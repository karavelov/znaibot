@extends('admin.layouts.master')

@section('content')
<!-- Главен контейнер с Alpine за управление на табовете -->
<div class="p-6 sm:p-10 space-y-8" x-data="{ activeTab: 'users' }">
    
    <!-- Хедър на страницата -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                <i class="fas fa-heartbeat text-red-500"></i> Алергени
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Преглед на ученици с регистрирани алергии</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('admin.allergens.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-500 hover:text-blue-600 transition-all shadow-sm">
                <i class="fas fa-cog"></i> Управление
            </a>
        </div>
    </div>

    <!-- Търсачка и Филтри -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <form method="GET" action="{{ route('admin.allergens.dashboard') }}" class="flex flex-1 items-center gap-3">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Търси по име на ученик..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                Търси
            </button>
            
            @if($search)
                <a href="{{ route('admin.allergens.dashboard') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-semibold transition-colors">
                    Изчисти
                </a>
            @endif
        </form>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700">
            <i class="fas fa-users mr-1"></i>
            {{ $usersWithAllergens->count() }} {{ $usersWithAllergens->count() == 1 ? 'човек' : 'човека' }} с алергии
        </div>
    </div>

    <!-- Табове (Apple Style Switcher) -->
    <div class="inline-flex bg-gray-100 dark:bg-gray-800 p-1 rounded-xl">
        <button @click="activeTab = 'users'" 
                class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                :class="activeTab === 'users' ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
            <i class="fas fa-user mr-1.5"></i> По ученик
        </button>
        <button @click="activeTab = 'allergens'" 
                class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                :class="activeTab === 'allergens' ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
            <i class="fas fa-list mr-1.5"></i> По алерген
        </button>
    </div>

    <!-- ТАБ 1: По ученик -->
    <div x-show="activeTab === 'users'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        @if($usersWithAllergens->isEmpty())
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Няма намерени резултати</h3>
                <p class="text-gray-500 mt-1">Няма регистрирани алергии{{ $search ? ' за "' . $search . '"' : '' }}.</p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">
                                <th class="px-6 py-4 w-1/2">Ученик</th>
                                <th class="px-6 py-4">Алергени</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($usersWithAllergens as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($user->image)
                                            <img src="{{ asset($user->image) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                                                {{ mb_substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-blue-600 transition-colors">
                                                {{ $user->name }}
                                            </a>
                                            @if($user->klas)
                                                <div class="text-xs font-medium text-gray-500 mt-0.5">
                                                    <i class="fas fa-chalkboard mr-1 text-gray-400"></i>{{ $user->klas->title }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->allergens as $allergen)
                                            <!-- Custom Tooltip (Pure CSS/Tailwind) -->
                                            <div class="relative group cursor-help">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold text-white shadow-sm"
                                                      style="background-color: {{ $allergen->color }};">
                                                    {{ $allergen->name }}
                                                    @if($allergen->pivot->notes)
                                                        <i class="fas fa-info-circle opacity-80"></i>
                                                    @endif
                                                </span>
                                                <!-- Tooltip текст -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-xs p-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10 shadow-xl">
                                                    <div class="font-bold">{{ $allergen->description ?: 'Няма описание' }}</div>
                                                    @if($allergen->pivot->notes)
                                                        <div class="mt-1 pt-1 border-t border-gray-700 text-gray-300 italic">
                                                            Бележка: {{ $allergen->pivot->notes }}
                                                        </div>
                                                    @endif
                                                    <!-- Малко триъгълниче отдолу -->
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- ТАБ 2: По алерген -->
    <div x-show="activeTab === 'allergens'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        @if($allergens->isEmpty())
             <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Няма данни</h3>
                <p class="text-gray-500 mt-1">В системата няма въведени алергени.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allergens as $allergen)
                <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden relative flex flex-col h-full shadow-sm hover:shadow-md transition-shadow">
                    
                    <!-- Цветна линия отгоре -->
                    <div class="absolute top-0 left-0 right-0 h-1.5" style="background-color: {{ $allergen->color }};"></div>
                    
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/20 mt-1.5">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $allergen->color }};"></span>
                            <strong class="text-gray-900 dark:text-white font-black tracking-tight">{{ $allergen->name }}</strong>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg text-white shadow-sm" style="background-color: {{ $allergen->color }};">
                            {{ $allergen->users->count() }}
                        </span>
                    </div>

                    @if($allergen->description)
                        <div class="px-5 py-3 bg-white dark:bg-gray-900 border-b border-gray-50 dark:border-gray-800">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $allergen->description }}</p>
                        </div>
                    @endif

                    <div class="flex-1 overflow-y-auto p-0">
                        <ul class="divide-y divide-gray-50 dark:divide-gray-800">
                            @foreach($allergen->users as $user)
                            <li class="p-4 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <div class="flex items-start gap-3">
                                    @if($user->image)
                                        <img src="{{ asset($user->image) }}" class="w-8 h-8 rounded-full object-cover mt-0.5">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 flex items-center justify-center font-bold text-xs mt-0.5">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-blue-600 truncate block">
                                            {{ $user->name }}
                                        </a>
                                        @if($user->klas)
                                            <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mt-0.5">{{ $user->klas->title }}</span>
                                        @endif
                                        
                                        @if($user->pivot->notes)
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-100 dark:border-yellow-900/30 rounded-lg p-2.5 flex items-start gap-2">
                                                <i class="fas fa-sticky-note text-yellow-500 mt-0.5"></i>
                                                <span class="italic">{{ $user->pivot->notes }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection