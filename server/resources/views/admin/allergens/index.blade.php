@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white flex items-center gap-3">
                <i class="fas fa-heartbeat text-red-500"></i> Алергени
            </h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на видовете алергени</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.allergens.dashboard') }}"
               class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 rounded-2xl text-sm font-bold hover:border-blue-400 hover:text-blue-600 transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                <i class="fas fa-heartbeat text-xs"></i> Dashboard
            </a>
            <a href="{{ route('admin.allergens.create') }}"
               class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i> Добави алерген
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            {{ $dataTable->table() }}
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" id="deleteModalBackdrop"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-[2rem] shadow-2xl p-8 w-full max-w-sm text-center">
            <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-trash-alt text-2xl text-red-400"></i>
            </div>
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Изтриване на алерген</h3>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-2">
                Ще изтриете: <strong id="deleteAllergenName" class="text-gray-700 dark:text-gray-200"></strong>
            </p>
            <p class="text-sm text-red-500 font-semibold mb-6 hidden" id="deleteAllergenWarning">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Свързан с <strong id="deleteAllergenCount"></strong> потребители!
            </p>
            <div class="flex items-center gap-3 justify-center">
                <button id="deleteModalCancelBtn"
                        class="px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all active:scale-95">
                    Отказ
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-2xl text-sm font-bold shadow-md shadow-red-500/20 transition-all active:scale-95">
                        <i class="fas fa-trash-alt mr-1.5 text-xs"></i> Изтрий
                    </button>
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
            function openDeleteModal()  { $('#deleteModal').removeClass('hidden').addClass('flex'); }
            function closeDeleteModal() { $('#deleteModal').removeClass('flex').addClass('hidden'); }

            $('body').on('click', '.btn-delete-allergen', function () {
                var name  = $(this).data('name');
                var count = parseInt($(this).data('count'));
                var url   = $(this).data('url');

                $('#deleteAllergenName').text(name);
                $('#deleteForm').attr('action', url);

                if (count > 0) {
                    $('#deleteAllergenCount').text(count);
                    $('#deleteAllergenWarning').removeClass('hidden');
                } else {
                    $('#deleteAllergenWarning').addClass('hidden');
                }

                openDeleteModal();
            });

            $('#deleteModalCancelBtn, #deleteModalBackdrop').on('click', function () {
                closeDeleteModal();
            });
        });
    </script>
@endpush
