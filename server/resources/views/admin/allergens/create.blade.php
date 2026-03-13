@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.allergens.index') }}"
           class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-500 hover:text-blue-600 hover:border-blue-400 transition-all shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Нов алерген</h1>
            <p class="text-gray-400 text-sm font-medium mt-0.5">Добавяне на нов вид алерген</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="max-w-xl">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
            <form action="{{ route('admin.allergens.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-1.5">
                        Наименование <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}"
                           placeholder="Пример: Глутен"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border @error('name') border-red-400 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-1.5">Описание</label>
                    <input type="text" name="description"
                           value="{{ old('description') }}"
                           placeholder="Пример: Пшеница, ечемик, ръж и техни производни"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border @error('description') border-red-400 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-1.5">
                        Цвят на badge <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color"
                               value="{{ old('color', '#dc3545') }}"
                               class="w-14 h-10 rounded-xl border @error('color') border-red-400 @else border-gray-200 dark:border-gray-700 @enderror cursor-pointer p-1 bg-white dark:bg-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Избери цвят за маркиране на алергена</span>
                    </div>
                    @error('color')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                        Запази
                    </button>
                    <a href="{{ route('admin.allergens.index') }}"
                       class="px-6 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all active:scale-95">
                        Назад
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
