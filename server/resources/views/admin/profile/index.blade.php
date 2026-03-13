@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Профил</h1>
        <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Управление на вашия акаунт</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        <!-- Редактиране на профил -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
            <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-6">Редактиране на профил</h2>
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf

                @php
                $input = 'w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all';
                $label = 'block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2';
                @endphp

                <div class="space-y-5">

                    <!-- Текуща снимка -->
                    @if(Auth::user()->image)
                    <div class="flex items-center gap-4">
                        <img src="{{ asset(Auth::user()->image) }}"
                             class="w-20 h-20 rounded-2xl object-cover object-center border border-gray-100 dark:border-gray-800"
                             alt="Профилна снимка">
                        <div>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Нова снимка -->
                    <div>
                        <label class="{{ $label }}">Профилна снимка</label>
                        <input type="file" name="image" accept="image/*"
                               class="{{ $input }} file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    </div>

                    <!-- Ime -->
                    <div>
                        <label class="{{ $label }}">Име</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                               class="{{ $input }}" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="{{ $label }}">Имейл</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}"
                               class="{{ $input }}" required>
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                        Запази промените
                    </button>
                </div>

            </form>
        </div>

        <!-- Смяна на парола -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
            <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-6">Смяна на парола</h2>
            <form method="POST" action="{{ route('admin.password.update') }}">
                @csrf

                <div class="space-y-5">

                    <div>
                        <label class="{{ $label }}">Текуща парола</label>
                        <input type="password" name="current_password" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Нова парола</label>
                        <input type="password" name="password" class="{{ $input }}">
                    </div>

                    <div>
                        <label class="{{ $label }}">Повтори парола</label>
                        <input type="password" name="password_confirmation" class="{{ $input }}">
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                        Смени паролата
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection

