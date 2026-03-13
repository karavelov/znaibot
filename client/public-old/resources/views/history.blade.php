@extends('main')

@section('content')
<div class="relative pt-12">
    <!-- Бутон Начало -->
    <a href="{{ route('welcome') }}" class="absolute top-0 left-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition shadow-sm">
        <i class="fas fa-arrow-left mr-2"></i> Начало
    </a>

    <h1 class="text-3xl font-bold mb-4 text-gray-800">История на училището</h1>
    <div class="bg-white p-6 rounded shadow-lg border-t-4 border-blue-500">
        <p class="text-gray-700 leading-relaxed">
            Нашето училище е основано през 1950 година и вече десетилетия наред подготвя успешни млади хора...
        </p>
    </div>
</div>
@endsection