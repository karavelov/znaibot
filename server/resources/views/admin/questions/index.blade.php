@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Въпроси</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на въпросите за викторината (1-12 клас)</p>
        </div>
        <a href="{{ route('admin.questions.create') }}"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Добави нов въпрос
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="table-responsive custom-datatable">
                {{ $dataTable->table(['class' => 'table w-full border-separate border-spacing-y-2']) }}
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

@push('styles')
<style>
    /* Лека корекция за DataTables, за да съвпадне с модерния дизайн */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: white !important;
        border-radius: 12px !important;
        border: none !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 12px !important;
        border: 1px solid #f3f4f6 !important;
        padding: 6px 12px !important;
        margin-bottom: 15px !important;
    }
    .dark .dataTables_wrapper .dataTables_filter input {
        background: #1f2937 !important;
        border-color: #374151 !important;
        color: white !important;
    }
</style>
@endpush