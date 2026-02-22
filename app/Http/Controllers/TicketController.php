<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    public function index(): View
    {
        return view('ticket.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
        ], [
            'customer_phone.regex' => 'Телефон должен быть в формате +7 (___) ___-__-__',
        ]);

        $ticket = Ticket::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'amount' => Ticket::AMOUNT,
            'status' => 'pending',
        ]);

        $returnUrl = route('ticket.success', ['ticket' => $ticket->id]);
        $yookassa = app(YooKassaService::class);
        $payment = $yookassa->createPayment(
            (float) Ticket::AMOUNT,
            'Входной билет на каток',
            $returnUrl,
            ['ticket_id' => (string) $ticket->id, 'type' => 'ticket'],
            $validated['customer_name'],
            $validated['customer_phone']
        );

        $ticket->update(['yookassa_payment_id' => $payment['payment_id']]);

        return redirect()->away($payment['redirect_url']);
    }

    public function success(Request $request, Ticket $ticket): View|RedirectResponse
    {
        if ($ticket->isPaid()) {
            return view('ticket.success', compact('ticket'));
        }

        $yookassa = app(YooKassaService::class);
        $data = $yookassa->getPayment($ticket->yookassa_payment_id);
        if ($data && ($data['status'] ?? '') === 'succeeded') {
            $ticket->update(['status' => 'paid', 'paid_at' => now()]);
            return view('ticket.success', compact('ticket'));
        }

        return redirect()->route('ticket.index')->with('error', 'Оплата не завершена. Попробуйте снова.');
    }
}
