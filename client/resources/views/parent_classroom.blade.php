@extends('main')
@section('body_class', 'scroll-mode')
@section('content')

<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    
    <!-- ГОРНА ЧАСТ: Навигация -->
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <a href="{{ route('parent_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 transition-all duration-300 group shadow-sm">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-indigo-500"></i> Назад към панела
        </a>

        <!-- Live Status Chip -->
        <div id="live-status-chip" class="inline-flex items-center bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-full shadow-sm">
            <span class="relative flex h-2 w-2 mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
            </span>
            <span class="text-xs font-bold text-indigo-700 uppercase tracking-widest">Свързване...</span>
        </div>
    </div>

    <!-- ОСНОВЕН BENTO GRID -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- ЛЯВА КОЛОНА: Информация за часа (1/3) -->
        <div class="space-y-6 animate-fade-in-left">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.02)] relative overflow-hidden">
                
                <!-- Loading Spinner -->
                <div id="loading-overlay" class="absolute inset-0 bg-white z-20 flex flex-col items-center justify-center transition-opacity duration-300">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                    <p class="text-gray-400 text-sm font-medium animate-pulse">Локализиране на ученика...</p>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center mb-8">
                    <i class="fas fa-graduation-cap text-xl text-indigo-500"></i>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight mb-2">Текущ статус</h2>
                <p class="text-gray-400 font-light mb-8">
                    Ученик: <span id="student-name-ui" class="text-gray-700 font-medium text-sm">...</span> 
                    <span id="student-class-ui" class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded ml-1"></span>
                </p>

                <!-- Детайли за часа -->
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-50">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold mb-1">Предмет / Статус</p>
                        <p id="subject-ui" class="text-xl font-bold text-gray-800 italic">...</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-50">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold mb-1">Преподавател</p>
                        <p id="teacher-ui" class="text-lg font-semibold text-gray-800">...</p>
                    </div>

                    <div id="room-card-ui" class="p-4 bg-gray-100 rounded-2xl shadow-sm transition-colors duration-500">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-1">Кабинет</p>
                        <p id="room-ui" class="text-2xl font-black text-gray-800 tracking-widest">-</p>
                    </div>
                </div>

                <!-- Времеви индикатор -->
                <div class="mt-8 flex items-center justify-between text-sm">
                    <span class="text-gray-400 font-medium">Край на часа:</span>
                    <span id="end-time-ui" class="text-gray-800 font-bold bg-gray-100 px-3 py-1 rounded-lg">-</span>
                </div>
                
                <div id="ai-message" class="mt-4 text-xs text-emerald-600 font-medium bg-emerald-50 p-3 rounded-xl hidden border border-emerald-100"></div>
            </div>
        </div>

        <!-- ДЯСНА КОЛОНА: Карта (2/3) -->
        <div class="lg:col-span-2 animate-fade-in-up">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-4 shadow-[0_20px_50px_rgba(0,0,0,0.02)] h-full min-h-[500px] flex flex-col relative overflow-hidden">
                
                <!-- Map Loading -->
                <div id="map-loading-overlay" class="absolute inset-0 bg-gray-50/80 z-20 rounded-[2rem] flex flex-col items-center justify-center backdrop-blur-sm transition-opacity duration-300">
                    <i class="fas fa-map text-6xl text-gray-300 mb-4 animate-pulse"></i>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Зареждане на картата...</p>
                </div>

                <div class="flex items-center justify-between px-6 py-4 z-10">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Локация на кабинета</h3>
                    <div id="map-status-ui" class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-3 py-1 rounded-full border border-emerald-100 hidden">
                        ОТКРИТА ЛОКАЦИЯ
                    </div>
                </div>

                <!-- 1. АКТИВНА КАРТА -->
                <div id="active-map" class="relative w-full h-full flex-1 rounded-[2rem] overflow-hidden hidden border border-gray-50 bg-gray-50">
                    
                    <!-- Етикет за етажа -->
                    <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                        <p id="floor-name" class="text-xs font-black text-gray-800 uppercase tracking-tighter">ЕТАЖ</p>
                    </div>

                    <!-- Снимката -->
                    <img id="floor-img" src="" class="w-full h-full object-contain" alt="Карта на етажа">
                    
                    <!-- Маркер -->
                    <div id="map-blip" class="absolute w-10 h-10 -ml-5 -mt-5 z-20 transition-all duration-700 ease-out" style="left: 50%; top: 50%;">
                        <div class="absolute inset-0 rounded-full bg-indigo-500 animate-ping opacity-50"></div>
                        <div class="relative w-10 h-10 bg-indigo-600 border-4 border-white rounded-full shadow-2xl flex items-center justify-center">
                            <i class="fas fa-user-graduate text-xs text-white"></i>
                        </div>
                        <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg whitespace-nowrap shadow-lg">
                            ТУК Е
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                        </div>
                    </div>
                </div>
                
                <!-- 2. СЪОБЩЕНИЕ ЗА ЛИПСВАЩА КАРТА -->
                <div id="empty-map-state" class="absolute inset-0 flex flex-col items-center justify-center text-center opacity-40 hidden">
                    <i class="fas fa-eye-slash text-6xl mb-4 text-gray-400"></i>
                    <p class="font-bold uppercase tracking-widest text-xs text-gray-500">Няма данни за локация</p>
                    <p class="text-[10px] text-gray-400 mt-2 max-w-xs mx-auto">Ученикът е или в междучасие, или е в стая без картографирани координати.</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("🚀 JS стартира. Изпращам заявка към API...");

        fetch('{{ route("api.student.location") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log("📦 ПОЛУЧЕНИ ДАННИ:", data);

            // Скриваме всички лоудъри
            document.getElementById('loading-overlay').style.opacity = '0';
            setTimeout(() => document.getElementById('loading-overlay').style.display = 'none', 300);
            
            document.getElementById('map-loading-overlay').style.opacity = '0';
            setTimeout(() => document.getElementById('map-loading-overlay').style.display = 'none', 300);

            // --- ОБРАБОТКА НА ГРЕШКИ ---
            if(!data.ok) {
                console.error("❌ Грешка от API:", data.message);
                document.getElementById('subject-ui').innerText = 'Грешка';
                document.getElementById('teacher-ui').innerText = data.message;
                document.getElementById('empty-map-state').classList.remove('hidden');
                return;
            }

            // --- ПОПЪЛВАНЕ НА ТЕКСТОВИ ДАННИ ---
            document.getElementById('student-name-ui').innerText = data.student_name;
            document.getElementById('student-class-ui').innerText = data.klas_name;
            document.getElementById('subject-ui').innerText = data.subject;
            document.getElementById('teacher-ui').innerText = data.teacher;
            document.getElementById('end-time-ui').innerText = data.end_time;
            
            // Показваме съобщението от AI
            if (data.answer) {
                const msgBox = document.getElementById('ai-message');
                msgBox.innerText = data.answer;
                msgBox.classList.remove('hidden');
            }

            // --- ЛОГИКА ЗА КАРТАТА ---
            const hasLocation = (data.location && data.location.x && data.location.y);
            console.log("📍 Има ли локация?", hasLocation ? "ДА" : "НЕ");

            if (hasLocation) {
                // 1. Показваме картата
                document.getElementById('active-map').classList.remove('hidden');
                document.getElementById('empty-map-state').classList.add('hidden');
                document.getElementById('map-status-ui').classList.remove('hidden');

                // 2. Оцветяваме картата с информацията в лилаво (активен час)
                document.getElementById('room-card-ui').classList.remove('bg-gray-100');
                document.getElementById('room-card-ui').classList.add('bg-indigo-600', 'shadow-lg', 'shadow-indigo-100');
                document.getElementById('room-ui').classList.replace('text-gray-800', 'text-white');
                
                // 3. Зареждаме снимката
                // Пътят: /assets/firstfloor.jpg (примерно)
                const imgPath = `{{ asset('assets/') }}/${data.location.floor}.jpg`;
                console.log("🖼 Зареждане на снимка:", imgPath);
                document.getElementById('floor-img').src = imgPath;
                
                // 4. Текст на етажа
                let floorBg = data.location.floor;
                if(floorBg === 'firstfloor') floorBg = '1 ЕТАЖ';
                if(floorBg === 'secondfloor') floorBg = '2 ЕТАЖ';
                if(floorBg === 'thirdfloor') floorBg = '3 ЕТАЖ';
                if(floorBg === 'forthfloor') floorBg = '4 ЕТАЖ';
                
                document.getElementById('floor-name').innerText = floorBg;
                document.getElementById('room-ui').innerHTML = `${data.room} <span class="text-xs font-normal opacity-70 ml-2">${floorBg}</span>`;

                // 5. Местим точката
                setTimeout(() => {
                    const blip = document.getElementById('map-blip');
                    blip.style.left = data.location.x + '%';
                    blip.style.top = data.location.y + '%';
                    console.log(`📍 Маркер преместен на: X=${data.location.x}%, Y=${data.location.y}%`);
                }, 100);

                // Статус чип
                document.getElementById('live-status-chip').innerHTML = `
                <span class="relative flex h-2 w-2 mr-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                </span>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">В ЧАС</span>`;

            } else {
                // НЯМА ЛОКАЦИЯ (Свободен или непозната стая)
                console.warn("⚠ Няма координати за тази стая или ученикът е свободен.");
                
                document.getElementById('active-map').classList.add('hidden');
                document.getElementById('empty-map-state').classList.remove('hidden');
                
                // Визуално променяме картата на сива
                document.getElementById('room-ui').innerText = data.room || '-';
                document.getElementById('live-status-chip').innerHTML = '<span class="text-xs font-bold text-gray-500 uppercase tracking-widest">НЯМА АКТИВЕН ЧАС</span>';
            }

        })
        .catch(error => {
            console.error("🔥 ГРЕШКА В JS:", error);
            document.getElementById('loading-overlay').style.display = 'none';
            document.getElementById('empty-map-state').classList.remove('hidden');
        });
    });
</script>

<style>
    @keyframes fade-in-left {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-left { animation: fade-in-left 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
</style>
@endsection