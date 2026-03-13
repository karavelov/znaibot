@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Нов клуб</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Попълнете данните за новия клуб</p>
        </div>
        <a href="{{ route('admin.clubs.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
        <form action="{{ route('admin.clubs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Иконка -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Иконка / Лого на клуба</label>
                    <input type="file" name="icon" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white transition-all file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                </div>

                <!-- Име -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Име на клуб <span class="text-red-400">(*)</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Брой членове -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Брой членове</label>
                    <input type="text" name="members" value="{{ old('members') }}" placeholder="напр. 25"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- Галерия -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Галерия</label>
                    <select name="gallery_id"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">— Изберете галерия —</option>
                        @foreach ($galleries as $gallery)
                            <option value="{{ $gallery->id }}">{{ $gallery->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Статус -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Статус</label>
                    <select name="status"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="1">Публично</option>
                        <option value="0">Скрито</option>
                    </select>
                </div>

                <!-- За клуба -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">За клуба</label>
                    <textarea name="about" class="summernote w-full">{{ old('about') }}</textarea>
                </div>

                <!-- Успехи -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Успехи</label>
                    <textarea name="achievements" class="summernote w-full">{{ old('achievements') }}</textarea>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Запази
                </button>
                <a href="{{ route('admin.clubs.index') }}"
                   class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Отказ
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
