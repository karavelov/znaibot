@extends('main')

@section('content')
<div class="relative pt-12"> <!-- Добавяме padding-top (pt-12), за да не застъпи бутона заглавието -->
    <!-- Бутон Начало -->
    <a href="{{ route('welcome') }}" class="absolute top-0 left-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition shadow-sm">
        <i class="fas fa-arrow-left mr-2"></i> Начало
    </a>

    <h2 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-2">Клубове по интереси</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($clubs as $club)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-blue-500">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-blue-800 mb-2">{{ $club->clubname }}</h3>
                <p class="text-gray-600 mb-4 text-sm">{{ $club->description }}</p>
                
                <div class="flex items-center mb-2">
                    <i class="fas fa-chalkboard-teacher w-6 text-gray-400"></i>
                    <span class="font-semibold mr-2">Ментор:</span> {{ $club->mentor }}
                </div>
                
                <div class="flex items-center">
                    <i class="fas fa-user-graduate w-6 text-gray-400"></i>
                    <span class="font-semibold mr-2">Участници:</span> {{ $club->participants }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection