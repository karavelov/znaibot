@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редактиране на въпрос</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Редактиране на съществуващ въпрос от банката</p>
        </div>
        <a href="{{ route('admin.questions.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
        <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <!-- Въпрос (Column: question) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Въпрос</label>
                    <textarea name="question" required rows="4"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all resize-none">{{ old('question', $question->question) }}</textarea>
                    @error('question')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Клас (Column: klas) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Клас (1-12)</label>
                        <select name="klas" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all appearance-none cursor-pointer">
                            <option value="" disabled>Изберете клас</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('klas', $question->klas) == $i ? 'selected' : '' }}>
                                    {{ $i }}. Клас
                                </option>
                            @endfor
                        </select>
                        @error('klas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Точки (Column: points) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Точки</label>
                        <input type="number" name="points" value="{{ old('points', $question->points) }}" required min="1"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        @error('points')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Запази промените
                </button>
                <a href="{{ route('admin.questions.index') }}" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Откажи
                </a>
            </div>

        </form>
    </div>

</div>
@endsection