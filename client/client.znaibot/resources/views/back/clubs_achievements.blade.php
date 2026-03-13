@extends('main')
@section('body_class', 'scroll-mode')
@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative overflow-hidden">
    
    <!-- Декоративен фонов елемент за "джаджано" излъчване -->
    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-3xl"></div>

    <a href="{{ route('clubs') }}" class="absolute top-8 left-8 z-20 inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 shadow-sm transition-all group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-amber-500"></i> Назад
    </a>

    <div class="max-w-4xl mx-auto mb-16 pt-12 md:pt-0">
        @foreach($clubs as $club)
        <div class="text-center md:text-left mb-12">
            <div class="flex items-center justify-center md:justify-start space-x-4 mb-4 animate-fade-in">
                <span class="px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full text-xs font-bold uppercase tracking-widest border border-amber-100">Зала на славата</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight text-[#1D1D1F] animate-fade-in">
                Успехи на клуб <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">{{ $club->name }}</span>
            </h1>
        </div>

        <div class="space-y-6 relative">
            <!-- Вертикална линия -->
            <div class="absolute left-6 md:left-8 top-0 bottom-0 w-px bg-gradient-to-b from-amber-200 via-gray-100 to-transparent"></div>

            @php
                // Разделяме текста по точка, нов ред или параграф
                $achievements = preg_split('/(?<=[.!?])\s+|(<p>|<\/p>|<br\s*\/?>)/', $club->achievements, -1, PREG_SPLIT_NO_EMPTY);
            @endphp

            @foreach($achievements as $index => $achievement)
                @php 
                    $achievement = trim(strip_tags($achievement)); 
                @endphp
                @if(strlen($achievement) > 3)
                <div class="relative pl-16 md:pl-20 group animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                    <!-- Точка на таймлайна -->
                    <div class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-white border-4 border-amber-500 z-10 group-hover:scale-125 transition-transform duration-300 shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                    
                    <!-- Карта на постижението -->
                    <div class="bg-white border border-gray-100 p-6 rounded-[2rem] shadow-sm group-hover:shadow-md group-hover:border-amber-100 transition-all duration-300 flex items-center justify-between overflow-hidden relative">
                        <!-- Декоративна икона на заден план -->
                        <i class="fas fa-award absolute -right-4 -bottom-4 text-gray-50 text-6xl group-hover:text-amber-50/50 transition-colors"></i>

                        <div class="relative z-10 flex items-center space-x-5">
                            <div class="w-12 h-12 shrink-0 bg-amber-50 rounded-2xl flex items-center justify-center border border-amber-100 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                                <i class="fas fa-trophy text-amber-500 group-hover:text-white"></i>
                            </div>
                            <div>
                                <p class="text-gray-700 font-medium text-lg leading-tight">{{ $achievement }}</p>
                                <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">Официално отличие</span>
                            </div>
                        </div>

                        <div class="hidden md:block opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right text-amber-200"></i>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        @endforeach
    </div>
</div>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fade-in-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.8s ease-out forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
</style>
@endsection