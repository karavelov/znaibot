@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Галерии</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на всички галерии</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Създай
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            {{ $dataTable->table() }}
        </div>
    </div>

</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    {{-- dynamically change status of gallery --}}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.gallery.status-change') }}",
                    method: 'PUT',
                    data: {
                        status: isChecked,
                        id: id
                    },
                    success: function(data) {
                        toastr.success(data.message);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.status + ': ' + xhr.statusText
                        toastr.error(errorMessage);
                    }
                });
            });
        });
    </script>
@endpush
