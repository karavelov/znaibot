@extends('main')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] flex flex-col items-center justify-center p-6 relative overflow-hidden">

    <a href="{{ route('home') }}" class="absolute top-8 left-8 inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Назад
    </a>

    <div class="w-full max-w-lg animate-fade-in">
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-10 shadow-sm">
            <div class="w-20 h-20 rounded-3xl bg-blue-50 flex items-center justify-center mb-8 mx-auto">
                <i class="fas fa-user-lock text-3xl text-blue-500"></i>
            </div>

            <div class="text-center space-y-3 mb-8">
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Вход</h2>
                <p class="text-gray-400 text-base font-light leading-relaxed">
                    Въведете вашите данни за достъп.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('scan.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="login" class="block text-sm font-medium text-gray-600 mb-2">Имейл или потребителско име</label>
                    <input
                        id="login"
                        name="login"
                        type="text"
                        value="{{ old('login') }}"
                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300"
                        placeholder="Въведете имейл или потребителско име"
                        required
                        autocomplete="username"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-2">Парола</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300"
                        placeholder="Въведете парола"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3.5 text-white font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-right-to-bracket"></i>
                    Вход
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-gray-400 uppercase tracking-[0.25em] font-bold">
                ЗнайБот | Училищен асистент
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }
</style>
@endsection