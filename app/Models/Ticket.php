<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public const AMOUNT = 300;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'amount',
        'yookassa_payment_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'integer',
    ];

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
