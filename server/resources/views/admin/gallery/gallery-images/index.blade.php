@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Галерия: {{ $gallery->title }}</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Преглед и управление на изображенията</p>
        </div>
        <a href="{{ route('admin.gallery.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-100 text-red-600 text-sm font-medium rounded-2xl px-5 py-4">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Upload Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5">Качване на изображения</h2>
        <form action="{{ route('admin.gallery-images.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Изображение
                        <span class="ml-2 text-[10px] font-semibold text-blue-500 normal-case tracking-normal">Може повече от едно</span>
                    </label>
                    <input type="file" name="image[]" multiple
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all">
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Запази
                </button>
            </div>
        </form>
    </div>

    <!-- Images Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="px-8 pt-6 pb-2">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400">Всички изображения</h2>
        </div>
        <div class="p-6 sm:p-8">
            {{ $dataTable->table() }}
        </div>
    </div>

</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush