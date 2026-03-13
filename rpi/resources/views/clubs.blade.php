@extends('main')
@section('body_class', 'scroll-mode')
@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12">
    
    <div class="max-w-6xl mx-auto">
        <!-- Бутон Начало - вече е част от потока на документа -->
        <div class="mb-8 animate-fade-in">
            <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group">
                <i class="fas fa-arrow-left mr-2 text-blue-500 group-hover:scale-110 transition-transform"></i> Начало
            </a>
        </div>

        <!-- Заглавие -->
        <div class="mb-16">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F] animate-fade-in">
                Клубове <span class="text-blue-500">.</span>
            </h1>
            <p class="text-gray-400 mt-3 text-lg font-light tracking-wide animate-fade-in" style="animation-delay: 0.1s">
                Разгледай дейностите и постиженията на нашите екипи
            </p>
        </div>

        <!-- Решетка -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- КЛУБ 1: Роботика -->
            @foreach($clubs as $club)
            @php
                $galleryTargetId = data_get($club, 'gallery_id') ?? data_get($club, 'id');
            @endphp
            <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-2 flex flex-col justify-between animate-fade-in-up" style="animation-delay: 0.2s">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-robot text-xl text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 tracking-tight mb-3">{{ $club->name }}</h3>
                    <p class="text-gray-500 leading-relaxed text-sm mb-6 font-light italic">
    {{ strip_tags($club->about) }}
</p>
                    
<div class="space-y-3 mb-8">
    <!-- <div class="flex items-center p-3 bg-gray-50 rounded-2xl border border-gray-50">
        <i class="fas fa-chalkboard-teacher text-xs text-gray-400 mr-3"></i>
        <span class="text-sm font-semibold text-gray-700">asdadasdasdasdsadad</span>
    </div> -->
    <div class="flex items-center p-3 bg-gray-50 rounded-2xl border border-gray-50">
        <i class="fas fa-user-graduate text-xs text-gray-400 mr-3"></i>
        <span class="text-sm font-semibold text-gray-700">{{ $club->members }} ученици</span>
    </div>
</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if(!empty($galleryTargetId))
                    <a href="{{ route('clubs_gallery', ['id' => $galleryTargetId]) }}" class="flex items-center justify-center py-3 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100 transition-all">
                        <i class="fas fa-images mr-2"></i> Галерия
                    </a>
                    @else
                    <span class="flex items-center justify-center py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-400 cursor-not-allowed">
                        <i class="fas fa-images mr-2"></i> Галерия
                    </span>
                    @endif
<a href="{{ route('clubs_achievements', ['id' => $club->id]) }}" class="flex items-center justify-center py-3 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-100 transition-all">
    <i class="fas fa-trophy mr-2"></i> Успехи
</a>
                </div>
            </div>
            @endforeach

            

        </div>
    </div>
</div>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fade-in-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.8s ease-out forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
</style>
@endsection 