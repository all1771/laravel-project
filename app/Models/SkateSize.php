<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkateSize extends Model
{
    protected $fillable = ['skate_id', 'size', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
        'size' => 'integer',
    ];

    public function skate(): BelongsTo
    {
        return $this->belongsTo(Skate::class);
    }
}
