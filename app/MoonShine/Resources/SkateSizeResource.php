<?php

namespace App\MoonShine\Resources;

use App\Models\SkateSize;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;

class SkateSizeResource extends ModelResource
{
    protected string $model = SkateSize::class;

    protected string $title = 'Размеры и количество';

    public function rules(Model $item): array
    {
        return [
            'skate_id' => 'required|exists:skates,id',
            'size' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Модель коньков', 'skate', resource: new SkateResource()),
            Number::make('Размер', 'size')->min(1)->required(),
            Number::make('Количество', 'quantity')->min(0)->required(),
        ];
    }
}
