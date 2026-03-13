@extends('main')

@section('content')
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-green-500"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700">
                Успешно идентифициран ученик: <strong>Иван Иванов (10б клас)</strong>
            </p>
        </div>
    </div>
</div>

<h2 class="text-3xl font-bold mb-6">Здравей, Иване! Какво търсиш днес?</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Опция 1 -->
<!-- Опция 1: Задай въпрос -->
<a href="{{ route('ai.chat') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition cursor-pointer border-t-4 border-blue-500 group block">
    <div class="text-blue-500 text-4xl mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-question-circle"></i></div>
    <h3 class="text-xl font-bold mb-2">Задай въпрос</h3>
    <p class="text-gray-600 text-sm">Имаш въпрос към администрацията или учител?</p>
</a>

    <!-- Опция 2 -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition cursor-pointer border-t-4 border-yellow-500 group">
        <div class="text-yellow-500 text-4xl mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-brain"></i></div>
        <h3 class="text-xl font-bold mb-2">Викторина на деня</h3>
        <p class="text-gray-600 text-sm">Провери знанията си и спечели точки.</p>
    </div>

    <!-- Опция 3 -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition cursor-pointer border-t-4 border-red-500 group">
        <div class="text-red-500 text-4xl mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-chalkboard-teacher"></i></div>
        <h3 class="text-xl font-bold mb-2">Намерете учител</h3>
        <p class="text-gray-600 text-sm">Виж графика и кабинета на преподавател.</p>
    </div>

    <!-- Опция 4 -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition cursor-pointer border-t-4 border-purple-500 group">
        <div class="text-purple-500 text-4xl mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-box-open"></i></div>
        <h3 class="text-xl font-bold mb-2">Изгубени вещи</h3>
        <p class="text-gray-600 text-sm">Провери за забравени вещи в училището.</p>
    </div>
</div>
@endsection