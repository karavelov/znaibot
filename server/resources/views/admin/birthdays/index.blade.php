@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                <i class="fas fa-birthday-cake text-red-400 mr-2"></i>Рождени дни
            </h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $label }} &mdash; {{ $users->count() }} {{ $users->count() === 1 ? 'рожденик' : 'рожденика' }}</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
        <form method="GET" action="{{ route('admin.birthdays.index') }}" id="birthday-form">
            <div class="flex flex-wrap items-end gap-4">

                <!-- Quick Filters -->
                <div class="flex flex-wrap gap-2">
                    @php
                        $quickFilters = [
                            'today'    => ['Днес',               'fas fa-star'],
                            'tomorrow' => ['Утре',               'fas fa-sun'],
                            'week'     => ['Следващата седмица', 'fas fa-calendar-week'],
                            'month'    => ['Този месец',         'fas fa-calendar-alt'],
                        ];
                    @endphp
                    @foreach($quickFilters as $key => [$label2, $icon])
                        <a href="{{ route('admin.birthdays.index', ['filter' => $key]) }}"
                           class="px-4 py-2.5 rounded-2xl text-sm font-bold transition-all active:scale-95
                                  {{ $filter === $key
                                       ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
                                       : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                            <i class="{{ $icon }} mr-1"></i>{{ $label2 }}
                        </a>
                    @endforeach
                </div>

                <!-- Custom Range -->
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">От</label>
                        <input type="date" name="date_from" id="date_from"
                               value="{{ $dateFrom ?? '' }}"
                               onchange="document.getElementById('filter-custom').value='custom'"
                               class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">До</label>
                        <input type="date" name="date_to" id="date_to"
                               value="{{ $dateTo ?? '' }}"
                               onchange="document.getElementById('filter-custom').value='custom'"
                               class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    </div>
                    <input type="hidden" name="filter" id="filter-custom" value="{{ $filter }}">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-2xl text-sm font-bold transition-all active:scale-95
                                   {{ $filter === 'custom'
                                        ? 'bg-green-600 text-white shadow-md shadow-green-500/20'
                                        : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                        <i class="fas fa-search mr-1"></i>Период
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- Results -->
    @if($users->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-12 text-center">
            <i class="fas fa-birthday-cake text-4xl text-gray-200 dark:text-gray-700 mb-4 block"></i>
            <p class="text-gray-400 font-medium">Няма рождени дни за избрания период.</p>
        </div>
    @else

    @php
        $roleOrder  = ['teacher', 'student'];
        $otherRoles = $users->filter(fn($u) => !in_array($u->role, $roleOrder))->values();
        $groups = [];
        foreach ($roleOrder as $role) {
            $group = $users->filter(fn($u) => $u->role === $role)->values();
            if ($group->isNotEmpty()) {
                $groups[$role] = $group;
            }
        }
        if ($otherRoles->isNotEmpty()) {
            $groups['other'] = $otherRoles;
        }

        $roleMeta = [
            'teacher' => ['label' => 'Учители',       'icon' => 'fas fa-chalkboard-teacher', 'tw' => 'amber'],
            'student' => ['label' => 'Ученици',       'icon' => 'fas fa-user-graduate',      'tw' => 'blue'],
            'other'   => ['label' => 'Други профили', 'icon' => 'fas fa-users',              'tw' => 'purple'],
        ];

        $roleLabels = [
            'teacher'  => ['Учител',        'amber'],
            'student'  => ['Ученик',        'blue'],
            'admin'    => ['Администратор', 'green'],
            'parent'   => ['Родител',       'purple'],
            'security' => ['Охрана',        'red'],
            'user'     => ['Потребител',    'gray'],
        ];
    @endphp

    @foreach($groups as $roleKey => $group)
    @php $meta = $roleMeta[$roleKey]; @endphp

    <!-- Group Header -->
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-2xl bg-{{ $meta['tw'] }}-100 flex items-center justify-center">
            <i class="{{ $meta['icon'] }} text-{{ $meta['tw'] }}-500 text-sm"></i>
        </div>
        <h2 class="text-base font-black text-gray-900 dark:text-white">{{ $meta['label'] }}</h2>
        <span class="px-2.5 py-1 bg-{{ $meta['tw'] }}-100 text-{{ $meta['tw'] }}-700 rounded-xl text-xs font-black">{{ $group->count() }}</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @foreach($group as $user)
        @php
            $isToday = Carbon\Carbon::parse($user->birth_date)->month === $today->month
                    && Carbon\Carbon::parse($user->birth_date)->day   === $today->day;
            [$rl, $rc] = $roleLabels[$user->role] ?? [$user->role, 'gray'];
        @endphp

        <a href="{{ route('admin.users.edit', $user->id) }}"
           class="relative bg-white dark:bg-gray-900 border {{ $isToday ? 'border-red-200 dark:border-red-800 ring-2 ring-red-200' : 'border-gray-100 dark:border-gray-800' }} rounded-[2rem] shadow-sm p-4 flex flex-col items-center text-center hover:shadow-md transition-all active:scale-95">

            @if($isToday)
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-red-500 text-white rounded-xl text-xs font-black whitespace-nowrap">
                <i class="fas fa-birthday-cake mr-1"></i>Днес!
            </span>
            @endif

            <!-- Avatar -->
            <div class="mb-3 {{ $isToday ? 'mt-2' : '' }}">
                @if($user->image)
                    <img src="{{ asset($user->image) }}"
                         class="w-16 h-16 rounded-2xl object-cover ring-4 ring-{{ $isToday ? 'red' : $meta['tw'] }}-100"
                         alt="{{ $user->name }}">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-{{ $meta['tw'] }}-50 ring-4 ring-{{ $meta['tw'] }}-100 flex items-center justify-center">
                        <i class="{{ $meta['icon'] }} text-{{ $meta['tw'] }}-400 text-xl"></i>
                    </div>
                @endif
            </div>

            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight mb-2">{{ $user->name }}</p>

            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-xl text-xs font-bold mb-1">
                <i class="fas fa-birthday-cake text-red-400 mr-1"></i>{{ $user->birth_month_day }} · {{ $user->age }} г.
            </span>

            <span class="px-2 py-0.5 bg-{{ $rc }}-100 text-{{ $rc }}-700 rounded-xl text-xs font-black">{{ $rl }}</span>

            @if($user->role === 'student' && $user->klas)
            <span class="mt-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-xl text-xs font-medium">
                <i class="fas fa-school mr-1"></i>{{ $user->klas->title }}
            </span>
            @endif
        </a>
        @endforeach
    </div>
    @endforeach

    @endif

</div>
@endsection

