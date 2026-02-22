<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    public const PRICE_PER_HOUR = 150;
    public const TICKET_ADDITION = 300;

    protected $fillable = [
        'full_name',
        'phone',
        'hours',
        'need_ticket',
        'skate_id',
        'skate_size_id',
        'amount',
        'yookassa_payment_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'hours' => 'integer',
        'need_ticket' => 'boolean',
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function skate(): BelongsTo
    {
        return $this->belongsTo(Skate::class);
    }

    public function skateSize(): BelongsTo
    {
        return $this->belongsTo(SkateSize::class, 'skate_size_id');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
