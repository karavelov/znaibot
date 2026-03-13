@extends('main')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] relative">

    <!-- Заглавие с дизайна от снимката -->
    <div class="text-center mb-16">
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-800 tracking-tight">
            ZnaiBot <span class="text-gray-400">System</span>
        </h1>
        <div class="w-24 h-1 bg-gray-300 mx-auto mt-6 rounded-full"></div>
        <p class="mt-4 text-gray-500 font-medium">Изберете категория</p>
    </div>

    <!-- РЕШЕТКА С БУТОНИТЕ -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-16 max-w-6xl mx-auto px-4">

        <!-- 1. КЛУБОВЕ (Стил: Hamburger / Списък) -->
        <a href="{{ route('clubs') }}" class="group flex flex-col items-center cursor-pointer">
            <div class="w-32 h-32 bg-white rounded-full shadow-lg shadow-gray-200/60 flex flex-col justify-center items-center space-y-2 transition-all duration-300 transform group-hover:-translate-y-3 group-hover:shadow-2xl border border-gray-100">
                <!-- Геометрична икона: Списък -->
                <div class="w-14 h-1.5 bg-emerald-400 rounded-full group-hover:bg-emerald-500 transition"></div>
                <div class="w-14 h-1.5 bg-amber-400 rounded-full group-hover:bg-amber-500 transition"></div>
                <div class="w-14 h-1.5 bg-rose-500 rounded-full group-hover:bg-rose-600 transition"></div>
            </div>
            <span class="mt-6 text-lg font-bold text-gray-600 tracking-wide group-hover:text-gray-900 transition-colors">
                Клубове
            </span>
        </a>

        <!-- 2. ИСТОРИЯ (Стил: Stairs / Хронология) -->
        <a href="{{ route('history') }}" class="group flex flex-col items-center cursor-pointer">
            <div class="w-32 h-32 bg-white rounded-full shadow-lg shadow-gray-200/60 flex flex-col justify-center items-start pl-8 space-y-2 transition-all duration-300 transform group-hover:-translate-y-3 group-hover:shadow-2xl border border-gray-100">
                <!-- Геометрична икона: Стълби -->
                <div class="w-8 h-1.5 bg-emerald-400 rounded-full ml-6 group-hover:bg-emerald-500 transition"></div>
                <div class="w-12 h-1.5 bg-amber-400 rounded-full ml-3 group-hover:bg-amber-500 transition"></div>
                <div class="w-16 h-1.5 bg-rose-500 rounded-full group-hover:bg-rose-600 transition"></div>
            </div>
            <span class="mt-6 text-lg font-bold text-gray-600 tracking-wide group-hover:text-gray-900 transition-colors">
                История
            </span>
        </a>

        <!-- 3. УСПЕХИ (Стил: Doner / Пиедестал) -->
        <a href="{{ route('achievements') }}" class="group flex flex-col items-center cursor-pointer">
            <div class="w-32 h-32 bg-white rounded-full shadow-lg shadow-gray-200/60 flex flex-col justify-center items-center space-y-2 transition-all duration-300 transform group-hover:-translate-y-3 group-hover:shadow-2xl border border-gray-100">
                <!-- Геометрична икона: Пирамида -->
                <div class="w-6 h-1.5 bg-emerald-400 rounded-full group-hover:bg-emerald-500 transition"></div>
                <div class="w-10 h-1.5 bg-amber-400 rounded-full group-hover:bg-amber-500 transition"></div>
                <div class="w-14 h-1.5 bg-rose-500 rounded-full group-hover:bg-rose-600 transition"></div>
            </div>
            <span class="mt-6 text-lg font-bold text-gray-600 tracking-wide group-hover:text-gray-900 transition-colors">
                Успехи
            </span>
        </a>

        <!-- 4. СКАНИРАЙ ЧИП (Стил: Bento / Чип матрица) -->
        <a href="{{ route('scan') }}" class="group flex flex-col items-center cursor-pointer">
            <div class="w-32 h-32 bg-white rounded-full shadow-lg shadow-gray-200/60 flex justify-center items-center transition-all duration-300 transform group-hover:-translate-y-3 group-hover:shadow-2xl border-4 border-transparent group-hover:border-blue-100">
                <!-- Геометрична икона: Решетка (Bento Box) -->
                <div class="grid grid-cols-3 gap-1.5">
                    <div class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse"></div>
                    <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                    <div class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse"></div>
                    
                    <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                    <div class="w-2.5 h-2.5 bg-amber-400 rounded-full"></div>
                    <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                    
                    <div class="w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse"></div>
                    <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                    <div class="w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse"></div>
                </div>
            </div>
            <span class="mt-6 text-lg font-bold text-blue-600 tracking-wide group-hover:text-blue-800 transition-colors">
                Сканирай ЧИП
            </span>
        </a>

    </div>
</div>
@endsection