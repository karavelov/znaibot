@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редактиране на партньор</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $brand->name }}</p>
        </div>
        <a href="{{ route('admin.brand.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8 max-w-xl">
        <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                <!-- Текущо лого -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Текущ изглед</label>
                    <div class="w-24 h-24 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Ново изображение</label>
                    <input type="file" name="image"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Име</label>
                    <input type="text" name="name" value="{{ $brand->name }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Изложено</label>
                    <select name="is_featured"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                        <option {{ $brand->is_featured == 1 ? 'selected' : '' }} value="1">Да</option>
                        <option {{ $brand->is_featured == 0 ? 'selected' : '' }} value="0">Не</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Статус</label>
                    <select name="status"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option {{ $brand->status == 1 ? 'selected' : '' }} value="1">Публично</option>
                        <option {{ $brand->status == 0 ? 'selected' : '' }} value="0">Скрито</option>
                    </select>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Запази
                </button>
                <a href="{{ route('admin.brand.index') }}" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Откажи
                </a>
            </div>

        </form>
    </div>

</div>
@endsection