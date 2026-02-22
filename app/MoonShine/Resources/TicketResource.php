<?php

namespace App\MoonShine\Resources;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Date;
use MoonShine\Resources\ModelResource;

class TicketResource extends ModelResource
{
    protected string $model = Ticket::class;

    protected string $title = 'Оплаченные билеты';

    protected string $column = 'customer_name';

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
            Text::make('ФИО', 'customer_name'),
            Text::make('Телефон', 'customer_phone'),
            Text::make('Сумма', 'amount')->badge('blue'),
            Text::make('Статус', 'status')->badge(fn ($v) => $v === 'paid' ? 'green' : 'gray'),
            Date::make('Оплачен', 'paid_at'),
            Text::make('YooKassa ID', 'yookassa_payment_id'),
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('ФИО', 'customer_name'),
            Text::make('Телефон', 'customer_phone'),
            Text::make('Сумма', 'amount'),
            Text::make('Статус', 'status')->badge(fn ($v) => $v === 'paid' ? 'green' : 'gray'),
            Date::make('Оплачен', 'paid_at'),
        ];
    }
}
