<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use App\Models\SkateSize;
use App\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function index(): View
    {
        $skates = Skate::with('sizes')->get();
        return view('booking.index', compact('skates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
            'hours' => 'required|integer|in:1,2,3,4',
            'need_ticket' => 'nullable|boolean',
            'skate_id' => 'nullable|exists:skates,id',
            'skate_size_id' => 'nullable|exists:skate_sizes,id',
        ];
        $validated = $request->validate($rules, [
            'phone.regex' => 'Телефон должен быть в формате +7 (___) ___-__-__',
        ]);

        $needTicket = (bool) ($request->input('need_ticket'));
        $hours = (int) $validated['hours'];
        $skateSizeId = $validated['skate_size_id'] ?? null;
        $skateId = $validated['skate_id'] ?? null;

        $amount = 0;
        if ($needTicket) {
            $amount += Booking::TICKET_ADDITION;
        }
        if ($skateSizeId) {
            $amount += Booking::PRICE_PER_HOUR * $hours;
        }

        if ($amount <= 0) {
            return back()->withErrors(['form' => 'Выберите хотя бы билет или коньки.'])->withInput();
        }

        $booking = Booking::create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'hours' => $hours,
            'need_ticket' => $needTicket,
            'skate_id' => $skateId,
            'skate_size_id' => $skateSizeId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        $returnUrl = route('booking.success', ['booking' => $booking->id]);
        $receiptItems = [];
        if ($needTicket) {
            $receiptItems[] = ['description' => 'Входной билет на каток', 'amount' => Booking::TICKET_ADDITION];
        }
        if ($skateSizeId) {
            $receiptItems[] = [
                'description' => "Аренда коньков, {$hours} ч.",
                'amount' => Booking::PRICE_PER_HOUR * $hours,
                'quantity' => 1,
            ];
        }
        $yookassa = app(YooKassaService::class);
        $payment = $yookassa->createPayment(
            (float) $amount,
            'Бронирование катка: ' . ($needTicket ? 'билет + ' : '') . ($skateSizeId ? "коньки {$hours} ч." : ''),
            $returnUrl,
            ['booking_id' => (string) $booking->id, 'type' => 'booking'],
            $validated['full_name'],
            $validated['phone'],
            $receiptItems
        );

        $booking->update(['yookassa_payment_id' => $payment['payment_id']]);

        return redirect()->away($payment['redirect_url']);
    }

    public function success(Request $request, Booking $booking): View|RedirectResponse
    {
        if ($booking->isPaid()) {
            return view('booking.success', compact('booking'));
        }

        $yookassa = app(YooKassaService::class);
        $data = $yookassa->getPayment($booking->yookassa_payment_id);
        if ($data && ($data['status'] ?? '') === 'succeeded') {
            $booking->update(['status' => 'paid', 'paid_at' => now()]);
            if ($booking->skate_size_id) {
                SkateSize::where('id', $booking->skate_size_id)->decrement('quantity');
            }
            return view('booking.success', compact('booking'));
        }

        return redirect()->route('booking.index')->with('error', 'Оплата не завершена. Попробуйте снова.');
    }
}
