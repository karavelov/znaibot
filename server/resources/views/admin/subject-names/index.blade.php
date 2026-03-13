@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Предмети</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на наименованията на предмети</p>
        </div>
        <a href="{{ route('admin.subject-names.create') }}"
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
            var pendingId = null;

            function openDeleteModal() {
                $('#deleteModal').removeClass('hidden').addClass('flex');
            }
            function closeDeleteModal() {
                $('#deleteModal').removeClass('flex').addClass('hidden');
                pendingId = null;
                $('#deleteModalBtn').show();
            }

            $('body').on('click', '.btn-delete-name', function () {
                var id    = $(this).data('id');
                var name  = $(this).data('name');
                var count = parseInt($(this).data('count'));

                pendingId = id;

                if (count > 0) {
                    $('#deleteModalBody').html(
                        '<strong>' + name + '</strong> има <strong>' + count + '</strong> назначен(и) учител(и).<br>' +
                        '<span class="text-red-500 text-xs">Първо премахнете назначенията от секция „Предмет → Учител".</span>'
                    );
                    $('#deleteModalBtn').hide();
                } else {
                    $('#deleteModalBody').html(
                        'Сигурни ли сте, че искате да изтриете<br>' +
                        '<strong>' + name + '</strong>?<br>' +
                        '<span class="text-xs">Това действие не може да бъде отменено.</span>'
                    );
                    $('#deleteModalBtn').show();
                }

                openDeleteModal();
            });

            $('#deleteModalCancelBtn, #deleteModalBackdrop').on('click', function () {
                closeDeleteModal();
            });

            $('#deleteModalBtn').on('click', function () {
                if (!pendingId) return;

                $.ajax({
                    url: "{{ url('/adm/subject-names') }}/" + pendingId,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (data) {
                        toastr.success(data.message);
                        closeDeleteModal();
                        $('#subject-names-table').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Възникна грешка.';
                        toastr.error(msg);
                        closeDeleteModal();
                    }
                });
            });
        });
    </script>
@endpush
