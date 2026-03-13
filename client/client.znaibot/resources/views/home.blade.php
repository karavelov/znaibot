
@extends('main')
@section('content')
<div class="min-h-screen bg-[#FBFBFD] text-[#1D1D1F] flex flex-col items-center justify-center p-6">
<div class="text-center mb-12 animate-fade-in">
    <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-4">
        Знай <span class="text-blue-500">Бот</span>
    </h1>
    <p class="text-xl text-gray-500 font-light tracking-wide">Как мога да ви помогна днес?</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-5xl">

    <a href="{{ route('clubs') }}" class="group relative overflow-hidden bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-16 group-hover:scale-110 transition-transform duration-500">
            <div class="space-y-1">
                <div class="w-6 h-1 bg-emerald-400 rounded-full"></div>
                <div class="w-4 h-1 bg-emerald-300 rounded-full"></div>
            </div>
        </div>
        <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Клубове</h2>
        <p class="text-gray-400 mt-2 text-sm">Разгледайте клубовете по интереси</p>
    </a>

    <a href="{{ route('history') }}" class="group relative overflow-hidden bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center mb-16 group-hover:scale-110 transition-transform duration-500">
            <div class="flex items-end space-x-1">
                <div class="w-1 h-4 bg-amber-400 rounded-full"></div>
                <div class="w-1 h-6 bg-amber-300 rounded-full"></div>
                <div class="w-1 h-3 bg-amber-200 rounded-full"></div>
            </div>
        </div>
        <h2 class="text-2xl font-semibold tracking-tight text-gray-800">История</h2>
        <p class="text-gray-400 mt-2 text-sm">Научете историята на училището</p>
    </a>

    <a href="{{ route('news') }}" class="group relative overflow-hidden bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center mb-16 group-hover:scale-110 transition-transform duration-500">
            <div class="relative">
                <div class="w-6 h-6 border-4 border-rose-300 rounded-full"></div>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full"></div>
            </div>
        </div>
        <h2 class="text-2xl font-semibold tracking-tight text-gray-800">Новини</h2>
        <p class="text-gray-400 mt-2 text-sm">Вижте последните новини</p>
    </a>

    <a href="{{ route('scan') }}" class="group relative overflow-hidden bg-blue-600 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-200 hover:-translate-y-1">
        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-blue-500 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
        
        <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center mb-16 relative z-10">
            <div class="grid grid-cols-2 gap-1">
                <div class="w-2 h-2 bg-white rounded-sm"></div>
                <div class="w-2 h-2 bg-white/40 rounded-sm"></div>
                <div class="w-2 h-2 bg-white/40 rounded-sm"></div>
                <div class="w-2 h-2 bg-white rounded-sm"></div>
            </div>
        </div>
        <h2 class="text-2xl font-semibold tracking-tight text-white relative z-10">Вход</h2>
        <p class="text-blue-100 mt-2 text-sm relative z-10">Вход с потребителско име/имейл и парола</p>
    </a>

</div>

<div class="mt-16 opacity-30 group">
    <p class="text-sm font-medium tracking-[0.3em] uppercase">ЗнайБот | Училищен асистент</p>
</div>
</div>
@endsection
@push('styles')
<style>
@keyframes fade-in {
from { opacity: 0; transform: translateY(10px); }
to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
animation: fade-in 0.8s ease-out forwards;
}
</style>
@endpush