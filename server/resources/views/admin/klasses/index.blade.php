@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Класове</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на класове</p>
        </div>
        <a href="{{ route('admin.klasses.create') }}"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Създай
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
        {{ $dataTable->table() }}
    </div>

</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function () {
            $('body').on('click', '.delete-item', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                if (confirm('Сигурни ли сте, че искате да изтриете този клас?')) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (data) {
                            toastr.success(data.message);
                            $('#klasses-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) { console.log(xhr); }
                    });
                }
            });
        });
    </script>
@endpush
