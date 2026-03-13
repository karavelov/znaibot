@extends('main')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <a href="{{ route('logout') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group">
            <i class="fas fa-right-from-bracket mr-2 text-blue-500 group-hover:scale-110 transition-transform"></i> Излизане
        </a>

        <div class="inline-flex items-center bg-white border border-gray-100 px-5 py-2.5 rounded-2xl shadow-sm animate-fade-in">
            <div class="relative flex mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
            </div>
            <span class="text-sm font-medium text-gray-700 italic">
                Успешен вход: <span class="text-gray-900 font-bold ml-1">{{ $user->name }} ({{ $user->klas_name }} клас)</span>
            </span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mb-12 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F]">
            Здравей, <span class="text-blue-600">{{ $user->name }}</span>! <br class="hidden md:block">
            <span class="text-gray-400 font-light">Какво търсиш днес?</span>
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 max-w-7xl mx-auto">
        
          <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-2 flex flex-col justify-between animate-fade-in-up [animation-delay:100ms]">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-comment-dots text-xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Задай въпрос</h3>
                <p class="text-gray-400 mt-3 text-sm leading-relaxed">Попитай нещо интересно ЗнайБот.</p>
            </div>
            <a href="{{ route('ai.chat') }}" class="mt-10 block w-full py-4 bg-blue-600 text-white text-center font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                Към чата
            </a>
        </div>

        <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-2 flex flex-col justify-between animate-fade-in-up [animation-delay:100ms]">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-brain text-xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Викторина</h3>
                <p class="text-gray-400 mt-3 text-sm leading-relaxed">Провери знанията си и спечели точки за деня.</p>
            </div>
            <a href="{{ route('student_quiz')}}" class="mt-10 w-full py-4 bg-blue-50 text-blue-600 text-center font-bold rounded-2xl hover:bg-blue-500 hover:text-white transition-all">
                Започни игра
            </a>
        </div>

        <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-2 flex flex-col justify-between animate-fade-in-up [animation-delay:200ms]">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-search-location text-xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Намери учител</h3>
                <p class="text-gray-400 mt-3 text-sm leading-relaxed">Виж графика и кабинета на учител.</p>
            </div>
            <a href="{{route('student_findteacher')}}" class="mt-10 w-full py-4 bg-blue-50 text-center text-blue-600 font-bold rounded-2xl hover:bg-blue-500 hover:text-white transition-all">
                Търси учител
            </a>
        </div>

        <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-2 flex flex-col justify-between animate-fade-in-up [animation-delay:300ms]">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-box-open text-xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Изгубени вещи</h3>
                <p class="text-gray-400 mt-3 text-sm leading-relaxed">Провери дали някой е намерил твоя вещ в сградата.</p>
            </div>
            <a href="{{route('student_lostthings')}}" class="mt-10 w-full py-4 bg-blue-50 text-center text-blue-600 font-bold rounded-2xl hover:bg-blue-500 hover:text-white transition-all">
                Провери
</a>
        </div>

        <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:-translate-y-2 flex flex-col justify-between animate-fade-in-up [animation-delay:400ms]">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-gamepad text-xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Игри</h3>
                <p class="text-gray-400 mt-3 text-sm leading-relaxed">Играй Rock, Paper, Scissors със ZnaiBot AI камерата.</p>
            </div>
            <a href="{{ route('student_games') }}" class="mt-10 w-full py-4 bg-blue-50 text-center text-blue-600 font-bold rounded-2xl hover:bg-blue-500 hover:text-white transition-all">
                Към игрите
            </a>
        </div>

    </div>

    <div class="mt-16 text-center animate-fade-in">
        <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">ЗНАЙБОТ • УЧИЛИЩЕН АСИСТЕНТ</p>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s ease-out forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
</style>
@endsection