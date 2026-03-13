@extends('main')
@section('body_class', 'scroll-mode')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <a href="{{ route('student_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group mb-6 w-fit">
            <i class="fas fa-arrow-left mr-2 text-blue-500 group-hover:scale-110 transition-transform"></i> Назад
        </a>

        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 md:p-8 shadow-sm">
            <div class="mb-5 md:mb-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-500">Робо Игри</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight mt-1">Камък Ножица Хартия</h1>
                    <p class="text-sm text-gray-500 mt-2">Live видео поток от AI детекцията.</p>
                </div>
                <span class="hidden md:inline-flex items-center bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-[0.14em] px-3 py-2 rounded-xl">AI Stream</span>
            </div>

            <div class="rounded-[1.5rem] overflow-hidden border border-gray-100 bg-[#121212] p-2 md:p-3">
                <div style="width: 100%; text-align: center; background: #121212;">
                <iframe 
                    src="https://adrianne-clandestine-maryellen.ngrok-free.dev/?token=znaibot_2026_parolata" 
                    style="width: 100%; aspect-ratio: 16 / 9; border: none; border-radius: 15px;"
                    allow="camera">
                </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
