<?php

namespace App\MoonShine\Resources;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Date;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;

class BookingResource extends ModelResource
{
    protected string $model = Booking::class;

    protected string $title = 'Бронирования';

    protected string $column = 'full_name';

    protected array $with = ['skate', 'skateSize'];

    public function rules(Model $item): array
    {
        return [];
    }

    public function query(): Builder
    {
        return parent::query()->orderByDesc('created_at');
    }

    public function getActiveActions(): array
    {
        return ['view', 'massDelete'];
    }

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('ФИО', 'full_name'),
            Text::make('Телефон', 'phone'),
            Number::make('Часов', 'hours'),
            Switcher::make('С билетом', 'need_ticket'),
            BelongsTo::make('Коньки', 'skate', resource: new SkateResource()),
            Text::make('Размер', 'skate_size_id', fn ($m) => $m->skateSize ? (string) $m->skateSize->size : '—'),
            Number::make('Сумма', 'amount'),
            Text::make('Статус', 'status')->badge(fn ($v) => $v === 'paid' ? 'green' : 'gray'),
            Date::make('Оплачено', 'paid_at'),
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('ФИО', 'full_name'),
            Text::make('Телефон', 'phone'),
            Number::make('Часов', 'hours'),
            Text::make('Коньки', 'skate_id', fn ($m) => $m->skate ? $m->skate->name . ', р. ' . ($m->skateSize?->size ?? '—') : '—'),
            Number::make('Сумма', 'amount'),
            Text::make('Статус', 'status')->badge(fn ($v) => $v === 'paid' ? 'green' : 'gray'),
            Date::make('Оплачено', 'paid_at'),
        ];
    }
}
