<?php

namespace App\MoonShine\Resources;

use App\Models\Skate;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

class SkateResource extends ModelResource
{
    protected string $model = Skate::class;

    protected string $title = 'Коньки (модели)';

    protected string $column = 'name';

    public function rules(Model $item): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Название модели', 'name')->required(),
        ];
    }
}
