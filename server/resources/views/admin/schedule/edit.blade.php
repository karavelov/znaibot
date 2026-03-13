@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Разписание — {{ $klas->title }}</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">
                @if($semesterDates)
                    {{ $semester == 1 ? 'I срок' : 'II срок' }}
                    &bull;
                    {{ $semesterDates->start_date?->format('d.m.Y') }} – {{ $semesterDates->end_date?->format('d.m.Y') }}
                @else
                    {{ $semester == 1 ? 'I срок' : 'II срок' }} &bull; Сроковете не са зададени
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.schedule.edit', [$klas->id, $semester == 1 ? 2 : 1]) }}"
               class="px-4 py-2.5 {{ $semester == 1 ? 'bg-amber-100 hover:bg-amber-200 text-amber-700' : 'bg-cyan-100 hover:bg-cyan-200 text-cyan-700' }} rounded-2xl text-sm font-bold transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-exchange-alt text-xs"></i>
                Превключи към {{ $semester == 1 ? 'II срок' : 'I срок' }}
            </a>
            <a href="{{ route('admin.schedule.index') }}"
               class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
            </a>
        </div>
    </div>

    <!-- Schedule Grid Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <form action="{{ route('admin.schedule.update', [$klas->id, $semester]) }}"
              method="POST" id="scheduleForm">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="schedule-grid">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <th class="px-4 py-3.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400 bg-gray-50/60 dark:bg-gray-800/40"
                                style="min-width:52px;">Час</th>
                            @foreach($days as $dayNum => $dayName)
                            <th class="px-3 py-3.5 text-center text-xs font-bold uppercase tracking-widest text-gray-400 bg-gray-50/60 dark:bg-gray-800/40">
                                {{ $dayName }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @for($period = 1; $period <= $periods; $period++)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="px-4 py-2.5 text-center font-black text-gray-500 dark:text-gray-400 bg-gray-50/40 dark:bg-gray-800/20">
                                {{ $period }}
                            </td>
                            @foreach($days as $dayNum => $dayName)
                            @php
                                $key     = $dayNum . '_' . $period;
                                $current = $scheduleData->get($key)?->subject_teacher_id;
                            @endphp
                            <td class="px-2 py-2" style="min-width:180px;">
                                <select name="schedule[{{ $dayNum }}][{{ $period }}]"
                                        class="schedule-select w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl text-xs font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                                    <option value="">—</option>
                                    @foreach($subjectTeachers as $st)
                                    <option value="{{ $st->id }}" {{ $current == $st->id ? 'selected' : '' }}>
                                        {{ $st->subject->name }} — {{ $st->teacher->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            @endforeach
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 flex items-center justify-between">
                <button type="button" id="clearAll"
                        class="px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-eraser text-xs text-gray-400"></i> Изчисти цялото разписание
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save text-xs"></i> Запази разписание
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Confirm clear modal --}}
<div class="modal fade" id="clearModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div class="w-100 text-center pt-4">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto">
                        <i class="fas fa-eraser fa-2x text-amber-500"></i>
                    </div>
                </div>
                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center px-6 pt-3 pb-2">
                <h5 class="font-bold text-gray-900 mb-2">Изчистване на разписание</h5>
                <p class="text-gray-400 text-sm mb-0">
                    Сигурни ли сте, че искате да изчистите всички клетки?<br>
                    <span class="text-xs">Промените ще бъдат запазени след натискане на „Запази разписание".</span>
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-5 gap-3">
                <button type="button"
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all"
                        data-dismiss="modal">Отказ</button>
                <button type="button" id="confirmClear"
                        class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-sm font-bold shadow-md shadow-amber-500/20 transition-all active:scale-95">
                    <i class="fas fa-eraser mr-1"></i> Изчисти
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .schedule-select.has-value {
        background-color: #eff6ff;
        border-color: #93c5fd;
        color: #1d4ed8;
    }
</style>

<script>
$(document).ready(function () {

    function highlightSelects() {
        $('.schedule-select').each(function () {
            if ($(this).val()) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
        });
    }
    highlightSelects();
    $(document).on('change', '.schedule-select', highlightSelects);

    $('#clearAll').on('click', function () {
        $('#clearModal').modal('show');
    });

    $('#confirmClear').on('click', function () {
        $('.schedule-select').val('').trigger('change');
        $('#clearModal').modal('hide');
    });

});
</script>
@endpush

@push('scripts')
<style>
    #schedule-grid td { vertical-align: middle; }
    .schedule-select { font-size: 0.8rem; }
    .schedule-select:focus { border-color: #6777ef; box-shadow: 0 0 0 .2rem rgba(103,119,239,.25); }
    /* Оцвети клетки с избран предмет */
    .schedule-select.has-value { background-color: #eaf4ff; border-color: #6777ef; }
</style>

<script>
$(document).ready(function () {

    // Маркирай попълнени клетки
    function highlightSelects() {
        $('.schedule-select').each(function () {
            if ($(this).val()) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
        });
    }
    highlightSelects();
    $(document).on('change', '.schedule-select', highlightSelects);

    // Изчисти всички
    $('#clearAll').on('click', function () {
        $('#clearModal').modal('show');
    });

    $('#confirmClear').on('click', function () {
        $('.schedule-select').val('').trigger('change');
        $('#clearModal').modal('hide');
    });

});
</script>
@endpush
