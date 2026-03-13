@extends('admin.layouts.master')

@php
    function fmtMin(int $minutes): string {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $h > 0 ? "{$h}ч {$m}мин" : "{$m}мин";
    }
@endphp

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Присъствена статистика</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $user->name }}</p>
        </div>
        <a href="{{ url()->previous() }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
        <form method="GET" action="{{ route('admin.nfc-logs.user-stats', $user->id) }}"
              class="flex flex-wrap items-end gap-4">

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">От дата</label>
                <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                       class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">До дата</label>
                <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                       class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    <i class="fas fa-filter mr-1"></i> Филтрирай
                </button>

                @php
                    $todayStr = now()->toDateString();
                    $isToday  = ($dateFrom === $todayStr && $dateTo === $todayStr);
                @endphp
                <a href="{{ route('admin.nfc-logs.user-stats', array_merge(['user' => $user->id], ['date_from' => $todayStr, 'date_to' => $todayStr])) }}"
                   class="px-4 py-2.5 rounded-2xl text-sm font-bold transition-all active:scale-95 {{ $isToday ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                    Днес
                </a>
                <a href="{{ route('admin.nfc-logs.user-stats', array_merge(['user' => $user->id], ['date_from' => now()->startOfWeek()->toDateString(), 'date_to' => $todayStr])) }}"
                   class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all active:scale-95">
                    Тази седмица
                </a>
                <a href="{{ route('admin.nfc-logs.user-stats', array_merge(['user' => $user->id], ['date_from' => now()->startOfMonth()->toDateString(), 'date_to' => $todayStr])) }}"
                   class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all active:scale-95">
                    Този месец
                </a>
                @if($dateFrom || $dateTo)
                    <a href="{{ route('admin.nfc-logs.user-stats', $user->id) }}"
                       class="px-4 py-2.5 bg-red-100 text-red-600 rounded-2xl text-sm font-bold hover:bg-red-200 transition-all active:scale-95">
                        <i class="fas fa-times mr-1"></i> Изчисти
                    </a>
                @endif
            </div>
        </form>

        @if($dateFrom || $dateTo)
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-xl text-xs font-black">
                <i class="fas fa-calendar-alt mr-1"></i>
                @if($dateFrom === $dateTo)
                    {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}
                @else
                    {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}
                    &rarr;
                    {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d F Y') }}
                @endif
            </span>
        </div>
        @endif
    </div>

    <!-- Profile + Stats Row -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- Profile Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6 flex flex-col items-center text-center">
            @if($user->image)
                <img src="{{ asset($user->image) }}"
                     class="w-20 h-20 rounded-2xl object-cover ring-4 ring-blue-100 mb-4">
            @else
                <div class="w-20 h-20 rounded-2xl bg-blue-50 ring-4 ring-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-user text-blue-400 text-2xl"></i>
                </div>
            @endif

            <h3 class="text-base font-black text-gray-900 dark:text-white mb-1">{{ $user->name }}</h3>
            <p class="text-xs text-gray-400 mb-3">{{ $user->email }}</p>

            @php
                $roleMap = [
                    'student'  => ['Ученик',        'blue'],
                    'teacher'  => ['Учител',        'amber'],
                    'admin'    => ['Администратор', 'green'],
                    'security' => ['Охрана',        'red'],
                    'parent'   => ['Родител',       'purple'],
                    'user'     => ['Потребител',    'gray'],
                ];
                [$rl, $rc] = $roleMap[$user->role] ?? [$user->role, 'gray'];
            @endphp
            <span class="px-2.5 py-1 bg-{{ $rc }}-100 text-{{ $rc }}-700 rounded-xl text-xs font-black">{{ $rl }}</span>

            @if($user->klas)
                <p class="text-xs text-gray-400 mt-2">
                    <i class="fas fa-chalkboard mr-1"></i>{{ $user->klas->title }}
                </p>
            @endif

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 w-full">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-user-edit text-xs text-gray-400"></i> Профил
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="lg:col-span-3 grid grid-cols-2 sm:grid-cols-4 gap-4">

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-3">
                    <i class="fas fa-calendar-check text-blue-500"></i>
                </div>
                <div class="text-3xl font-black text-blue-600">{{ $totalDays }}</div>
                <div class="text-xs text-gray-400 font-medium mt-1">Дни присъствие</div>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center mb-3">
                    <i class="fas fa-clock text-green-500"></i>
                </div>
                <div class="text-3xl font-black text-green-600">{{ $totalMinutesAll > 0 ? fmtMin($totalMinutesAll) : '—' }}</div>
                <div class="text-xs text-gray-400 font-medium mt-1">Общо престой</div>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-3">
                    <i class="fas fa-chart-line text-amber-500"></i>
                </div>
                <div class="text-3xl font-black text-amber-600">{{ $avgMinutes > 0 ? fmtMin($avgMinutes) : '—' }}</div>
                <div class="text-xs text-gray-400 font-medium mt-1">Средно на ден</div>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center mb-3">
                    <i class="fas fa-robot text-cyan-500"></i>
                </div>
                <div class="text-3xl font-black text-cyan-600">{{ $totalRobot }}</div>
                <div class="text-xs text-gray-400 font-medium mt-1">Знайбот взаимодействия</div>
            </div>

        </div>
    </div>

    <!-- Daily History -->
    @if(empty($days))
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-12 text-center">
            <i class="fas fa-door-closed text-4xl text-gray-200 dark:text-gray-700 mb-4 block"></i>
            <p class="text-gray-400 font-medium">Няма регистрирани посещения.</p>
        </div>
    @else
        @foreach($days as $dateStr => $day)
        @php
            $dateObj     = \Carbon\Carbon::parse($dateStr);
            $isToday     = $dateObj->isToday();
            $robot       = $robotByDate->get($dateStr, 0);
            $hasSessions = count($day['sessions']) > 0;
        @endphp
        <div class="bg-white dark:bg-gray-900 border {{ $isToday ? 'border-blue-200 dark:border-blue-800' : 'border-gray-100 dark:border-gray-800' }} rounded-[2rem] shadow-sm overflow-hidden">

            <!-- Day Header -->
            <div class="{{ $isToday ? 'bg-gradient-to-r from-blue-600 to-blue-500' : 'bg-gray-50 dark:bg-gray-800/50' }} px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($isToday)
                        <span class="px-2.5 py-1 bg-white/20 text-white rounded-xl text-xs font-black">Днес</span>
                    @endif
                    <div>
                        <p class="text-sm font-black {{ $isToday ? 'text-white' : 'text-gray-900 dark:text-white' }}">
                            {{ $dateObj->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-xs {{ $isToday ? 'text-blue-100' : 'text-gray-400' }}">
                            {{ $dateObj->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($robot > 0)
                        <span class="px-2.5 py-1 bg-cyan-100 text-cyan-700 rounded-xl text-xs font-black">
                            <i class="fas fa-robot mr-1"></i>{{ $robot }}
                        </span>
                    @endif
                    @if($day['totalMinutes'] > 0)
                        <span class="px-2.5 py-1 {{ $isToday ? 'bg-white/20 text-white' : 'bg-green-100 text-green-700' }} rounded-xl text-xs font-black">
                            <i class="fas fa-clock mr-1"></i>{{ fmtMin($day['totalMinutes']) }}
                        </span>
                    @endif
                </div>
            </div>

            @if($hasSessions)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-widest text-gray-400 w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-gray-400">
                                <i class="fas fa-sign-in-alt text-green-400 mr-1"></i> Влизане
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-gray-400">
                                <i class="fas fa-sign-out-alt text-red-400 mr-1"></i> Излизане
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-gray-400">
                                <i class="fas fa-hourglass-half text-amber-400 mr-1"></i> Престой
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-gray-400 hidden md:table-cell">Четец вход</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-widest text-gray-400 hidden md:table-cell">Четец изход</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($day['sessions'] as $i => $session)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-3 text-center text-gray-400 font-medium">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <span class="font-black text-green-600">{{ $session['in']->format('H:i:s') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($session['out'])
                                    <span class="font-black text-red-500">{{ $session['out']->format('H:i:s') }}</span>
                                @else
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-xl text-xs font-black">
                                        <i class="fas fa-circle mr-1" style="font-size:.5rem;"></i>В училище
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($session['minutes'] !== null)
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ fmtMin($session['minutes']) }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs hidden md:table-cell">{{ $session['reader_in'] }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs hidden md:table-cell">{{ $session['reader_out'] ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if(count($day['sessions']) > 1 && $day['totalMinutes'] > 0)
                    <tfoot>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800">
                            <td colspan="2" class="px-6 py-2 text-right text-xs font-bold uppercase tracking-widest text-gray-400">Общо:</td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2 font-black text-green-600">{{ fmtMin($day['totalMinutes']) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @else
            <div class="px-6 py-4">
                <p class="text-xs text-gray-400 font-medium">Само Знайбот активност — без записани влизания/излизания.</p>
            </div>
            @endif
        </div>
        @endforeach
    @endif

</div>
@endsection
