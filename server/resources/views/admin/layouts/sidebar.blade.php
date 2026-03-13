<!-- Основен контейнер: Плътно бяло, тънка сива линия отдясно, без излишни сенки -->
<style>
  /* Compact sidebar nav items */
  aside nav a[class*="rounded-xl"],
  aside nav button[class*="rounded-xl"] {
    padding-top: 0.375rem !important;
    padding-bottom: 0.375rem !important;
  }
</style>
<aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 flex flex-col transition-colors duration-300">
    
    <!-- Лого: Изчистено, без фонове -->
    <div class="h-14 flex items-center px-6 border-b border-gray-100 dark:border-gray-800 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="text-xl font-black tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
            <!-- Добавих малка синя точка за акцент (опционално) -->
            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
            Админ Панел
        </a>
    </div>

    <!-- Навигация: Повече отстояние, по-меки форми -->
    <nav class="flex-1 px-3 py-2 space-y-0.5 overflow-y-auto">
        
        <!-- Заглавие на секция -->
        <div class="px-3 mt-1 mb-1 text-[10px] font-bold tracking-widest text-gray-400 uppercase">
            Главно меню
        </div>

        <!-- 1. Начало (Активният елемент е със синия цвят от твоята снимка) -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 
                  {{ setActive(['admin.dashboard']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            <i class="fas fa-home w-5 text-center text-lg {{ setActive(['admin.dashboard']) ? 'text-white' : 'text-gray-400' }}"></i>
            <span>Начало</span>
        </a>

        <!-- 2. Тестове (Падащо меню) -->
        <div x-data="{ expanded: {{ setActive(['admin.quizzes.*','admin.questions.*','admin.rank.*','admin.quiz-category.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" 
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 
                           {{ setActive(['admin.quizzes.*','admin.questions.*','admin.rank.*','admin.quiz-category.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-layer-group w-5 text-center text-lg {{ setActive(['admin.quizzes.*','admin.questions.*','admin.rank.*','admin.quiz-category.*']) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}"></i>
                    <span>Викторина</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            
            <!-- Подменю: Изчистено, с лява линия за йерархия -->
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                
                <li><a href="{{ route('admin.questions.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.questions.*']) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">Въпроси</a></li>
                <li><a href="{{ route('admin.rank.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.rank.*']) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">Медали</a></li>
            
            </ul>
        </div>

        <!-- 3. Галерия -->
        <div x-data="{ expanded: {{ setActive(['admin.gallery.*', 'admin.gallery-images.index']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.gallery.*', 'admin.gallery-images.index']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-images w-5 text-center text-lg {{ setActive(['admin.gallery.*', 'admin.gallery-images.index']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>Галерия</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.gallery.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.gallery.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Всички снимки</a></li>
            </ul>
        </div>

        <!-- 4. Партньори -->
        <div x-data="{ expanded: {{ setActive(['admin.brand.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.brand.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-handshake w-5 text-center text-lg {{ setActive(['admin.brand.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>Партньори</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.brand.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.brand.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Списък партньори</a></li>
            </ul>
        </div>

        <!-- 5. Клубове -->
        <a href="{{ route('admin.clubs.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 
                  {{ setActive(['admin.clubs.*']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            <i class="fas fa-shield-alt w-5 text-center text-lg {{ setActive(['admin.clubs.*']) ? 'text-white' : 'text-gray-400' }}"></i>
            <span>Клубове</span>
        </a>

        <!-- Разделител -->
        <div class="px-3 mt-2 mb-1 text-[10px] font-bold tracking-widest text-gray-400 uppercase border-t border-gray-100 dark:border-gray-800 pt-2">
            Управление
        </div>

        <!-- 6. Публикации -->
        <div x-data="{ expanded: {{ setActive(['admin.blog-category.*', 'admin.blog.*', 'admin.blog-comments.*', 'admin.blog-sub-category.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.blog-category.*', 'admin.blog.*', 'admin.blog-comments.*', 'admin.blog-sub-category.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-pen-nib w-5 text-center text-lg {{ setActive(['admin.blog-category.*', 'admin.blog.*', 'admin.blog-comments.*', 'admin.blog-sub-category.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>Новини</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.blog.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.blog.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Всички статии</a></li>
                <li><a href="{{ route('admin.blog-category.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.blog-category.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Категории</a></li>
                <!-- <li><a href="{{ route('admin.blog-sub-category.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.blog-sub-category.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Подкатегории</a></li> -->
            </ul>
        </div>

        <!-- 7. NFC -->
        <div x-data="{ expanded: {{ setActive(['admin.nfc-logs.*', 'admin.nfc-readers.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.nfc-logs.*', 'admin.nfc-readers.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-wifi w-5 text-center text-lg {{ setActive(['admin.nfc-logs.*', 'admin.nfc-readers.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>NFC Система</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.nfc-logs.presence') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.nfc-logs.presence']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Кой е в училище</a></li>
                <li><a href="{{ route('admin.nfc-logs.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.nfc-logs.index']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">NFC Журнал</a></li>
                <li><a href="{{ route('admin.nfc-logs.late') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.nfc-logs.late']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Закъснения</a></li>
                <li><a href="{{ route('admin.nfc-readers.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.nfc-readers.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">NFC Четци</a></li>
            </ul>
        </div>

        <!-- 8. Разписание -->
        <div x-data="{ expanded: {{ setActive(['admin.schedule.*', 'admin.semesters.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.schedule.*', 'admin.semesters.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-calendar-alt w-5 text-center text-lg {{ setActive(['admin.schedule.*', 'admin.semesters.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>Разписание</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.schedule.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.schedule.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Седмично разписание</a></li>
                <li><a href="{{ route('admin.semesters.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.semesters.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Настройка на срокове</a></li>
            </ul>
        </div>

        <div x-data="{ expanded: {{ setActive(['admin.subject-names.*', 'admin.subjects.*']) ? 'true' : 'false' }} }">
    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.subject-names.*', 'admin.subjects.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
        <div class="flex items-center gap-3">
            <i class="fas fa-book w-5 text-center text-lg {{ setActive(['admin.subject-names.*', 'admin.subjects.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span>Предмети</span>
        </div>
        <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
        <li>
            <a href="{{ route('admin.subject-names.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.subject-names.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                Списък с предмети
            </a>
        </li>
        <li>
            <a href="{{ route('admin.subjects.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.subjects.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">
                Учители
            </a>
        </li>
    </ul>
</div>

        <!-- Логове -->
        <a href="{{ route('admin.logs.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ setActive(['admin.logs.*']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            <i class="fas fa-scroll w-5 text-center text-lg {{ setActive(['admin.logs.*']) ? 'text-white' : 'text-gray-400' }}"></i>
            <span>Логове</span>
        </a>

        <!-- 9. Профили -->
        <div x-data="{ expanded: {{ setActive(['admin.users.*', 'admin.klasses.*', 'admin.allergens.*', 'admin.birthdays.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.users.*', 'admin.klasses.*', 'admin.allergens.*', 'admin.birthdays.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-circle w-5 text-center text-lg {{ setActive(['admin.users.*', 'admin.klasses.*', 'admin.allergens.*', 'admin.birthdays.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>Профили</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.users.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.users.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Потребители</a></li>
                <li><a href="{{ route('admin.klasses.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.klasses.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Класове</a></li>
                <li>
                    <a href="{{ route('admin.allergens.dashboard') }}" class="flex items-center gap-2 py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.allergens.*']) ? 'text-red-600' : 'text-gray-500 hover:text-red-600' }}">
                        Алергени
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.birthdays.index') }}" class="flex items-center gap-2 py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.birthdays.*']) ? 'text-orange-500' : 'text-gray-500 hover:text-orange-500' }}">
                        Рождени дни
                    </a>
                </li>
            </ul>
        </div>

        <!-- <div class="px-4 mt-6 mb-2 text-[11px] font-bold tracking-widest text-gray-400 uppercase">
            Система
        </div> -->

        <!-- 10. Персонализиране -->
        <!-- <div x-data="{ expanded: {{ setActive(['admin.slider.*', 'admin.footer-info.*', 'admin.home-page-settings.*', 'admin.footer-socials.*']) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ setActive(['admin.slider.*', 'admin.footer-info.*', 'admin.home-page-settings.*', 'admin.footer-socials.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-paint-roller w-5 text-center text-lg {{ setActive(['admin.slider.*', 'admin.footer-info.*', 'admin.home-page-settings.*', 'admin.footer-socials.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span>Дизайн & Изглед</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 text-gray-400" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="expanded" x-collapse class="mt-1 mb-2 relative before:absolute before:inset-y-0 before:left-6 before:w-[2px] before:bg-gray-100 dark:before:bg-gray-800" style="display: none;">
                <li><a href="{{ route('admin.slider.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.slider.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Слайдъри</a></li>
                <li><a href="{{ route('admin.footer-info.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.footer-info.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Футър</a></li>
                <li><a href="{{ route('admin.footer-socials.index') }}" class="block py-2 pl-12 pr-4 text-sm font-medium transition-colors {{ setActive(['admin.footer-socials.*']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900' }}">Социални мрежи</a></li>
            </ul>
        </div> -->

        <!-- 11. Настройки -->
        <!-- <a href="{{ route('admin.settings.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 
                  {{ setActive(['admin.settings.index']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            <i class="fas fa-cog w-5 text-center text-lg {{ setActive(['admin.settings.index']) ? 'text-white' : 'text-gray-400' }}"></i>
            <span>Системни Настройки</span>
        </a> -->

        <!-- 12. Абонати -->
        <!-- <a href="{{ route('admin.subscribers.index') }}" 
           class="flex items-center gap-3 px-4 py-3 mb-6 rounded-xl text-sm font-semibold transition-all duration-200 
                  {{ setActive(['admin.subscribers.index']) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
            <i class="fas fa-envelope-open-text w-5 text-center text-lg {{ setActive(['admin.subscribers.index']) ? 'text-white' : 'text-gray-400' }}"></i>
            <span>Абонати</span>
        </a> -->

    </nav>
</aside>