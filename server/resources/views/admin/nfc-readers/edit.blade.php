@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редакция на NFC четец</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $reader->title }}</p>
        </div>
        <a href="{{ route('admin.nfc-readers.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8 max-w-xl">
        <form action="{{ route('admin.nfc-readers.update', $reader->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Заглавие <span class="text-red-400">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $reader->title) }}"
                           placeholder="Пример: Вход към стая 101"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border @error('title') border-red-400 @else border-gray-100 dark:border-gray-700 @enderror rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Тип <span class="text-red-400">*</span>
                    </label>
                    <select name="type"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border @error('type') border-red-400 @else border-gray-100 dark:border-gray-700 @enderror rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        @foreach($typeLabels as $value => $label)
                            <option value="{{ $value }}" {{ old('type', $reader->type) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Обнови
                </button>
                <a href="{{ route('admin.nfc-readers.index') }}"
                   class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Откажи
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
