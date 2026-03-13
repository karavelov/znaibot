@extends('main')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] flex flex-col items-center justify-center p-6 relative overflow-hidden">
    
    <!-- Бутон "Назад" -->
    <a href="{{ route('home') }}" class="absolute top-8 left-8 inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Назад
    </a>

    <!-- Анимация и Текст -->
    <div class="flex flex-col items-center max-w-md w-full animate-fade-in">
        <div class="relative w-64 h-64 flex justify-center items-center mb-12">
            <div class="absolute inset-0 bg-blue-400/20 rounded-full animate-[ping_3s_linear_infinite]"></div>
            <div class="absolute inset-4 bg-blue-400/10 rounded-full animate-[ping_2s_linear_infinite]"></div>
            
            <div class="relative w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl shadow-blue-200/50 flex items-center justify-center border border-blue-50">
                <i class="fas fa-rss text-4xl text-blue-500 animate-pulse"></i>
            </div>

            <div class="absolute top-0 right-4 w-3 h-3 bg-blue-300 rounded-full"></div>
            <div class="absolute bottom-8 left-0 w-2 h-2 bg-blue-200 rounded-full"></div>
        </div>

        <div class="text-center space-y-4">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Готовност за сканиране</h2>
            <p class="text-gray-400 text-lg font-light leading-relaxed">
                Моля, доближете вашата <span class="text-blue-500 font-medium">карта или чип</span> до четеца на устройството.
            </p>
        </div>
    </div>

    <!-- Статус лента -->
    <div class="mt-20 w-full max-w-2xl bg-white/50 backdrop-blur-md border border-gray-100 p-8 rounded-[2.5rem] shadow-sm">
        <div class="flex items-center justify-center space-x-3 mb-8">
            <div class="h-[1px] w-8 bg-gray-200"></div>
            <span class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-bold">Очаква се сканиране</span>
            <div class="h-[1px] w-8 bg-gray-200"></div>
        </div>
        
        <div class="text-center text-gray-500 italic">
            Моля, изчакайте докато сканирате картата си.
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- START: DEBUG BUTTONS (С ФОРМИ ЗА FORCE LOGIN)           -->
    <!-- ======================================================= -->
    <div class="fixed bottom-6 right-6 flex flex-col gap-3 z-50">
        <div class="text-[10px] font-mono text-gray-400 text-right uppercase mb-1">Debug Login</div>
        
        <!-- Форма за Ученик -->
        <form action="{{ route('debug.login', ['role' => 'student']) }}" method="POST">
            @csrf
            <button type="submit" class="w-48 bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-50 font-bold py-2.5 px-4 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center justify-between group opacity-60 hover:opacity-100">
                <span class="text-xs">Влез като Ученик</span>
                <i class="fas fa-user-graduate"></i>
            </button>
        </form>

        <!-- Форма за Родител -->
        <form action="{{ route('debug.login', ['role' => 'parent']) }}" method="POST">
            @csrf
            <button type="submit" class="w-48 bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-50 font-bold py-2.5 px-4 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center justify-between group opacity-60 hover:opacity-100">
                <span class="text-xs">Влез като Родител</span>
                <i class="fas fa-user-friends"></i>
            </button>
        </form>
    </div>
    <!-- ======================================================= -->

</div>

<script>
function pollServer() {
    fetch("{{ route('check_scan') }}") 
        .then(res => {
            if (!res.ok) throw new Error("HTTP error " + res.status);
            return res.json();
        })
        .then(data => {
            // Проверяваме дали е намерен потребител
            if (data.found && data.role) {
                console.log("Card scanned: " + data.role);
                
                if (data.role === "student") {
                    window.location.href = "{{ route('student_home') }}";
                } else if (data.role === "parent") {
                    window.location.href = "{{ route('parent_home') }}";
                } else if (data.role === "teacher") {
                    // Ако имаш route за учители
                     window.location.href = "{{ route('home') }}"; // Или teacher_home
                } else {
                    // Fallback за други роли
                    window.location.href = "{{ route('home') }}";
                }
            }
        })
        .catch(err => console.log("Waiting for scan...", err));
}

// Проверява на всеки 1.5 секунди
setInterval(pollServer, 1500);
</script>

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