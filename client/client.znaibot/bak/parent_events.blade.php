@extends('main')
@section('body_class', 'scroll-mode')
@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    
    <!-- ГОРНА ЧАСТ -->
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="animate-fade-in">
            <a href="{{ route('parent_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 transition-all duration-300 group shadow-sm mb-4">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-indigo-500"></i> Назад
            </a>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F]">
                Събития <span class="text-indigo-500">.</span>
            </h1>
            <p class="text-gray-400 mt-2 text-lg font-light tracking-wide">Важни дати и родителски срещи</p>
        </div>

        <div class="hidden md:flex items-center space-x-4">
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Днес е</p>
                <p class="text-lg font-bold text-gray-800 italic">12 Октомври, 2023</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500">
                <i class="far fa-calendar-check text-xl"></i>
            </div>
        </div>
    </div>

    <!-- ОСНОВЕН GRID -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- ЛЯВА КОЛОНА: Списък със събития (5/12) -->
        <div class="lg:col-span-5 space-y-4 overflow-y-auto max-h-[700px] pr-2 custom-scrollbar animate-fade-in-left">
            
            <!-- Събитие 1 -->
            <div onclick="updateMap(42.6977, 23.3219, 'Зала за тържества')" class="group cursor-pointer bg-white border border-gray-100 rounded-[2rem] p-6 transition-all duration-300 hover:shadow-xl hover:shadow-indigo-100/50 hover:-translate-y-1 active:scale-95 border-l-4 border-l-indigo-500">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Родителска среща</span>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800 leading-none">18:30</p>
                        <p class="text-[10px] text-gray-400 font-medium italic">Утре</p>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Обща среща на 10-тите класове</h3>
                <p class="text-gray-400 text-sm font-light mb-6 line-clamp-1">Обсъждане на предстоящи изпити и екскурзии.</p>
                
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-gray-700 font-semibold bg-gray-50 px-3 py-1.5 rounded-xl">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2 text-xs"></i> Зала 302
                    </div>
                    <span class="text-indigo-500 font-bold group-hover:mr-2 transition-all">Виж на картата <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </div>

            <!-- Събитие 2 -->
            <div onclick="updateMap(42.6980, 23.3225, 'Актова зала')" class="group cursor-pointer bg-white border border-gray-100 rounded-[2rem] p-6 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-100/50 hover:-translate-y-1 active:scale-95 border-l-4 border-l-emerald-500">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Тържество</span>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800 leading-none">10:00</p>
                        <p class="text-[10px] text-gray-400 font-medium italic">15 Окт</p>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Празник на есента</h3>
                <p class="text-gray-400 text-sm font-light mb-6 line-clamp-1">Ученически концерт и изложба във фоайето.</p>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-gray-700 font-semibold bg-gray-50 px-3 py-1.5 rounded-xl">
                        <i class="fas fa-map-marker-alt text-emerald-500 mr-2 text-xs"></i> Актова зала
                    </div>
                    <span class="text-emerald-500 font-bold group-hover:mr-2 transition-all">Виж на картата <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </div>

            <!-- Събитие 3 -->
            <div onclick="updateMap(42.6975, 23.3215, 'Кабинет по Химия')" class="group cursor-pointer bg-white border border-gray-100 rounded-[2rem] p-6 transition-all duration-300 hover:shadow-xl hover:shadow-amber-100/50 hover:-translate-y-1 active:scale-95 border-l-4 border-l-amber-500">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-bold uppercase tracking-widest">Отворени врати</span>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800 leading-none">14:00</p>
                        <p class="text-[10px] text-gray-400 font-medium italic">20 Окт</p>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Демонстрация на AI Лаб</h3>
                <p class="text-gray-400 text-sm font-light mb-6 line-clamp-1">Запознайте се с новите технологии в обучението.</p>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-gray-700 font-semibold bg-gray-50 px-3 py-1.5 rounded-xl">
                        <i class="fas fa-map-marker-alt text-amber-500 mr-2 text-xs"></i> Кабинет 104
                    </div>
                    <span class="text-amber-500 font-bold group-hover:mr-2 transition-all">Виж на картата <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </div>

        </div>

        <!-- ДЯСНА КОЛОНА: Карта (7/12) -->
        <div class="lg:col-span-7 animate-fade-in-right">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-4 shadow-[0_20px_50px_rgba(0,0,0,0.02)] h-full min-h-[500px] flex flex-col sticky top-12">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 mb-4">
                    <div>
                        <h3 id="target-room" class="text-lg font-bold text-gray-800 leading-none mb-1">Изберете събитие</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Навигация в сградата</p>
                    </div>
                    <div class="bg-indigo-600 text-white p-2.5 rounded-xl shadow-lg shadow-indigo-100">
                        <i class="fas fa-route"></i>
                    </div>
                </div>

                <!-- Leaflet Контейнер -->
                <div id="events-map" class="flex-1 rounded-[2rem] z-0 overflow-hidden border border-gray-50"></div>
            </div>
        </div>

    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let currentMarker;

    document.addEventListener('DOMContentLoaded', function() {
        // Инициализиране на картата
        map = L.map('events-map', { zoomControl: false }).setView([42.6977, 23.3219], 18);

        L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        // Първоначален маркер
        updateMap(42.6977, 23.3219, 'Зала 302');
    });

    function updateMap(lat, lng, roomName) {
        // Обновяване на заглавието над картата
        document.getElementById('target-room').innerText = roomName;

        // Центриране на картата
        map.flyTo([lat, lng], 19, { animate: true, duration: 1.5 });

        // Премахване на стар маркер
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Кастъм маркер
        var eventIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="flex items-center justify-center">
                    <div class="w-10 h-10 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-2xl border-4 border-white animate-bounce">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        currentMarker = L.marker([lat, lng], {icon: eventIcon}).addTo(map)
            .bindPopup(`<b class="font-sans">${roomName}</b>`)
            .openPopup();
    }
</script>

<style>
    @keyframes fade-in-left { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fade-in-right { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    .animate-fade-in-left { animation: fade-in-left 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .animate-fade-in-right { animation: fade-in-right 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    
    .leaflet-container { background: #f8fafc; border-radius: 2rem; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
</style>
@endsection