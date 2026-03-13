@extends('main')
@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    
    <div class="max-w-7xl mx-auto mb-16 pt-12 md:pt-0">
        <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group" style="margin-bottom: 20px">
            <i class="fas fa-arrow-left mr-2 text-blue-500 group-hover:scale-110 transition-transform"></i> Начало
        </a>
        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-[#1D1D1F] animate-fade-in">
            Новини <span class="text-blue-500">.</span>
        </h1>
        <p class="text-gray-400 mt-4 text-xl font-light tracking-wide">Всичко интересно от живота в нашето училище</p>
    </div>

    <div class="max-w-7xl mx-auto">
        @if($featured)
        <a href="{{ route('newsarticle', ['slug' => $featured->slug]) }}" class="group relative block mb-16 animate-fade-in-up">
            <div class="relative h-[400px] md:h-[500px] rounded-[3rem] overflow-hidden shadow-2xl bg-gray-200">
                {{-- Снимката от външния проект --}}
                <img src="https://znaibot.karavelov.com/{{ $featured->image }}" 
                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=News&background=e5e7eb&color=9ca3af&size=800';" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" 
                     alt="{{ $featured->title }}">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8 md:p-16">
                    <span class="px-4 py-1.5 bg-blue-600 text-white rounded-full text-xs font-bold uppercase tracking-widest w-fit mb-6">Важно</span>
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight max-w-4xl">
                        {{ $featured->title }}
                    </h2>
                    <div class="text-gray-300 text-lg font-light max-w-2xl mb-6 line-clamp-2">
                        {{ strip_tags($featured->description) }}
                    </div>
                    <div class="flex items-center text-white/60 text-sm">
                        <span class="mr-4"><i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($featured->created_at)->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </a>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($news as $index => $item)
            <a href="{{ route('newsarticle', ['slug' => $item->slug]) }}" class="group animate-fade-in-up" style="animation-delay: {{ ($index % 3) * 0.1 }}s">
                <div class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                    <div class="h-60 overflow-hidden relative bg-gray-100">
                        {{-- Снимката от външния проект --}}
                        <img src="https://znaibot.karavelov.com/{{ $item->image }}" 
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Article&background=f3f4f6&color=9ca3af&size=400';"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                             alt="{{ $item->title }}">
                    </div>
                    <div class="p-8">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</span>
                        <h3 class="text-xl font-bold text-gray-800 mt-3 mb-4 group-hover:text-blue-600 transition-colors">{{ $item->title }}</h3>
                        <div class="text-gray-500 text-sm font-light leading-relaxed line-clamp-2">{{ strip_tags($item->description) }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection