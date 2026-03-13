@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Седмично разписание</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Избери клас и срок за редакция</p>
        </div>
        <a href="{{ route('admin.semesters.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-calendar-alt text-xs text-gray-400"></i> Настройка на срокове
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-gray-400" style="min-width:160px;">Клас</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400">I срок</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400">II срок</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($klasses as $klas)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 font-black text-gray-900 dark:text-white">{{ $klas->title }}</td>

                        {{-- I срок --}}
                        <td class="px-6 py-4 text-center">
                            @if($klas->semester1)
                                <div class="text-gray-400 text-xs font-medium mb-2">
                                    {{ $klas->semester1->start_date?->format('d.m.Y') }}
                                    –
                                    {{ $klas->semester1->end_date?->format('d.m.Y') }}
                                </div>
                            @endif
                            <a href="{{ route('admin.schedule.edit', [$klas->id, 1]) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-cyan-100 hover:bg-cyan-200 text-cyan-700 rounded-xl text-xs font-bold transition-all active:scale-95">
                                <i class="fas fa-edit"></i> Разписание
                            </a>
                        </td>

                        {{-- II срок --}}
                        <td class="px-6 py-4 text-center">
                            @if($klas->semester2)
                                <div class="text-gray-400 text-xs font-medium mb-2">
                                    {{ $klas->semester2->start_date?->format('d.m.Y') }}
                                    –
                                    {{ $klas->semester2->end_date?->format('d.m.Y') }}
                                </div>
                            @endif
                            <a href="{{ route('admin.schedule.edit', [$klas->id, 2]) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-xl text-xs font-bold transition-all active:scale-95">
                                <i class="fas fa-edit"></i> Разписание
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-400 font-medium">Няма създадени класове.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
