@extends('main')

@section('content')
<div class="relative">
    <!-- Бутон Начало -->
    <a href="{{ route('welcome') }}" class="absolute top-0 left-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition shadow-sm">
        <i class="fas fa-arrow-left mr-2"></i> Начало
    </a>

    <div class="flex flex-col items-center justify-center min-h-[60vh] pt-12">
        
        <!-- Анимация за чакане -->
        <div class="relative flex justify-center items-center mb-10">
            <div class="absolute animate-ping inline-flex h-32 w-32 rounded-full bg-blue-400 opacity-75"></div>
            <div class="relative inline-flex rounded-full h-24 w-24 bg-blue-600 items-center justify-center">
                <i class="fas fa-wifi text-4xl text-white"></i>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-gray-700 animate-pulse">Очакване на ЧИП...</h2>
        <p class="text-gray-500 mt-2">Моля, доближете вашата карта до четеца.</p>

        <!-- Скрити бутони за симулация -->
        <div class="mt-16 bg-gray-200 p-6 rounded-xl border border-gray-300">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-4 text-center">--- Developer Simulation Panel ---</p>
            <div class="flex space-x-4">
                <a href="{{ route('student') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-lg font-bold transition">
                    <i class="fas fa-user-graduate mr-2"></i> Сканиран: УЧЕНИК
                </a>
                
                <a href="{{ route('parent') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 shadow-lg font-bold transition">
                    <i class="fas fa-user-friends mr-2"></i> Сканиран: РОДИТЕЛ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection