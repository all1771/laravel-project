@extends('layouts.app')

@section('title', 'Купить билет')

@section('content')
    <div class="mx-auto max-w-lg px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold text-slate-900">Входной билет на каток</h1>
            <p class="mt-2 text-slate-600">300 ₽ — оплачивается один раз. После оплаты вы получаете доступ на каток.</p>

            <form action="{{ route('ticket.store') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-slate-700">ФИО</label>
                    <input type="text" name="customer_name" id="customer_name" required
                           value="{{ old('customer_name') }}"
                           class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                           placeholder="Иванов Иван Иванович">
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-slate-700">Телефон</label>
                    <input type="tel" name="customer_phone" id="customer_phone" required
                           value="{{ old('customer_phone', '+7 (') }}"
                           maxlength="18"
                           class="input-phone mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                           placeholder="+7 (___) ___-__-__">
                    @error('customer_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full rounded-xl bg-sky-600 py-3 font-semibold text-white shadow-sm transition hover:bg-sky-700 active:scale-[0.99]">
                    Оплатить 300 ₽
                </button>
            </form>
        </div>
    </div>

@endsection
