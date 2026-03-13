@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Настройки</h1>
        <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на системните настройки</p>
    </div>

    <!-- Tab Nav -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-2 flex gap-1">
        <button id="tab-btn-general"
                style="flex:1; padding:10px 16px; border-radius:1.5rem; font-size:.875rem; font-weight:700; background:#2563eb; color:#fff; border:none; cursor:pointer;">
            Главни настройки
        </button>
        <button id="tab-btn-email"
                style="flex:1; padding:10px 16px; border-radius:1.5rem; font-size:.875rem; font-weight:700; background:transparent; color:#6b7280; border:none; cursor:pointer;">
            Имейл настройки
        </button>
        <button id="tab-btn-logo"
                style="flex:1; padding:10px 16px; border-radius:1.5rem; font-size:.875rem; font-weight:700; background:transparent; color:#6b7280; border:none; cursor:pointer;">
            Лого и фавикон
        </button>
    </div>

    <!-- Tab: General -->
    <div id="tab-general">
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm p-8">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Име на сайт</label>
                        <input type="text" name="site_name" value="{{ @$generalSettings->site_name }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Оформление (на администратор)</label>
                        <select name="layout" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                            <option {{ @$generalSettings->layout == 'LTR' ? 'selected' : '' }} value="LTR">Ляво</option>
                            <option {{ @$generalSettings->layout == 'RTL' ? 'selected' : '' }} value="RTL">Дясно</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Имейл за контакти</label>
                            <input type="text" name="contact_email" value="{{ @$generalSettings->contact_email }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Телефон за контакти</label>
                            <input type="text" name="contact_phone" value="{{ @$generalSettings->contact_phone }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Адрес за контакти</label>
                        <input type="text" name="contact_address" value="{{ @$generalSettings->contact_address }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Карта (Embed Google Maps)</label>
                        <input type="text" name="map" value="{{ @$generalSettings->map }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Времева зона</label>
                        <select name="time_zone" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all select2">
                            <option value="">Изберете</option>
                            @foreach (config('custom_currency.time_zone') as $key => $timeZone)
                                <option {{ @$generalSettings->time_zone == $key ? 'selected' : '' }} value="{{ $key }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md transition-all">Запази</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab: Email -->
    <div id="tab-email" style="display:none">
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm p-8">
            <form action="{{ route('admin.email-settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Имейл</label>
                            <input type="text" name="email" value="{{ $emailSettings->email }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Хост</label>
                            <input type="text" name="host" value="{{ $emailSettings->host }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">SMTP потребител</label>
                            <input type="text" name="username" value="{{ $emailSettings->username }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">SMTP парола</label>
                            <input type="text" name="password" value="{{ $emailSettings->password }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Порт</label>
                            <input type="text" name="port" value="{{ $emailSettings->port }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Протокол</label>
                            <select name="encryption" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                                <option {{ $emailSettings->encryption == 'tls' ? 'selected' : '' }} value="tls">TLS</option>
                                <option {{ $emailSettings->encryption == 'ssl' ? 'selected' : '' }} value="ssl">SSL</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md transition-all">Запази</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab: Logo -->
    <div id="tab-logo" style="display:none">
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm p-8">
            <form action="{{ route('admin.logo-setting-update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400">Лого</label>
                        @if(@$logoSettings->logo)
                            <img src="{{ asset(@$logoSettings->logo) }}" class="h-16 object-contain rounded-2xl" alt="logo">
                        @endif
                        <input type="file" name="logo"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <input type="hidden" name="old_logo" value="{{ @$logoSettings->logo }}">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400">Фавикон</label>
                        @if(@$logoSettings->favicon)
                            <img src="{{ asset(@$logoSettings->favicon) }}" class="h-16 object-contain rounded-2xl" alt="favicon">
                        @endif
                        <input type="file" name="favicon"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <input type="hidden" name="old_favicon" value="{{ @$logoSettings->favicon }}">
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md transition-all">Запази</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var tabs = ['general', 'email', 'logo'];

    $('#tab-btn-general, #tab-btn-email, #tab-btn-logo').on('click', function () {
        var name = this.id.replace('tab-btn-', '');
        $.each(tabs, function (i, t) {
            $('#tab-' + t).toggle(t === name);
            if (t === name) {
                $('#tab-btn-' + t).css({ background: '#2563eb', color: '#fff' });
            } else {
                $('#tab-btn-' + t).css({ background: 'transparent', color: '#6b7280' });
            }
        });
    });
});
</script>
@endpush
