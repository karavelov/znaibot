@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Настройка на срокове</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Дати на учебните срокове по клас</p>
        </div>
        <a href="{{ route('admin.schedule.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Към разписание
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <form action="{{ route('admin.semesters.store') }}" method="POST">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <!-- Semester group headers -->
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-widest text-gray-400 bg-gray-50/60 dark:bg-gray-800/40"
                                rowspan="2" style="min-width:120px;">Клас</th>
                            <th colspan="2"
                                class="px-6 py-3 text-center text-xs font-bold uppercase tracking-widest text-cyan-600 bg-cyan-50/60 dark:bg-cyan-900/20 border-b border-cyan-100 dark:border-cyan-900">
                                I срок
                            </th>
                            <th colspan="2"
                                class="px-6 py-3 text-center text-xs font-bold uppercase tracking-widest text-amber-600 bg-amber-50/60 dark:bg-amber-900/20 border-b border-amber-100 dark:border-amber-900">
                                II срок
                            </th>
                        </tr>
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-widest text-cyan-500 bg-cyan-50/40 dark:bg-cyan-900/10" style="min-width:160px;">От</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-widest text-cyan-500 bg-cyan-50/40 dark:bg-cyan-900/10" style="min-width:160px;">До</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-widest text-amber-500 bg-amber-50/40 dark:bg-amber-900/10" style="min-width:160px;">От</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-widest text-amber-500 bg-amber-50/40 dark:bg-amber-900/10" style="min-width:160px;">До</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($klasses as $klas)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-3 font-black text-gray-900 dark:text-white">{{ $klas->title }}</td>

                            {{-- I срок --}}
                            <td class="px-3 py-3 bg-cyan-50/20 dark:bg-cyan-900/5">
                                <input type="date"
                                       name="semesters[{{ $klas->id }}][1][start]"
                                       value="{{ optional($klas->semester1)->start_date?->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-400 transition-all">
                            </td>
                            <td class="px-3 py-3 bg-cyan-50/20 dark:bg-cyan-900/5">
                                <input type="date"
                                       name="semesters[{{ $klas->id }}][1][end]"
                                       value="{{ optional($klas->semester1)->end_date?->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-400 transition-all">
                            </td>

                            {{-- II срок --}}
                            <td class="px-3 py-3 bg-amber-50/20 dark:bg-amber-900/5">
                                <input type="date"
                                       name="semesters[{{ $klas->id }}][2][start]"
                                       value="{{ optional($klas->semester2)->start_date?->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition-all">
                            </td>
                            <td class="px-3 py-3 bg-amber-50/20 dark:bg-amber-900/5">
                                <input type="date"
                                       name="semesters[{{ $klas->id }}][2][end]"
                                       value="{{ optional($klas->semester2)->end_date?->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition-all">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save text-xs"></i> Запази всички срокове
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
