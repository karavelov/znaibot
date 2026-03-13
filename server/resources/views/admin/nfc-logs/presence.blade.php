@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Кой е в училище сега</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">
                Присъствие в реално време &bull; Обновява се автоматично
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400 font-medium" id="lastUpdated">{{ now()->format('H:i:s') }}</span>
            <a href="{{ route('admin.nfc-logs.late') }}"
               class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-sm font-bold shadow-md shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-user-clock text-xs"></i> Закъснения
            </a>
            <a href="{{ route('admin.nfc-logs.index') }}"
               class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-list text-xs text-gray-400"></i> Пълен журнал
            </a>
        </div>
    </div>

    <!-- Counter Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2rem] shadow-lg shadow-blue-500/20 p-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
            <i class="fas fa-users fa-lg text-white"></i>
        </div>
        <div>
            <div class="text-4xl font-black text-white leading-none" id="presentCount">{{ $presentLogs->count() }}</div>
            <div class="text-blue-100 text-sm font-medium mt-1">
                {{ $presentLogs->count() == 1 ? 'човек' : 'човека' }} в училище към {{ now()->format('H:i') }}
            </div>
        </div>
    </div>

    <!-- Presence Cards -->
    <div id="presenceCards">
        @if($presentLogs->isEmpty())
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-12 text-center">
                <i class="fas fa-door-closed fa-3x text-gray-200 dark:text-gray-700 mb-4 block"></i>
                <p class="text-gray-400 font-medium">Няма регистрирани присъствия за днес.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($presentLogs as $log)
                @php $user = $log->user; @endphp
                @php
                    $roleMap = [
                        'student'  => ['Ученик',         'bg-blue-100 text-blue-700'],
                        'teacher'  => ['Учител',         'bg-amber-100 text-amber-700'],
                        'admin'    => ['Администратор',  'bg-green-100 text-green-700'],
                        'security' => ['Охрана',         'bg-red-100 text-red-700'],
                        'parent'   => ['Родител',        'bg-gray-100 text-gray-700'],
                        'user'     => ['Потребител',     'bg-cyan-100 text-cyan-700'],
                    ];
                    [$rl, $rc] = $roleMap[$user->role] ?? [$user->role, 'bg-gray-100 text-gray-600'];
                @endphp
                <a href="{{ route('admin.nfc-logs.user-stats', $user->id) }}"
                   class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[1.5rem] shadow-sm hover:shadow-md transition-all active:scale-95 p-4 flex flex-col items-center text-center group"
                   title="Виж статистика">
                    <!-- Avatar -->
                    <div class="mb-3">
                        @if($user->image)
                            <img src="{{ asset($user->image) }}"
                                 class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-100"
                                 alt="{{ $user->name }}">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 border-2 border-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-400 text-xl"></i>
                            </div>
                        @endif
                    </div>
                    <!-- Name -->
                    <div class="font-bold text-gray-900 dark:text-white text-sm leading-tight mb-1">{{ $user->name }}</div>
                    <!-- Role badge -->
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $rc }}">{{ $rl }}</span>
                    <!-- Class -->
                    @if($user->role === 'student' && $user->klas)
                        <div class="text-gray-400 text-xs mt-1">
                            <i class="fas fa-chalkboard mr-0.5"></i>{{ $user->klas->title }}
                        </div>
                    @endif
                    <!-- Entry time -->
                    <div class="mt-3 pt-3 border-t border-gray-50 dark:border-gray-800 w-full">
                        <div class="text-green-600 text-xs font-bold">
                            <i class="fas fa-sign-in-alt mr-0.5"></i>{{ $log->read_at->format('H:i') }}
                        </div>
                        <div class="text-gray-300 text-xs">{{ $log->read_at->diffForHumans() }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Robot Section -->
    @if($robotLogs->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-800 flex items-center gap-3">
            <span class="w-8 h-8 rounded-xl bg-cyan-50 flex items-center justify-center">
                <i class="fas fa-robot text-cyan-500 text-sm"></i>
            </span>
            <h2 class="text-base font-black text-gray-900 dark:text-white">Взаимодействия със Знайбот днес</h2>
        </div>
        <ul class="divide-y divide-gray-50 dark:divide-gray-800">
            @foreach($robotLogs as $entry)
            <li class="flex items-center gap-4 px-6 py-3">
                @if($entry->user?->image)
                    <img src="{{ asset($entry->user->image) }}"
                         class="w-9 h-9 rounded-xl object-cover shrink-0">
                @else
                    <div class="w-9 h-9 rounded-xl bg-cyan-50 flex items-center justify-center shrink-0">
                        <i class="fas fa-user text-cyan-400 text-sm"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $entry->user?->name ?? '—' }}</span>
                    @if($entry->user?->klas)
                        <span class="text-gray-400 text-xs ml-1">{{ $entry->user->klas->title }}</span>
                    @endif
                </div>
                <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-xl text-sm font-black">{{ $entry->count }} пъти</span>
                <span class="text-gray-400 text-xs hidden sm:block">Последно: {{ \Carbon\Carbon::parse($entry->last_at)->format('H:i') }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Автоматично обновяване на 30 секунди
    setInterval(function () {
        $.get('{{ route('admin.nfc-logs.presence') }}', function (html) {
            var $doc     = $(html);
            var newCards = $doc.find('#presenceCards').html();
            var newCount = $doc.find('#presentCount').text();

            $('#presenceCards').html(newCards);
            $('#presentCount').text(newCount);
            $('#lastUpdated').text(new Date().toLocaleTimeString('bg-BG'));
        });
    }, 30000);
});
</script>
@endpush
