@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Ново назначение</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Добавяне на предмет → учител</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8 max-w-xl">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                <!-- Предмет -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Предмет <span class="text-red-400">(*)</span>
                    </label>
                    <select name="subject_name"
                            class="subject-select w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">— Изберете предмет —</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}"
                                {{ old('subject_name') == $subject->name ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-400">
                        Нов предмет? Добавете го в
                        <a href="{{ route('admin.subject-names.create') }}" class="text-blue-500 hover:underline font-medium">Управление на предмети</a>.
                    </p>
                </div>

                <!-- Учител -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Учител <span class="text-red-400">(*)</span>
                    </label>
                    <select name="teacher_id"
                            class="teacher-select w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">— Изберете учител —</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Запази
                </button>
                <a href="{{ route('admin.subjects.index') }}"
                   class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Отказ
                </a>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.subject-select, .teacher-select').select2({
            theme: 'classic',
            width: '100%'
        });
    });
</script>
@endpush
