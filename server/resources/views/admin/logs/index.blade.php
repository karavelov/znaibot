@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Логове</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Системни записи на потребителски активности</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-2xl font-medium">
            <i class="fas fa-list-ul text-xs"></i>
            <span>{{ $logs->total() }} записа</span>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">

            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-inbox text-2xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <p class="text-gray-400 dark:text-gray-500 font-medium">Няма намерени записи</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="pb-3 pr-4 text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase w-14">#</th>
                                <th class="pb-3 pr-4 text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase">Текст</th>
                                <th class="pb-3 pr-4 text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase w-44">Потребител</th>
                                <th class="pb-3 text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase w-24 text-right">Действие</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @foreach($logs as $log)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="py-4 pr-4">
                                    <span class="inline-flex items-center justify-center w-7 h-7 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-bold text-gray-500 dark:text-gray-400">
                                        {{ $log->id }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4">
                                    <p class="text-gray-800 dark:text-gray-200 font-medium leading-snug line-clamp-2">{{ $log->text }}</p>
                                </td>
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                            <i class="fas fa-user text-[10px] text-blue-500"></i>
                                        </div>
                                        <span class="text-gray-600 dark:text-gray-300 font-medium truncate max-w-[130px]">
                                            {{ $log->user ? $log->user->name : 'Непознат' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 text-right">
                                    <button
                                        data-id="{{ $log->id }}"
                                        data-url="{{ route('admin.logs.destroy', $log->id) }}"
                                        class="delete-log inline-flex items-center justify-center w-8 h-8 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 hover:text-red-600 transition-all active:scale-90 opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($logs->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">
                        Показани {{ $logs->firstItem() }}–{{ $logs->lastItem() }} от {{ $logs->total() }}
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if($logs->onFirstPage())
                            <span class="px-3 py-1.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}"
                               class="px-3 py-1.5 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                            @if($page == $logs->currentPage())
                                <span class="px-3 py-1.5 rounded-xl text-sm font-bold bg-blue-600 text-white shadow-sm shadow-blue-500/20">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                   class="px-3 py-1.5 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}"
                               class="px-3 py-1.5 rounded-xl text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span class="px-3 py-1.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        @endif
                    </div>
                </div>
                @endif

            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('body').on('click', '.delete-log', function () {
            const id  = $(this).data('id');
            const url = $(this).data('url');
            const row = $(this).closest('tr');

            if (!confirm('Сигурни ли сте, че искате да изтриете този запис?')) return;

            $.ajax({
                url: url,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success(data.message);
                    row.fadeOut(300, function () { $(this).remove(); });
                },
                error: function (xhr) {
                    toastr.error('Грешка при изтриване на записа.');
                    console.error(xhr);
                }
            });
        });
    });
</script>
@endpush
