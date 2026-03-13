<nav class="sticky top-0 z-30 flex items-center justify-between h-20 px-4 sm:px-8 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
    
    <!-- Лява част: Бутон за мобилен сайдбар (Hamburger) -->
    <div class="flex items-center">
        <!-- Забележка: Този бутон трябва да контролира променлива за отваряне на сайдбара при мобилни устройства -->
        <button type="button" class="p-2 text-gray-500 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors lg:hidden">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>
      
    <!-- Дясна част: Потребителско меню -->
    <div class="flex items-center gap-4">
        
        <!-- Падащо меню за профила (Alpine.js) -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors focus:outline-none">
                <div class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Здравей, {{ auth()->user()->email }}
                </div>
                <!-- Кръгъл аватар (placeholder) -->
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300">
                    <i class="far fa-user text-sm"></i>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Съдържание на падащото меню -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 py-2 z-50"
                 style="display: none;">
                
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-800 transition-colors">
                    <i class="far fa-user w-4 text-center"></i> Профил
                </a>



                <div class="my-1 border-t border-gray-100 dark:border-gray-800"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Изход
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>