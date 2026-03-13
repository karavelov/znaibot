@extends('admin.layouts.master')

@section('content')
<!-- Главен контейнер с плавна анимация при зареждане -->
<div class="p-6 sm:p-10 space-y-10" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    
    <!-- Приветствен Хедър -->
    <div class="flex flex-col gap-1 transition-all duration-700 transform" :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
            Здравей, {{ auth()->user()->name }}! 👋
        </h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Ето какво се случва в твоя административен панел днес.</p>
    </div>

    <!-- Решетка със статистики (Grid) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Карта: Публикации -->
        <a href="{{ route('admin.blog.index') }}" 
           class="group relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden"
           :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-95'" style="transition-delay: 100ms;">
            
            <div class="flex items-center gap-5">
                <!-- Икона в iOS стил (Синя) -->
                <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl transition-transform group-hover:scale-110 duration-300">
                    <i class="far fa-file-alt"></i>
                </div>
                
                <div class="flex flex-col">
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Публикации</span>
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none mt-1">{{$totalBlogs}}</span>
                </div>
            </div>

            <!-- Малък декоративен елемент в ъгъла -->
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50/50 dark:bg-blue-900/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </a>

        <!-- Карта: Абонати -->


        <!-- Карта: Администратори -->
        <a href="{{ route('admin.users.index') }}" 
           class="group relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden"
           :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-95'" style="transition-delay: 300ms;">
            
            <div class="flex items-center gap-5">
                <!-- Икона в iOS стил (Индиго) -->
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl transition-transform group-hover:scale-110 duration-300">
                    <i class="fas fa-user-shield"></i>
                </div>
                
                <div class="flex flex-col">
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Администратори</span>
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none mt-1">{{$totalUsers}}</span>
                </div>
            </div>

            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </a>

    </div>

    <!-- Секция за бързи действия (Пример за разширяване на Dashboard-а) -->
    <div class="pt-6 border-t border-gray-100 dark:border-gray-800 transition-all duration-1000 delay-500" :class="loaded ? 'opacity-100' : 'opacity-0'">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 px-2">Бързи действия</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.blog.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                Нова публикация
            </a>
            <a href="{{ route('admin.allergens.create') }}" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95">
                Добави алерген
            </a>
        </div>
    </div>

</div>
@endsection