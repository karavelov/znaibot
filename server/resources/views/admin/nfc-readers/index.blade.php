@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">NFC Четци</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на всички NFC четци</p>
        </div>
        <a href="{{ route('admin.nfc-readers.create') }}"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Добави четец
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            {{ $dataTable->table() }}
        </div>
    </div>

</div>

{{-- Confirm Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-bold">Потвърждение за изтриване</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-amber-500"></i>
                </div>
                <h5 class="mb-1 font-bold">Сигурни ли сте?</h5>
                <p class="text-gray-400 mb-0">Ще изтриете четец: <strong id="deleteReaderTitle"></strong></p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-3">
                <button type="button" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all" data-dismiss="modal">Отказ</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-red-500/20 transition-all active:scale-95">Изтрий</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function () {
            $('body').on('click', '.btn-delete-reader', function () {
                var title = $(this).data('title');
                var url   = $(this).data('url');
                $('#deleteReaderTitle').text(title);
                $('#deleteForm').attr('action', url);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
