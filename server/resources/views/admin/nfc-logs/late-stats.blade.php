@extends('admin.layouts.master')

@php
    function fmtLate(int $minutes): string {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $h > 0 ? "{$h}ч {$m}мин" : "{$m}мин";
    }

    $roleMap = [
        'student'  => ['Ученик',        'bg-blue-100 text-blue-700'],
        'teacher'  => ['Учител',        'bg-amber-100 text-amber-700'],
        'admin'    => ['Администратор', 'bg-green-100 text-green-700'],
        'security' => ['Охрана',        'bg-red-100 text-red-700'],
        'parent'   => ['Родител',       'bg-gray-100 text-gray-600'],
        'user'     => ['Потребител',    'bg-cyan-100 text-cyan-700'],
    ];
@endphp

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Закъснения</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Статистика за закъснения по период</p>
        </div>
        <a href="{{ route('admin.nfc-logs.presence') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 sm:p-6">
        <form method="GET" action="{{ route('admin.nfc-logs.late') }}">
            <div class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">От дата</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                           class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">До дата</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                           class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        <i class="fas fa-clock mr-1"></i>Закъснение след
                    </label>
                    <input type="time" name="cutoff" value="{{ $cutoff }}"
                           class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-filter text-xs"></i> Филтрирай
                    </button>
                    <a href="{{ route('admin.nfc-logs.late', ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString(), 'cutoff' => $cutoff]) }}"
                       class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Днес
                    </a>
                    <a href="{{ route('admin.nfc-logs.late', ['date_from' => now()->startOfWeek()->toDateString(), 'date_to' => now()->toDateString(), 'cutoff' => $cutoff]) }}"
                       class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Тази седмица
                    </a>
                    <a href="{{ route('admin.nfc-logs.late', ['date_from' => now()->startOfMonth()->toDateString(), 'date_to' => now()->toDateString(), 'cutoff' => $cutoff]) }}"
                       class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Този месец
                    </a>
                </div>
            </div>
            <!-- Active period badges -->
            <div class="mt-4 flex items-center gap-2 flex-wrap">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-xl text-xs font-bold">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    @if($dateFrom === $dateTo)
                        {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}
                        &rarr;
                        {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d F Y') }}
                    @endif
                </span>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-xl text-xs font-bold">
                    <i class="fas fa-clock mr-1"></i>Закъснение след {{ $cutoff }}
                </span>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mb-3">
                <i class="fas fa-user-clock text-red-500 text-lg"></i>
            </div>
            <div class="text-3xl font-black text-red-500 leading-none">{{ $totalInstances }}</div>
            <div class="text-gray-400 text-xs font-medium mt-1">Общо закъснения</div>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-3">
                <i class="fas fa-users text-amber-500 text-lg"></i>
            </div>
            <div class="text-3xl font-black text-amber-500 leading-none">{{ $uniqueLateUsers }}</div>
            <div class="text-gray-400 text-xs font-medium mt-1">Уникални лица</div>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center mb-3">
                <i class="fas fa-calendar-times text-indigo-500 text-lg"></i>
            </div>
            <div class="text-3xl font-black text-indigo-500 leading-none">{{ $byDate->count() }}</div>
            <div class="text-gray-400 text-xs font-medium mt-1">Дни със закъснения</div>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center mb-3">
                <i class="fas fa-hourglass-half text-green-500 text-lg"></i>
            </div>
            @php
                $avgLate = $totalInstances > 0
                    ? (int) round($byDate->flatten()->avg('minutes_late'))
                    : 0;
            @endphp
            <div class="text-3xl font-black text-green-500 leading-none">{{ $avgLate > 0 ? fmtLate($avgLate) : '—' }}</div>
            <div class="text-gray-400 text-xs font-medium mt-1">Средно закъснение</div>
        </div>
    </div>

    @if($totalInstances === 0)
        <!-- Empty state -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-12 text-center">
            <i class="fas fa-check-circle fa-3x text-green-300 mb-4 block"></i>
            <p class="text-gray-400 font-medium">Няма регистрирани закъснения за избрания период.</p>
        </div>
    @else

    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">

        <!-- By Date (left / wider) -->
        <div class="lg:col-span-4 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-800 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-red-500 text-sm"></i>
                </span>
                <h2 class="text-base font-black text-gray-900 dark:text-white">По дни</h2>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @foreach($byDate as $dateStr => $entries)
                @php $dateObj = \Carbon\Carbon::parse($dateStr); @endphp
                <div>
                    <button type="button"
                            class="w-full px-5 py-3 flex items-center justify-between bg-gray-50/60 dark:bg-gray-800/40 hover:bg-gray-100/60 transition-all text-left"
                            data-toggle="collapse"
                            data-target="#day-{{ str_replace('-', '', $dateStr) }}">
                        <div class="flex items-center gap-2">
                            @if($dateObj->isToday())
                                <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-lg text-xs font-bold">Днес</span>
                            @endif
                            <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $dateObj->translatedFormat('l, d F Y') }}</span>
                            <span class="text-gray-400 text-xs hidden sm:inline">({{ $dateObj->diffForHumans() }})</span>
                        </div>
                        <span class="px-2.5 py-1 bg-red-100 text-red-600 rounded-xl text-xs font-black shrink-0">
                            {{ $entries->count() }} {{ $entries->count() === 1 ? 'закъснение' : 'закъснения' }}
                        </span>
                    </button>
                    <div id="day-{{ str_replace('-', '', $dateStr) }}"
                         class="{{ $dateObj->isToday() ? 'show' : '' }} collapse">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-50 dark:border-gray-800">
                                        <th class="px-5 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-gray-400">Потребител</th>
                                        <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-gray-400">Клас</th>
                                        <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-gray-400">Влизане</th>
                                        <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-gray-400">Закъснение</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                    @foreach($entries->sortBy('first_arrival') as $row)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2.5">
                                                @if($row->user_image)
                                                    <img src="{{ asset($row->user_image) }}"
                                                         class="w-7 h-7 rounded-xl object-cover shrink-0">
                                                @else
                                                    <div class="w-7 h-7 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                                                        <i class="fas fa-user text-indigo-400 text-xs"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('admin.nfc-logs.user-stats', $row->user_id) }}"
                                                       class="font-bold text-gray-900 dark:text-white hover:text-blue-600 transition-colors text-sm">
                                                        {{ $row->user_name }}
                                                    </a>
                                                    @php [$rl, $rc] = $roleMap[$row->user_role] ?? [$row->user_role, 'bg-gray-100 text-gray-600']; @endphp
                                                    <span class="ml-1 px-1.5 py-0.5 {{ $rc }} rounded-md text-xs font-bold">{{ $rl }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-gray-400 text-xs">{{ $row->klas_title ?? '—' }}</td>
                                        <td class="px-3 py-3">
                                            <span class="font-black text-red-500 text-sm">
                                                {{ \Carbon\Carbon::parse($row->first_arrival)->format('H:i:s') }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-xl text-xs font-black">
                                                +{{ fmtLate($row->minutes_late) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- By User (right / narrower) -->
        <div class="lg:col-span-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-800 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fas fa-user-clock text-amber-500 text-sm"></i>
                </span>
                <h2 class="text-base font-black text-gray-900 dark:text-white">Най-чести закъснения</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <th class="px-5 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-gray-400">Потребител</th>
                            <th class="px-3 py-2.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400">Пъти</th>
                            <th class="px-3 py-2.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400">Средно</th>
                            <th class="px-3 py-2.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400">Макс.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($byUser as $entry)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    @if($entry->user_image)
                                        <img src="{{ asset($entry->user_image) }}"
                                             class="w-7 h-7 rounded-xl object-cover shrink-0">
                                    @else
                                        <div class="w-7 h-7 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                                            <i class="fas fa-user text-indigo-400 text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.nfc-logs.user-stats', $entry->user_id) }}"
                                           class="font-bold text-gray-900 dark:text-white hover:text-blue-600 transition-colors text-sm block leading-tight">
                                            {{ $entry->user_name }}
                                        </a>
                                        <span class="text-gray-400 text-xs">{{ $entry->klas_title ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="px-2.5 py-1 bg-red-100 text-red-600 rounded-xl text-xs font-black">
                                    {{ $entry->count }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center font-black text-amber-500 text-sm">
                                +{{ fmtLate($entry->avg_minutes) }}
                            </td>
                            <td class="px-3 py-3 text-center font-black text-red-500 text-sm">
                                +{{ fmtLate($entry->max_minutes) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @endif

</div>
@endsection
