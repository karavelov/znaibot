@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">NFC Журнал</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Всички NFC сканирания</p>
        </div>
        <a href="{{ route('admin.nfc-logs.presence') }}"
           class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-green-500/20 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-users text-xs"></i> Кой е в училище сега
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 sm:p-6">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Четец</label>
                <select id="readerFilter"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <option value="">— Всички —</option>
                    @foreach($readers as $reader)
                        <option value="{{ $reader->id }}">{{ $reader->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Дата</label>
                <input type="date" id="dateFilter"
                       value="{{ date('Y-m-d') }}"
                       class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <button id="clearFilters"
                    class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-times text-xs text-gray-400"></i> Изчисти
            </button>
        </div>
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

    <script>
        $('#nfc-logs-table').on('init.dt', function () {
            var table = $(this).DataTable();

            function reload() {
                table.ajax.url(
                    window.location.pathname +
                    '?reader_filter=' + $('#readerFilter').val() +
                    '&date_filter=' + $('#dateFilter').val()
                ).load();
            }

            $('#readerFilter, #dateFilter').on('change', reload);

            $('#clearFilters').on('click', function () {
                $('#readerFilter').val('');
                $('#dateFilter').val('');
                reload();
            });

            // Активирай филтъра за днес при зареждане
            reload();
        });
    </script>
@endpush
