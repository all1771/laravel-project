<?php

namespace Database\Seeders;

use App\Models\Skate;
use App\Models\SkateSize;
use Illuminate\Database\Seeder;

class SkateSeeder extends Seeder
{
    public function run(): void
    {
        $adult = Skate::create(['name' => 'Взрослые']);
        foreach ([38, 39, 40, 41, 42, 43, 44] as $size) {
            SkateSize::create([
                'skate_id' => $adult->id,
                'size' => $size,
                'quantity' => 5,
            ]);
        }

        $child = Skate::create(['name' => 'Детские']);
        foreach ([28, 30, 32, 34, 36] as $size) {
            SkateSize::create([
                'skate_id' => $child->id,
                'size' => $size,
                'quantity' => 5,
            ]);
        }
    }
}
