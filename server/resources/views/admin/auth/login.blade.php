{{-- Check if the user is already logged in. --}}
@php
    if (Auth::check() && Auth::user()->role === 'admin') {
        echo redirect()->route('admin.dashboard')->send();
        exit;
    }
@endphp

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Административен панел — Вход</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: {} } }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">

        <!-- Лого / Заглавие -->
        <div class="flex flex-col items-center mb-8 transition-all duration-700"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'">
            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30 mb-4">
                <span class="w-3 h-3 rounded-full bg-white"></span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900">Административен панел</h1>
            <p class="text-sm text-gray-400 font-medium mt-1">Влезте в своя акаунт</p>
        </div>

        <!-- Карта за вход -->
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm p-8 transition-all duration-700 delay-100"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-100 text-red-600 text-sm font-medium rounded-2xl px-4 py-3 flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Имейл -->
                <div class="mb-5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Имейл</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 pointer-events-none">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input id="email" type="email" name="email" tabindex="1" autofocus
                               value="{{ old('email') }}"
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all"
                               placeholder="admin@example.com">
                    </div>
                </div>

                <!-- Парола -->
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-400">Парола</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-600 transition-colors">
                            Забравена парола?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 pointer-events-none">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input id="password" type="password" name="password" tabindex="2"
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Запомни ме -->
                <div class="mb-6 flex items-center gap-3">
                    <input type="checkbox" name="remember" id="remember-me" tabindex="3"
                           class="w-4 h-4 rounded-lg accent-blue-600 cursor-pointer">
                    <label for="remember-me" class="text-sm font-medium text-gray-500 cursor-pointer select-none">Запомни ме</label>
                </div>

                <!-- Бутон за вход -->
                <button type="submit" tabindex="4"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Вход
                </button>

            </form>
        </div>

    </div>

</body>
</html>
</body>
</html>