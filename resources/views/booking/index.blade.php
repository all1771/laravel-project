@extends('layouts.app')

@section('title', 'Бронирование коньков')

@section('content')
    <div class="mx-auto max-w-xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold text-slate-900">Бронирование коньков</h1>
            <p class="mt-2 text-slate-600">150 ₽ за 1 час. Можно выбрать коньки или прийти со своими.</p>

            <form action="{{ route('booking.store') }}" method="POST" class="mt-8 space-y-6" id="booking-form">
                @csrf
                @if($errors->has('form'))
                    <p class="text-sm text-red-600">{{ $errors->first('form') }}</p>
                @endif

                <div>
                    <label for="full_name" class="block text-sm font-medium text-slate-700">ФИО</label>
                    <input type="text" name="full_name" id="full_name" required
                           value="{{ old('full_name') }}"
                           class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                           placeholder="Иванов Иван Иванович">
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Телефон</label>
                    <input type="tel" name="phone" id="phone" required
                           value="{{ old('phone', '+7 (') }}"
                           maxlength="18"
                           class="input-phone mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                           placeholder="+7 (___) ___-__-__">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Количество часов аренды</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach([1, 2, 3, 4] as $h)
                            <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-300 px-4 py-3 transition has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50 has-[:checked]:ring-1 has-[:checked]:ring-sky-500">
                                <input type="radio" name="hours" value="{{ $h }}" {{ (int) old('hours', 1) === $h ? 'checked' : '' }} class="h-4 w-4 border-slate-300 text-sky-600 focus:ring-sky-500">
                                <span>{{ $h }} {{ $h === 1 ? 'час' : ($h < 5 ? 'часа' : 'часов') }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('hours')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="need_ticket" value="1" {{ old('need_ticket') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <span class="text-sm font-medium text-slate-700">Добавить входной билет (300 ₽)</span>
                    </label>
                </div>

                <div id="skates-block">
                    <label class="block text-sm font-medium text-slate-700">Коньки (необязательно)</label>
                    <p class="mt-1 text-sm text-slate-500">Оставьте пустым, если придёте со своими.</p>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="skate_id" class="block text-xs font-medium text-slate-500">Модель</label>
                            <select name="skate_id" id="skate_id" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                                <option value="">— не выбирать —</option>
                                @foreach($skates as $skate)
                                    <option value="{{ $skate->id }}" {{ (string) old('skate_id') === (string) $skate->id ? 'selected' : '' }}>{{ $skate->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="skate_size_id" class="block text-xs font-medium text-slate-500">Размер</label>
                            <select name="skate_size_id" id="skate_size_id" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                                <option value="">— выберите модель —</option>
                                @foreach($skates as $skate)
                                    @foreach($skate->sizes as $sz)
                                        <option value="{{ $sz->id }}" data-skate-id="{{ $skate->id }}" {{ $sz->quantity < 1 ? 'disabled' : '' }} {{ (string) old('skate_size_id') === (string) $sz->id ? 'selected' : '' }}>
                                            {{ $sz->size }} (в наличии: {{ $sz->quantity }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('skate_size_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <p class="text-sm text-slate-600" id="total-line">
                    Итого: <span id="total-value">0</span> ₽
                </p>

                <button type="submit" class="w-full rounded-xl bg-sky-600 py-3 font-semibold text-white shadow-sm transition hover:bg-sky-700 active:scale-[0.99]">
                    Перейти к оплате
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            var form = document.getElementById('booking-form');
            var needTicket = form.querySelector('input[name="need_ticket"]');
            var hoursRadios = form.querySelectorAll('input[name="hours"]');
            var skateSizeSelect = form.querySelector('#skate_size_id');
            var totalEl = document.getElementById('total-value');

            function getHours() {
                var h = 1;
                hoursRadios.forEach(function (r) { if (r.checked) h = parseInt(r.value, 10); });
                return h;
            }
            function updateTotal() {
                var ticket = needTicket && needTicket.checked ? 300 : 0;
                var skate = skateSizeSelect && skateSizeSelect.value ? 150 * getHours() : 0;
                var total = ticket + skate;
                totalEl.textContent = total;
            }
            needTicket && needTicket.addEventListener('change', updateTotal);
            hoursRadios && hoursRadios.forEach(function (r) { r.addEventListener('change', updateTotal); });
            skateSizeSelect && skateSizeSelect.addEventListener('change', updateTotal);
            updateTotal();

            var skateIdSelect = form.querySelector('#skate_id');
            if (skateIdSelect && skateSizeSelect) {
                skateIdSelect.addEventListener('change', function () {
                    var sid = this.value;
                    var opts = skateSizeSelect.querySelectorAll('option[data-skate-id]');
                    skateSizeSelect.value = '';
                    opts.forEach(function (o) {
                        o.style.display = o.getAttribute('data-skate-id') === sid ? '' : 'none';
                    });
                    updateTotal();
                });
                skateIdSelect.dispatchEvent(new Event('change'));
            }
        })();
    </script>
    @endpush
@endsection
