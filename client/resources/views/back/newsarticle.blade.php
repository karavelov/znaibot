@extends('main')
@section('body_class', 'scroll-mode')
@section('content')
<div class="min-h-screen bg-white">
    
    <div class="relative h-[60vh] md:h-[70vh] w-full overflow-hidden bg-gray-200">
        {{-- КОРИГИРАНА СНИМКА С ПЪЛЕН URL И FALLBACK --}}
        <img src="https://znaibot.karavelov.com/{{ $article->image }}" 
             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($article->title) }}&background=e5e7eb&color=9ca3af&size=1200';"
             class="absolute inset-0 w-full h-full object-cover" 
             alt="{{ $article->title }}">
        
        <a href="{{ route('news') }}" class="absolute top-8 left-8 z-20 inline-flex items-center px-5 py-2.5 bg-white/80 backdrop-blur-md border border-white/50 text-gray-800 text-sm font-medium rounded-2xl hover:bg-white transition-all group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-blue-500"></i> Назад към новините
        </a>
    </div>

    <article class="max-w-4xl mx-auto px-6 -mt-32 relative z-10">
        <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-2xl shadow-black/5 border border-gray-100">
            
            <div class="flex items-center space-x-4 mb-8">
                <span class="text-gray-400 text-sm italic">{{ \Carbon\Carbon::parse($article->created_at)->format('d.m.Y') }}</span>
            </div>

            <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-[#1D1D1F] mb-10 leading-tight">
                {{ $article->title }}
            </h1>

            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-light space-y-6 text-lg">
                {{-- Тук е добре да се използва {!! $article->description !!}, за да се запази HTML форматирането от базата --}}
                {!! $article->description !!}
            </div>

            @if($article->youtube_key)
            <div class="mt-8 aspect-w-16 aspect-h-9">
                <iframe src="{{ $article->youtube_key }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-96 rounded-2xl"></iframe>
            </div>
            @endif
        </div>

        <div class="my-20 text-center">
            <p class="text-gray-400 mb-6">Искате ли да прочетете още?</p>
            <a href="{{ route('news') }}" class="inline-flex items-center px-8 py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-blue-600 transition-all shadow-xl shadow-blue-200/20">
                Виж всички новини <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </article>
</div>
@endsection