@extends('layouts.app')

@section('title', 'Бронирование оплачено')

@section('content')
    <div class="mx-auto max-w-lg px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="mt-4 text-2xl font-bold text-slate-900">Оплата прошла успешно</h1>
            <p class="mt-2 text-slate-600">Бронирование подтверждено.</p>
            <dl class="mt-6 rounded-xl bg-slate-50 p-4 text-left space-y-2">
                <div class="flex justify-between gap-4"><dt class="text-slate-500">ФИО</dt><dd class="font-medium text-slate-900">{{ $booking->full_name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Телефон</dt><dd class="font-medium text-slate-900">{{ $booking->phone }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Часов</dt><dd class="font-medium text-slate-900">{{ $booking->hours }}</dd></div>
                @if($booking->skateSize)
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Коньки</dt><dd class="font-medium text-slate-900">{{ $booking->skate->name }}, размер {{ $booking->skateSize->size }}</dd></div>
                @endif
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Сумма</dt><dd class="font-medium text-slate-900">{{ $booking->amount }} ₽</dd></div>
            </dl>
            <a href="{{ route('home') }}" class="mt-8 inline-block rounded-xl bg-sky-600 px-6 py-3 font-semibold text-white transition hover:bg-sky-700">На главную</a>
        </div>
    </div>
@endsection
