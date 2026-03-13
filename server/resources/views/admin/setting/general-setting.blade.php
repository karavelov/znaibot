<div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Име на сайт</label>
                <input type="text" name="site_name" value="{{ @$generalSettings->site_name }}"
                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Оформление (на администратор)</label>
                <select name="layout"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    <option {{ @$generalSettings->layout == 'LTR' ? 'selected' : '' }} value="LTR">Ляво</option>
                    <option {{ @$generalSettings->layout == 'RTL' ? 'selected' : '' }} value="RTL">Дясно</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Имейл за контакти</label>
                    <input type="text" name="contact_email" value="{{ @$generalSettings->contact_email }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Телефон за контакти</label>
                    <input type="text" name="contact_phone" value="{{ @$generalSettings->contact_phone }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Адрес за контакти</label>
                <input type="text" name="contact_address" value="{{ @$generalSettings->contact_address }}"
                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Карта (Embed Google Maps)</label>
                <input type="text" name="map" value="{{ @$generalSettings->map }}"
                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>

            <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Времева зона</label>
                <select name="time_zone"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all select2">
                    <option value="">Изберете</option>
                    @foreach (config('custom_currency.time_zone') as $key => $timeZone)
                        <option {{ @$generalSettings->time_zone == $key ? 'selected' : '' }} value="{{ $key }}">{{ $key }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
            <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                Запази
            </button>
        </div>
    </form>
</div>
