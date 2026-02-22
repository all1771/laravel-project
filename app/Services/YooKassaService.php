<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class YooKassaService
{
    private string $shopId;
    private string $secretKey;
    private string $baseUrl = 'https://api.yookassa.ru/v3';

    public function __construct()
    {
        $this->shopId = config('services.yookassa.shop_id');
        $this->secretKey = config('services.yookassa.secret_key');
    }

    /**
     * Нормализовать телефон для чека: только цифры, начинается с 7.
     */
    private function normalizePhoneForReceipt(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (str_starts_with($digits, '8')) {
            $digits = '7' . substr($digits, 1);
        } elseif (!str_starts_with($digits, '7')) {
            $digits = '7' . $digits;
        }
        return substr($digits, 0, 11);
    }

    /**
     * Создать платёж, вернуть URL для редиректа и id платежа.
     * @param float $amount Сумма в рублях
     * @param string $description Описание заказа
     * @param string $returnUrl URL возврата после оплаты
     * @param array $metadata Метаданные (id заказа, тип)
     * @param string|null $customerName ФИО для чека (54-ФЗ)
     * @param string|null $customerPhone Телефон для чека (форма +7 (___) ___-__-__)
     * @param array $receiptItems Позиции чека [['description' => '...', 'amount' => 300.00], ...]. Если пусто — одна позиция по $amount и $description.
     */
    public function createPayment(
        float $amount,
        string $description,
        string $returnUrl,
        array $metadata = [],
        ?string $customerName = null,
        ?string $customerPhone = null,
        array $receiptItems = []
    ): array {
        $value = number_format($amount, 2, '.', '');
        $body = [
            'amount' => [
                'value' => $value,
                'currency' => 'RUB',
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $returnUrl,
            ],
            'description' => $description,
            'metadata' => $metadata,
        ];

        if ($customerName !== null && $customerPhone !== null) {
            $items = [];
            if ($receiptItems !== []) {
                foreach ($receiptItems as $item) {
                    $items[] = [
                        'description' => $item['description'],
                        'quantity' => (float) ($item['quantity'] ?? 1),
                        'amount' => [
                            'value' => number_format((float) $item['amount'], 2, '.', ''),
                            'currency' => 'RUB',
                        ],
                        'vat_code' => 1,
                        'payment_mode' => 'full_payment',
                        'payment_subject' => 'service',
                    ];
                }
            } else {
                $items[] = [
                    'description' => $description,
                    'quantity' => 1.000,
                    'amount' => ['value' => $value, 'currency' => 'RUB'],
                    'vat_code' => 1,
                    'payment_mode' => 'full_payment',
                    'payment_subject' => 'service',
                ];
            }
            $body['receipt'] = [
                'customer' => [
                    'full_name' => $customerName,
                    'phone' => $this->normalizePhoneForReceipt($customerPhone),
                ],
                'items' => $items,
                'internet' => 'true',
            ];
        }

        $response = Http::withBasicAuth($this->shopId, $this->secretKey)
            ->withHeaders([
                'Idempotence-Key' => (string) Str::uuid(),
            ])
            ->post("{$this->baseUrl}/payments", $body);

        if (!$response->successful()) {
            throw new \RuntimeException('YooKassa error: ' . ($response->json('description') ?? $response->body()));
        }

        $data = $response->json();
        $confirmation = $data['confirmation'] ?? [];
        $redirectUrl = $confirmation['confirmation_url'] ?? '';

        return [
            'payment_id' => $data['id'],
            'redirect_url' => $redirectUrl,
            'status' => $data['status'] ?? 'pending',
        ];
    }

    /**
     * Получить статус платежа.
     */
    public function getPayment(string $paymentId): ?array
    {
        $response = Http::withBasicAuth($this->shopId, $this->secretKey)
            ->get("{$this->baseUrl}/payments/{$paymentId}");

        if (!$response->successful()) {
            return null;
        }

        return $response->json();
    }
}
