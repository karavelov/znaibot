@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Нов потребител</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Полета маркирани с <span class="text-red-400">*</span> са задължителни.</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @php
            $input = 'w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all';
            $label = 'block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="{{ $label }}">Профилна снимка</label>
                    <input type="file" name="image" accept="image/*"
                           class="{{ $input }} file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                </div>

                <div>
                    <label class="{{ $label }}">Име <span class="text-red-400">(*)</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Имейл <span class="text-red-400">(*)</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Телефон</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Пол</label>
                    <select name="gender" class="{{ $input }}">
                        <option value="">— Изберете пол —</option>
                        <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Мъж</option>
                        <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Жена</option>
                    </select>
                </div>

                <div>
                    <label class="{{ $label }}">Instagram</label>
                    <input type="text" name="instagram" value="{{ old('instagram') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Facebook</label>
                    <input type="text" name="facebook" value="{{ old('facebook') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Дата на раждане</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Място на раждане</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Гражданство</label>
                    <input type="text" name="citizenship" value="{{ old('citizenship') }}" class="{{ $input }}">
                </div>

                <div class="md:col-span-2">
                    <label class="{{ $label }}">Достъп <span class="text-red-400">(*)</span></label>
                    <select id="roleSelect" name="role" class="{{ $input }}">
                        <option value="">Изберете</option>
                        <option value="admin">Администратор</option>
                        <option value="user">Потребител</option>
                        <option value="student">Ученик</option>
                        <option value="teacher">Учител</option>
                        <option value="parent">Родител</option>
                        <option value="security">Охрана</option>
                    </select>
                </div>

                <div class="md:col-span-2" id="klas-field" style="display:none;">
                    <label class="{{ $label }}">Клас</label>
                    <select name="klas_id" class="klas-select {{ $input }}">
                        <option value="">— Изберете клас —</option>
                        @foreach($klasses as $klas)
                            <option value="{{ $klas->id }}">{{ $klas->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2" id="homeroom-field" style="display:none;">
                    <label class="{{ $label }}">Класен ръководител на</label>
                    <select name="homeroom_klas_id" class="homeroom-select {{ $input }}">
                        <option value="">— Изберете клас —</option>
                        @foreach($klasses as $klas)
                            <option value="{{ $klas->id }}">{{ $klas->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="father-field" style="display:none;">
                    <label class="{{ $label }}">Баща (Родител — мъж)</label>
                    <select name="parent_father_id" class="father-select {{ $input }}">
                        <option value="">— Изберете —</option>
                        @foreach($fathers as $father)
                            <option value="{{ $father->id }}">{{ $father->name }} ({{ $father->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="mother-field" style="display:none;">
                    <label class="{{ $label }}">Майка (Родител — жена)</label>
                    <select name="parent_mother_id" class="mother-select {{ $input }}">
                        <option value="">— Изберете —</option>
                        @foreach($mothers as $mother)
                            <option value="{{ $mother->id }}">{{ $mother->name }} ({{ $mother->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="{{ $label }}">Личен лекар</label>
                    <input type="text" name="doctor_name" value="{{ old('doctor_name') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Телефон на лекар</label>
                    <input type="text" name="doctor_phone" value="{{ old('doctor_phone') }}" class="{{ $input }}">
                </div>

                <div class="md:col-span-2">
                    <label class="{{ $label }}">NFC ID <span class="text-gray-300 normal-case font-medium tracking-normal">(уникален идентификатор на чип)</span></label>
                    <input type="text" name="nfc_id" value="{{ old('nfc_id') }}"
                           placeholder="Пример: 04:A3:2B:11:CD:EF"
                           class="{{ $input }} @error('nfc_id') !border-red-400 @enderror">
                    @error('nfc_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="{{ $label }}">Парола <span class="text-red-400">(*)</span></label>
                    <input type="password" name="password" class="{{ $input }}">
                </div>

                <div>
                    <label class="{{ $label }}">Повтори парола <span class="text-red-400">(*)</span></label>
                    <input type="password" name="password_confirmation" class="{{ $input }}">
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

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.klas-select, .homeroom-select, .father-select, .mother-select').select2({ theme: 'classic', width: '100%' });

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
    });
</script>
@endpush