@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редактиране на клас</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $klas->title }}</p>
        </div>
        <a href="{{ route('admin.klasses.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Edit Form Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
            <h2 class="text-base font-black text-gray-900 dark:text-white mb-6">Наименование</h2>
            <form action="{{ route('admin.klasses.update', $klas->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                            Наименование на клас <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $klas->title) }}"
                               placeholder="Пример: 10а клас"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border @error('title') border-red-400 @else border-gray-100 dark:border-gray-700 @enderror rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        @error('title')
                            <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                        Запази
                    </button>
                    <a href="{{ route('admin.klasses.index') }}"
                       class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Откажи
                    </a>
                </div>
            </form>
        </div>

        <div class="space-y-6">

            <!-- Homeroom Teacher Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
                <h2 class="text-base font-black text-gray-900 dark:text-white mb-4">Класен ръководител</h2>
                @if($klas->homeroomTeacher)
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chalkboard-teacher text-blue-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $klas->homeroomTeacher->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $klas->homeroomTeacher->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $klas->homeroomTeacher->id) }}"
                               class="w-9 h-9 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-all"
                               title="Редактиране">
                                <i class="far fa-edit text-sm"></i>
                            </a>
                            <button type="button"
                                    class="btn-remove-homeroom w-9 h-9 bg-red-50 border border-red-100 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-100 transition-all"
                                    data-url="{{ route('admin.klasses.remove-homeroom', $klas->id) }}"
                                    data-name="{{ $klas->homeroomTeacher->name }}"
                                    title="Премахване">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-400 font-medium">Няма асоцииран класен ръководител.</p>
                @endif
            </div>

            <!-- Students Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
                <h2 class="text-base font-black text-gray-900 dark:text-white mb-4">Ученици в класа</h2>
                @if($klas->students->isEmpty())
                    <p class="text-sm text-gray-400 font-medium">Няма добавени ученици в този клас.</p>
                @else
                    <ul class="divide-y divide-gray-50 dark:divide-gray-800" id="students-list">
                        @foreach($klas->students as $student)
                        <li class="py-3 flex items-center gap-3" id="student-{{ $student->id }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $student->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $student->email }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $student->id) }}"
                                   class="w-8 h-8 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-all"
                                   title="Редактиране">
                                    <i class="far fa-edit text-xs"></i>
                                </a>
                                <button type="button"
                                        class="btn-remove-student w-8 h-8 bg-red-50 border border-red-100 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-100 transition-all"
                                        data-url="{{ route('admin.klasses.remove-student', [$klas->id, $student->id]) }}"
                                        data-student-id="{{ $student->id }}"
                                        data-name="{{ $student->name }}"
                                        title="Премахване">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>

</div>

{{-- Confirm Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div class="w-100 text-center pt-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-warning rounded-circle" style="width:56px;height:56px;">
                        <i class="fas fa-exclamation-triangle text-white fa-lg"></i>
                    </div>
                </div>
                <button type="button" class="close position-absolute" style="top:12px;right:16px;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center px-5 pt-3 pb-2">
                <h5 class="font-weight-bold mb-2" id="confirmModalTitle">Премахване</h5>
                <p class="text-muted small mb-0" id="confirmModalBody"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-dismiss="modal">Отказ</button>
                <button type="button" class="btn btn-danger px-4 rounded-pill" id="confirmModalBtn">
                    <i class="fas fa-times mr-1"></i> Премахни
                </button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function () {

    var pendingAction = null;

    // Премахване на ученик
    $('body').on('click', '.btn-remove-student', function () {
        var btn       = $(this);
        var name      = btn.data('name');
        var url       = btn.data('url');
        var studentId = btn.data('student-id');

        $('#confirmModalTitle').text('Премахване на ученик');
        $('#confirmModalBody').html(
            'Сигурни ли сте, че искате да премахнете<br><strong>' + name + '</strong> от класа?<br>' +
            '<small class="text-muted">Профилът на ученика няма да бъде изтрит.</small>'
        );

        pendingAction = function () {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success(data.message);
                    $('#student-' + studentId).fadeOut(300, function () {
                        $(this).remove();
                        if ($('#students-list li').length === 0) {
                            $('#students-list').replaceWith('<p class="text-muted">Няма добавени ученици в този клас.</p>');
                        }
                    });
                },
                error: function () {
                    toastr.error('Възникна грешка. Моля, опитайте отново.');
                }
            });
        };

        $('#confirmModal').modal('show');
    });

    // Премахване на класен ръководител
    $('body').on('click', '.btn-remove-homeroom', function () {
        var btn  = $(this);
        var name = btn.data('name');
        var url  = btn.data('url');

        $('#confirmModalTitle').text('Премахване на класен ръководител');
        $('#confirmModalBody').html(
            'Сигурни ли сте, че искате да премахнете<br><strong>' + name + '</strong> като класен ръководител?<br>' +
            '<small class="text-muted">Профилът на учителя няма да бъде изтрит.</small>'
        );

        pendingAction = function () {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success(data.message);
                    $('#confirmModal').modal('hide');
                    location.reload();
                },
                error: function () {
                    toastr.error('Възникна грешка. Моля, опитайте отново.');
                }
            });
        };

        $('#confirmModal').modal('show');
    });

    // Потвърждение
    $('#confirmModalBtn').on('click', function () {
        $('#confirmModal').modal('hide');
        if (typeof pendingAction === 'function') {
            pendingAction();
            pendingAction = null;
        }
    });

});
</script>
@endpush
