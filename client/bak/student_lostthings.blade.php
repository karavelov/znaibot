@extends('main')
@section('body_class', 'scroll-mode')
@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-8 mb-16">
        <div class="animate-fade-in">
            <a href="{{ route('student_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 transition-all duration-300 group shadow-sm mb-6 md:mb-4">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-rose-500"></i> Назад
            </a>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F]">
                Изгубени <span class="text-rose-500">вещи</span>
            </h1>
            <p class="text-gray-400 mt-2 text-lg font-light tracking-wide">Намери това, което си изгубил</p>
        </div>

    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
        
        @forelse($items as $index => $item)
        @php
            $imagePath = data_get($item, 'image')
                ?? data_get($item, 'image_url')
                ?? data_get($item, 'photo')
                ?? data_get($item, 'picture')
                ?? 'assets/lostthing-placeholder.svg';
            $itemTitle = data_get($item, 'title') ?? 'Намерена вещ';
            $itemDescription = data_get($item, 'description') ?? 'Няма описание.';
        @endphp
        <div class="group bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ ($index % 4) * 100 }}ms;">
            <div class="relative h-64 overflow-hidden bg-gray-50">
                <img src="{{ asset($imagePath) }}" 
                    alt="{{ $itemTitle }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute top-4 left-4">
                    <span class="bg-white/90 backdrop-blur-md text-rose-600 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">Намерено</span>
                </div>
            </div>
            
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">{{ $itemTitle }}</h3>
                </div>
                <p class="text-gray-400 text-sm font-light leading-relaxed mb-6 line-clamp-2">
                    {{ $itemDescription }}
                </p>
                
                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <div class="flex items-center text-gray-400 text-xs">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}</span>
                    </div>
                    <button class="text-rose-500 font-bold text-sm hover:underline">Това е мое <i class="fas fa-chevron-right ml-1"></i></button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-400 py-12">
            Няма намерени вещи в момента.
        </div>
        @endforelse

    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fade-in-right {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 1s ease-out forwards; }
    .animate-fade-in-right { animation: fade-in-right 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endsection