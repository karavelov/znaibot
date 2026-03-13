@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редактиране</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $customer->name }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">

        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
            <form action="{{ route('admin.users.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                $input = 'w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all';
                $label = 'block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2';
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @if($customer->image)
                    <div class="md:col-span-2">
                        <label class="{{ $label }}">Текуща снимка</label>
                        <img src="{{ asset($customer->image) }}" class="w-20 h-20 rounded-2xl object-cover object-center border border-gray-100" alt="Profile">
                    </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="{{ $label }}">Профилна снимка</label>
                        <input type="file" name="image" accept="image/*"
                               class="{{ $input }} file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="{{ $label }}">Име</label>
                        <input type="text" name="name" value="{{ $customer->name }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Имейл</label>
                        <input type="email" name="email" value="{{ $customer->email }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Телефон</label>
                        <input type="text" name="phone" value="{{ $customer->phone }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Пол</label>
                        <select name="gender" class="{{ $input }}">
                            <option value="">— Изберете пол —</option>
                            <option value="male" {{ $customer->gender=='male' ? 'selected' : '' }}>Мъж</option>
                            <option value="female" {{ $customer->gender=='female' ? 'selected' : '' }}>Жена</option>
                        </select>
                    </div>

                    <div>
                        <label class="{{ $label }}">Instagram</label>
                        <input type="text" name="instagram" value="{{ $customer->instagram }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Facebook</label>
                        <input type="text" name="facebook" value="{{ $customer->facebook }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Дата на раждане</label>
                        <input type="date" name="birth_date" value="{{ $customer->birth_date }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Място на раждане</label>
                        <input type="text" name="birth_place" value="{{ $customer->birth_place }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Гражданство</label>
                        <input type="text" name="citizenship" value="{{ $customer->citizenship }}" class="{{ $input }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="{{ $label }}">Достъп <span class="text-red-400">(*)</span></label>
                        <select id="roleSelect" name="role" class="{{ $input }}">
                            <option value="">Изберете</option>
                            <option value="admin"    {{ $customer->role=='admin'    ? 'selected' : '' }}>Администратор</option>
                            <option value="user"     {{ $customer->role=='user'     ? 'selected' : '' }}>Потребител</option>
                            <option value="student"  {{ $customer->role=='student'  ? 'selected' : '' }}>Ученик</option>
                            <option value="teacher"  {{ $customer->role=='teacher'  ? 'selected' : '' }}>Учител</option>
                            <option value="parent"   {{ $customer->role=='parent'   ? 'selected' : '' }}>Родител</option>
                            <option value="security" {{ $customer->role=='security' ? 'selected' : '' }}>Охрана</option>
                        </select>
                    </div>

                    <div class="md:col-span-2" id="klas-field" style="display:none;">
                        <label class="{{ $label }}">Клас</label>
                        <select name="klas_id" class="klas-select {{ $input }}">
                            <option value="">— Изберете клас —</option>
                            @foreach($klasses as $klas)
                                <option value="{{ $klas->id }}" {{ $customer->klas_id==$klas->id ? 'selected' : '' }}>{{ $klas->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2" id="homeroom-field" style="display:none;">
                        <label class="{{ $label }}">Класен ръководител на</label>
                        <select name="homeroom_klas_id" class="homeroom-select {{ $input }}">
                            <option value="">— Изберете клас —</option>
                            @foreach($klasses as $klas)
                                <option value="{{ $klas->id }}" {{ $customer->homeroom_klas_id==$klas->id ? 'selected' : '' }}>{{ $klas->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="father-field" style="display:none;">
                        <label class="{{ $label }}">Баща (Родител — мъж)</label>
                        <select name="parent_father_id" class="father-select {{ $input }}">
                            <option value="">— Изберете —</option>
                            @foreach($fathers as $father)
                                <option value="{{ $father->id }}" {{ $customer->parent_father_id==$father->id ? 'selected' : '' }}>{{ $father->name }} ({{ $father->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="mother-field" style="display:none;">
                        <label class="{{ $label }}">Майка (Родител — жена)</label>
                        <select name="parent_mother_id" class="mother-select {{ $input }}">
                            <option value="">— Изберете —</option>
                            @foreach($mothers as $mother)
                                <option value="{{ $mother->id }}" {{ $customer->parent_mother_id==$mother->id ? 'selected' : '' }}>{{ $mother->name }} ({{ $mother->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="{{ $label }}">Личен лекар</label>
                        <input type="text" name="doctor_name" value="{{ $customer->doctor_name }}" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Телефон на лекар</label>
                        <input type="text" name="doctor_phone" value="{{ $customer->doctor_phone }}" class="{{ $input }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="{{ $label }}">NFC ID <span class="text-gray-300 normal-case font-medium tracking-normal">(уникален идентификатор на чип)</span></label>
                        <input type="text" name="nfc_id"
                               value="{{ old('nfc_id', $customer->nfc_id) }}"
                               placeholder="Пример: 04:A3:2B:11:CD:EF"
                               class="{{ $input }} @error('nfc_id') !border-red-400 @enderror">
                        @error('nfc_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                        Запази
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Отказ
                    </a>
                </div>

            </form>
        </div>

        <div class="space-y-6">

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Последна активност</h3>
                @if($customer->last_login_at)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Влизане</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $customer->last_login_at }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">IP</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $customer->last_login_ip }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-gray-400 shrink-0">Браузър</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 text-right text-xs leading-snug">{{ $customer->useragent }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-400">Няма информация за последно влизане.</p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Смяна на парола</h3>
                <form method="POST" action="{{ route('admin.users.password.update', $customer->id) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Нова парола</label>
                            <input type="password" name="password"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all @error('password') !border-red-400 @enderror">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Повтори парола</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                        <button type="submit"
                                class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                            Запази парола
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-red-100 dark:border-red-900/30 rounded-[2rem] shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-red-400">Алергени</h3>
                    <a href="{{ route('admin.allergens.dashboard') }}"
                       class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors flex items-center gap-1">
                        Dashboard <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>

                <div id="allergenList" class="flex flex-wrap gap-2 mb-4 min-h-[32px]">
                    @forelse($customer->allergens as $allergen)
                    <div class="inline-flex items-center gap-1.5 allergen-row px-3 py-1 rounded-full text-xs font-semibold"
                         id="allergen-row-{{ $allergen->id }}"
                         style="background:{{ $allergen->color }}1a; border: 1px solid {{ $allergen->color }};">
                        <span style="color:{{ $allergen->color }}">{{ $allergen->name }}</span>
                        @if($allergen->pivot->notes)
                            <span class="text-gray-400 italic">({{ $allergen->pivot->notes }})</span>
                        @endif
                        <button type="button" class="btn-remove-allergen ml-0.5"
                                data-allergen-id="{{ $allergen->id }}"
                                data-url="{{ route('admin.allergens.user.remove', [$customer->id, $allergen->id]) }}"
                                style="color:{{ $allergen->color }}">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 w-full" id="noAllergenMsg">
                        <i class="fas fa-check-circle text-green-400 mr-1"></i>Няма алергени.
                    </p>
                    @endforelse
                </div>

                <div class="space-y-2">
                    <select id="allergenSelect"
                            class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-700 dark:text-gray-300 focus:outline-none allergen-select2">
                        <option value="">— Избери алерген —</option>
                        @foreach($allAllergens as $a)
                            <option value="{{ $a->id }}" data-color="{{ $a->color }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="allergenNotes"
                           placeholder="Бележка (по желание)"
                           class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-700 dark:text-gray-300 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400/20 focus:border-red-300 transition-all">
                    <button type="button" id="btnAddAllergen"
                            class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-2xl text-sm font-bold shadow-sm shadow-red-500/20 transition-all active:scale-95">
                        <i class="fas fa-plus mr-1.5 text-xs"></i> Добави алерген
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.klas-select, .homeroom-select, .father-select, .mother-select, .allergen-select2').select2({ theme: 'classic', width: '100%' });

        function toggleRoleFields() {
            var role = $('#roleSelect').val();
            if (role === 'student') {
                $('#father-field, #mother-field, #klas-field').show();
                $('#homeroom-field').hide();
            } else if (role === 'teacher') {
                $('#father-field, #mother-field, #klas-field').hide();
                $('#homeroom-field').show();
            } else {
                $('#father-field, #mother-field, #klas-field, #homeroom-field').hide();
            }
        }

        toggleRoleFields();
        $('#roleSelect').on('change', toggleRoleFields);

        $('#btnAddAllergen').on('click', function () {
            var allergenId = $('#allergenSelect').val();
            var notes      = $('#allergenNotes').val();
            if (!allergenId) { alert('Изберете алерген.'); return; }

            $.ajax({
                url: '{{ route('admin.allergens.user.add', $customer->id) }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', allergen_id: allergenId, notes: notes },
                success: function (res) {
                    $('#noAllergenMsg').remove();
                    var html = '<div class="inline-flex items-center gap-1.5 allergen-row px-3 py-1 rounded-full text-xs font-semibold"'
                        + ' id="allergen-row-' + res.id + '"'
                        + ' style="background:' + res.color + '1a; border: 1px solid ' + res.color + ';">';
                    html += '<span style="color:' + res.color + '">' + $('<span>').text(res.name).html() + '</span>';
                    if (res.notes) html += '<span class="text-gray-400 italic">('+$('<span>').text(res.notes).html()+')</span>';
                    html += '<button type="button" class="btn-remove-allergen ml-0.5"'
                        + ' data-allergen-id="' + res.id + '" data-url="' + res.remove_url + '"'
                        + ' style="color:' + res.color + '"><i class="fas fa-times text-[10px]"></i></button></div>';
                    $('#allergenList').append(html);
                    $('#allergenSelect option[value="' + res.id + '"]').remove();
                    $('#allergenSelect').val('').trigger('change');
                    $('#allergenNotes').val('');
                },
                error: function (xhr) { alert(xhr.responseJSON?.error ?? 'Грешка при добавяне.'); }
            });
        });

        $('body').on('click', '.btn-remove-allergen', function () {
            var btn = $(this), allergenId = btn.data('allergen-id'), url = btn.data('url'), row = btn.closest('.allergen-row'), name = row.find('span').first().text();
            if (!confirm('Премахни алерген "' + name + '"?')) return;
            $.ajax({
                url: url, method: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function () {
                    row.fadeOut(200, function () {
                        $(this).remove();
                        if ($('#allergenList .allergen-row').length === 0) {
                            $('#allergenList').html('<p class="text-xs text-gray-400 w-full" id="noAllergenMsg"><i class="fas fa-check-circle text-green-400 mr-1"></i>Няма алергени.</p>');
                        }
                    });
                    $('#allergenSelect').append($('<option>').val(allergenId).text(name));
                },
                error: function () { alert('Грешка при премахване.'); }
            });
        });
    });
</script>
@endpush