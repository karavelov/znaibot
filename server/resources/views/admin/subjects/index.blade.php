@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Предмети</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Назначения предмет → учител</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Добави
        </a>
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
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Изтриване на предмет</h3>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-6" id="deleteModalBody"></p>
            <div class="flex items-center gap-3 justify-center">
                <button id="deleteModalCancelBtn"
                        class="px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all active:scale-95">
                    Отказ
                </button>
                <button id="deleteModalBtn"
                        class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-2xl text-sm font-bold shadow-md shadow-red-500/20 transition-all active:scale-95">
                    <i class="fas fa-trash-alt mr-1.5 text-xs"></i> Изтрий
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function () {
            var pendingDeleteUrl = null;

            function openDeleteModal() {
                $('#deleteModal').removeClass('hidden').addClass('flex');
            }
            function closeDeleteModal() {
                $('#deleteModal').removeClass('flex').addClass('hidden');
            }

            $('body').on('click', '.btn-delete-subject', function () {
                var id      = $(this).data('id');
                var subject = $(this).data('subject');
                var teacher = $(this).data('teacher');

                pendingDeleteUrl = "{{ url('/adm/subjects') }}/" + id;

                $('#deleteModalBody').html(
                    '<strong>' + subject + '</strong> — <strong>' + teacher + '</strong><br>' +
                    '<span class="text-xs">Това действие не може да бъде отменено.</span>'
                );

                openDeleteModal();
            });

            $('#deleteModalCancelBtn, #deleteModalBackdrop').on('click', function () {
                closeDeleteModal();
            });

            $('#deleteModalBtn').on('click', function () {
                if (!pendingDeleteUrl) return;

                $.ajax({
                    url: pendingDeleteUrl,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (data) {
                        toastr.success(data.message);
                        closeDeleteModal();
                        $('#subjects-table').DataTable().ajax.reload();
                    },
                    error: function () {
                        toastr.error('Възникна грешка. Моля, опитайте отново.');
                    }
                });
            });
        });
    </script>
@endpush
