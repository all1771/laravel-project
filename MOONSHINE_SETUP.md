# Настройка админ-панели Moonshine

После установки зависимостей выполните:

```bash
composer update
php artisan moonshine:install
```

Создайте учётную запись администратора:

```bash
php artisan moonshine:user
```

Зарегистрируйте ресурсы в меню. Откройте `app/Providers/MoonShineServiceProvider.php` и в метод `menu()` добавьте пункты:

```php
use App\MoonShine\Resources\SkateResource;
use App\MoonShine\Resources\SkateSizeResource;
use App\MoonShine\Resources\TicketResource;
use App\MoonShine\Resources\BookingResource;
use MoonShine\Menu\MenuItem;

protected function menu(): array
{
    return [
        MenuItem::make('Коньки (модели)', new SkateResource()),
        MenuItem::make('Размеры и количество', new SkateSizeResource()),
        MenuItem::make('Бронирования', new BookingResource()),
        MenuItem::make('Оплаченные билеты', new TicketResource()),
    ];
}
```

В `resources()` того же провайдера добавьте (если нужно):

```php
protected function resources(): array
{
    return [
        new SkateResource(),
        new SkateSizeResource(),
        new BookingResource(),
        new TicketResource(),
    ];
}
```

Админ-панель будет доступна по адресу: `/admin`
