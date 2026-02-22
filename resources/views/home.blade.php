@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div class="max-w-2xl">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Ледовый каток
                </h1>
                <p class="mt-4 text-lg text-slate-600">
                    Катайтесь в своё удовольствие. Покупайте билеты и бронируйте коньки онлайн.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('ticket.index') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-sky-700 active:scale-[0.98]">
                        Купить билет — 300 ₽
                    </a>
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 active:scale-[0.98]">
                        Бронировать коньки
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <h2 class="text-2xl font-bold text-slate-900">Условия</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                <h3 class="font-semibold text-slate-900">Вход на каток</h3>
                <p class="mt-2 text-slate-600">300 ₽ — оплачивается один раз. После оплаты вы получаете доступ на каток.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                <h3 class="font-semibold text-slate-900">Аренда коньков</h3>
                <p class="mt-2 text-slate-600">150 ₽ за 1 час. Можно выбрать коньки при бронировании или прийти со своими.</p>
            </div>
        </div>
    </section>
@endsection
