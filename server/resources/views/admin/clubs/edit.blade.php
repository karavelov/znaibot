@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редактиране на клуб</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $club->name }}</p>
        </div>
        <a href="{{ route('admin.clubs.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Two-column grid -->
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-6 items-start">

        {{-- Лява колона: Форма --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
            <form action="{{ route('admin.clubs.update', $club->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Текуща иконка -->
                    @if($club->icon)
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Текуща иконка</label>
                        <img src="{{ asset($club->icon) }}" class="w-20 h-20 rounded-2xl object-contain bg-gray-50 border border-gray-100 p-2" alt="Иконка">
                    </div>
                    @endif

                    <!-- Нова иконка -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Иконка / Лого на клуба</label>
                        <input type="file" name="icon" accept="image/*"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white transition-all file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    </div>

                    <!-- Име -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                            Име на клуб <span class="text-red-400">(*)</span>
                        </label>
                        <input type="text" name="name" value="{{ $club->name }}"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Брой членове -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Брой членове</label>
                        <input type="text" name="members" value="{{ $club->members }}"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    </div>

                    <!-- Галерия -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Галерия</label>
                        <select name="gallery_id"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                            <option value="">— Изберете галерия —</option>
                            @foreach ($galleries as $gallery)
                                <option value="{{ $gallery->id }}" {{ $club->gallery_id == $gallery->id ? 'selected' : '' }}>
                                    {{ $gallery->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Статус -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Статус</label>
                        <select name="status"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                            <option value="1" {{ $club->status == 1 ? 'selected' : '' }}>Публично</option>
                            <option value="0" {{ $club->status == 0 ? 'selected' : '' }}>Скрито</option>
                        </select>
                    </div>

                    <!-- За клуба -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">За клуба</label>
                        <textarea name="about" class="summernote w-full">{!! $club->about !!}</textarea>
                    </div>

                    <!-- Успехи -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Успехи</label>
                        <textarea name="achievements" class="summernote w-full">{!! $club->achievements !!}</textarea>
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                        Запази
                    </button>
                    <a href="{{ route('admin.clubs.index') }}"
                       class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Отказ
                    </a>
                </div>

            </form>
        </div>

        {{-- Дясна колона: Управление на ученици --}}
        <div class="space-y-6">

            {{-- Добавяне на ученик --}}
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-6">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Добавяне на ученик</h3>
                <div class="flex gap-2">
                    <select class="select2 flex-1 min-w-0 px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all" id="studentSelect">
                        <option value="">— Изберете ученик —</option>
                        @foreach($availableStudents as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    <button type="button" id="addStudentBtn"
                            class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 shrink-0">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-400">Показват се само ученици, които не са в клуба.</p>
            </div>

            {{-- Членове на клуба --}}
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
                <div class="px-6 pt-6 pb-4 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Членове на клуба</h3>
                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-black rounded-xl" id="memberCount">{{ $club->students->count() }}</span>
                </div>
                <div class="px-4 pb-4 space-y-1" id="members-list">
                    @forelse($club->students as $student)
                        <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all" id="member-{{ $student->id }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $student->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $student->email }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('admin.users.edit', $student->id) }}"
                                   class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-100 transition-all"
                                   title="Редактиране на профил">
                                    <i class="far fa-edit text-xs"></i>
                                </a>
                                <button type="button"
                                        class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center hover:bg-red-100 transition-all btn-remove-member"
                                        data-url="{{ route('admin.clubs.remove-student', [$club->id, $student->id]) }}"
                                        data-student-id="{{ $student->id }}"
                                        data-name="{{ $student->name }}"
                                        title="Премахване от клуба">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8" id="empty-msg">
                            <p class="text-sm text-gray-400">Няма добавени членове.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Confirm Remove Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 rounded-[2rem] shadow-2xl overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-6 px-8 justify-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto">
                    <i class="fas fa-exclamation-triangle fa-lg text-amber-500"></i>
                </div>
                <button type="button" class="close position-absolute" style="top:16px;right:20px;" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center px-8 pt-4 pb-2">
                <h5 class="text-lg font-black text-gray-900 mb-2">Премахване от клуба</h5>
                <p class="text-sm text-gray-500 mb-0" id="confirmModalBody"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-6 gap-3">
                <button type="button"
                        class="px-6 py-2.5 bg-white border border-gray-100 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95"
                        data-dismiss="modal">
                    Отказ
                </button>
                <button type="button" id="confirmModalBtn"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-2xl text-sm font-bold shadow-md shadow-red-500/20 transition-all active:scale-95">
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

    $('.select2').select2();

    var pendingAction = null;

    // ── Добавяне на ученик ──────────────────────────────────────────
    $('#addStudentBtn').on('click', function () {
        var userId = $('#studentSelect').val();
        if (!userId) {
            toastr.warning('Моля, изберете ученик!');
            return;
        }

        $.ajax({
            url: '{{ route('admin.clubs.add-student', $club->id) }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', user_id: userId },
            success: function (data) {
                toastr.success(data.message);

                // Премахни от dropdown-а
                $('#studentSelect option[value="' + userId + '"]').remove();
                $('#studentSelect').val('').trigger('change');

                // Премахни "Няма членове" съобщението
                $('#empty-msg').remove();

                // Добави новия ред в списъка
                var s = data.student;
                $('#members-list').append(
                    '<div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all" id="member-' + s.id + '">' +
                        '<div class="flex-1 min-w-0">' +
                            '<p class="text-sm font-bold text-gray-900 dark:text-white truncate">' + s.name + '</p>' +
                            '<p class="text-xs text-gray-400 truncate student-email">' + s.email + '</p>' +
                        '</div>' +
                        '<div class="flex items-center gap-1.5 shrink-0">' +
                            '<a href="' + s.edit_url + '" class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-100 transition-all" title="Редактиране на профил">' +
                                '<i class="far fa-edit text-xs"></i>' +
                            '</a>' +
                            '<button type="button" class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center hover:bg-red-100 transition-all btn-remove-member"' +
                                ' data-url="' + s.remove_url + '"' +
                                ' data-student-id="' + s.id + '"' +
                                ' data-name="' + s.name + '"' +
                                ' title="Премахване от клуба">' +
                                '<i class="fas fa-times text-xs"></i>' +
                            '</button>' +
                        '</div>' +
                    '</div>'
                );

                // Обнови брояч
                $('#memberCount').text(parseInt($('#memberCount').text()) + 1);
            },
            error: function (xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Възникна грешка.';
                toastr.error(msg);
            }
        });
    });

    // ── Премахване на ученик ────────────────────────────────────────
    $('body').on('click', '.btn-remove-member', function () {
        var btn       = $(this);
        var name      = btn.data('name');
        var url       = btn.data('url');
        var studentId = btn.data('student-id');

        $('#confirmModalBody').html(
            'Сигурни ли сте, че искате да премахнете<br>' +
            '<strong>' + name + '</strong> от клуба?<br>' +
            '<small class="text-gray-400">Профилът на ученика няма да бъде изтрит.</small>'
        );

        pendingAction = function () {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (data) {
                    toastr.success(data.message);
                    $('#confirmModal').modal('hide');

                    // Върни ученика в dropdown-а
                    var name = btn.data('name');
                    var email = $('#member-' + studentId + ' .student-email').text();
                    $('#studentSelect').append(
                        '<option value="' + studentId + '">' + name + ' (' + email + ')</option>'
                    );

                    // Премахни от списъка
                    $('#member-' + studentId).fadeOut(300, function () {
                        $(this).remove();
                        var count = parseInt($('#memberCount').text()) - 1;
                        $('#memberCount').text(count);
                        if ($('#members-list > div').length === 0) {
                            $('#members-list').append(
                                '<div class="text-center py-8" id="empty-msg"><p class="text-sm text-gray-400">Няма добавени членове.</p></div>'
                            );
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

    // ── Потвърди ────────────────────────────────────────────────────
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
