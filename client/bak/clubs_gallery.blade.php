@extends('main')
@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    
    <!-- Навигация -->
    <a href="{{ route('clubs') }}" class="absolute top-8 left-8 z-20 inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 shadow-sm transition-all group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-blue-500"></i> Назад към клубовете
    </a>

    <div class="max-w-6xl mx-auto mb-16 pt-12 md:pt-0">
        <div class="flex items-center space-x-4 mb-4 animate-fade-in">
            <span class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest">Галерия</span>
            {{-- Предполагаме, че $gallery е един обект, а не колекция --}}
            <h2 class="text-gray-400 font-light tracking-wide">{{ $gallery->title }}</h2>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F] animate-fade-in">
            Галерия на <span class="text-blue-500">клуба</span>
        </h1>
    </div>

    <!-- Решетка със снимки -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
        
        {{-- Коригиран цикъл - премахнат е вложеният foreach --}}
        @foreach($galleriesimages as $image)
        <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 animate-fade-in-up">
            <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                {{-- Коригиран път до снимката с fallback --}}
                <img src="https://znaibot.karavelov.com/{{ $image->image }}" 
                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Image&background=f3f4f6&color=9ca3af&size=500';"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                     alt="Снимка от галерия {{ $gallery->title }}">
            </div>
            <!-- Overlay информация -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                {{-- Коригирано форматиране на датата --}}
                <span class="text-blue-400 text-xs font-bold mb-2 uppercase tracking-widest">{{ \Carbon\Carbon::parse($image->created_at)->format('d.m.Y') }}</span>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection