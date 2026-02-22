<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skate extends Model
{
    protected $fillable = ['name'];

    public function sizes(): HasMany
    {
        return $this->hasMany(SkateSize::class);
    }
}
