@extends('main')

@section('content')
<div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-8">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-id-badge text-purple-500"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-purple-700">
                Успешно идентифициран родител: <strong>Мария Иванова</strong>
            </p>
        </div>
    </div>
</div>

<h2 class="text-3xl font-bold mb-6">Родителски Панел</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Опция 1 -->
    <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition cursor-pointer flex flex-col items-center text-center">
        <div class="bg-blue-100 p-4 rounded-full mb-4">
            <i class="fas fa-map-marked-alt text-3xl text-blue-600"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Класната стая на детето</h3>
        <p class="text-gray-600">Навигация до текущата стая на Иван Иванов.</p>
        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Покажи карта</button>
    </div>

    <!-- Опция 2 -->
    <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition cursor-pointer flex flex-col items-center text-center">
        <div class="bg-green-100 p-4 rounded-full mb-4">
            <i class="fas fa-calendar-alt text-3xl text-green-600"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Събития</h3>
        <p class="text-gray-600">Родителски срещи и училищни празници.</p>
        <button class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Виж календар</button>
    </div>

    <!-- Опция 3 -->
    <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition cursor-pointer flex flex-col items-center text-center">
        <div class="bg-orange-100 p-4 rounded-full mb-4">
            <i class="fas fa-directions text-3xl text-orange-600"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Упътване</h3>
        <p class="text-gray-600">Помощ при ориентация в сградата.</p>
        <button class="mt-4 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Старт навигация</button>
    </div>
</div>
@endsection