@extends('main')
@extends('keyboard')
@section('body_class', 'scroll-mode')
@section('content')

<div class="min-h-screen bg-[#FBFBFD] p-4 md:p-8">
    
    <!-- Header -->
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <a href="{{ route('parent_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-indigo-500"></i> Назад към панела
        </a>
        
        @if($user)
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4 bg-white p-1.5 pr-5 rounded-full border border-gray-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ $user->name }}</p>
                    <p class="text-[10px] text-indigo-500 font-medium uppercase tracking-wide">
                        {{ $user->klas_name ?? 'РОДИТЕЛ' }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Лява колона: Чат -->
        <div class="lg:col-span-4 flex flex-col space-y-6">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm flex-1 flex flex-col h-[600px]">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-map-signs text-indigo-500 text-2xl"></i>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Навигация</h2>
                <p class="text-xs text-gray-400 mb-4">Попитайте за стая, кабинет или място в училището.</p>

                <!-- Chat Scroller -->
                <div id="chat-scroller" class="flex-1 overflow-y-auto mb-6 pr-2 space-y-4 scrollbar-hide">
                    <div class="flex justify-start animate-fade-in">
                        <div class="bg-gray-100 p-4 rounded-2xl rounded-tl-none text-sm text-gray-700 max-w-[90%]">
                            Здравейте! Аз съм вашият навигатор. Коя стая търсите?
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="ai-loading" class="hidden mb-4 flex items-center gap-3 text-indigo-500 text-xs font-bold pl-2">
                    <div class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    </div>
                    Проверявам картата...
                </div>

                <!-- Form -->
                <form id="location-form" class="relative mt-auto">
                    @csrf
                    <input type="text" id="query-input" autocomplete="off" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 pr-12 text-sm text-gray-800 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-gray-400" 
                           placeholder="Напр: Къде е стая 305?">
                    <button type="submit" class="absolute right-2 top-2 w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center justify-center transition-colors shadow-lg shadow-indigo-200">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Дясна колона: Карта -->
        <div class="lg:col-span-8">
            <div id="map-wrapper" class="bg-white border border-gray-100 rounded-[2.5rem] p-4 shadow-sm h-full min-h-[600px] relative overflow-hidden flex items-center justify-center">
                
                <!-- State: Empty -->
                <div id="empty-map-state" class="text-center opacity-30">
                    <i class="fas fa-compass text-8xl mb-6 text-gray-300"></i>
                    <p class="font-bold uppercase tracking-widest text-xs text-gray-400">Картата ще се появи тук</p>
                </div>

                <!-- State: Active Map -->
                <div id="active-map" class="hidden relative w-full h-full cursor-crosshair bg-gray-50 rounded-[2rem] overflow-hidden border border-gray-100">
                    
                    <div class="absolute top-6 left-6 z-10 bg-white/90 backdrop-blur px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                        <p id="floor-name" class="text-xs font-black text-gray-800 uppercase tracking-tighter">ЕТАЖ</p>
                    </div>

                    <img id="floor-img" src="" class="w-full h-full object-contain">
                    
                    <div id="map-blip" class="absolute w-12 h-12 -ml-6 -mt-6 z-20 transition-all duration-700 ease-out" style="left: 50%; top: 50%;">
                        <div class="absolute inset-0 rounded-full bg-indigo-500 animate-ping opacity-50"></div>
                        <div class="relative w-12 h-12 bg-indigo-600 border-4 border-white rounded-full shadow-2xl flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-lg text-white"></i>
                        </div>
                        <!-- Tooltip -->
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] font-bold px-3 py-1 rounded-lg whitespace-nowrap shadow-lg">
                            ТУК
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addBubble(text, side) {
        const isUser = side === 'user';
        const containerClasses = isUser ? 'justify-end' : 'justify-start';
        const bubbleClasses = isUser 
            ? 'bg-indigo-600 text-white rounded-tr-none' 
            : 'bg-gray-100 text-gray-700 rounded-tl-none border border-gray-200';
            
        const html = `
        <div class="flex ${containerClasses} animate-fade-in">
            <div class="${bubbleClasses} p-4 rounded-2xl text-sm shadow-sm max-w-[85%] leading-relaxed">
                ${text}
            </div>
        </div>`;
        
        const scroller = document.getElementById('chat-scroller');
        scroller.insertAdjacentHTML('beforeend', html);
        scroller.scrollTop = scroller.scrollHeight;
    }

    document.getElementById('location-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('query-input');
        const query = input.value.trim();
        
        if(!query) return;
        
        addBubble(query, 'user');
        input.value = '';
        document.getElementById('ai-loading').classList.remove('hidden');

        try {
            // Изпращаме към новия Parent Navigation API Route
            const response = await fetch("{{ route('api.parent.navigation') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: query })
            });
            
            const data = await response.json();
            document.getElementById('ai-loading').classList.add('hidden');

            if(data.ok) {
                addBubble(data.answer, 'bot');
                
                if(data.location) {
                    updateMap(data.location);
                } else {
                    // Ако няма локация (напр. потребителят е питал за времето)
                    // Не скриваме картата, ако вече е показана, или оставяме празна
                }
            } else {
                addBubble(data.answer || "Възникна грешка.", 'bot');
            }
        } catch(e) {
            console.error(e);
            document.getElementById('ai-loading').classList.add('hidden');
            addBubble("Няма връзка със сървъра.", 'bot');
        }
    });

    function updateMap(loc) {
        document.getElementById('empty-map-state').classList.add('hidden');
        document.getElementById('active-map').classList.remove('hidden');
        
        // Превод на етажа
        let floorName = loc.floor;
        if(floorName === 'firstfloor') floorName = '1 ЕТАЖ';
        if(floorName === 'secondfloor') floorName = '2 ЕТАЖ';
        if(floorName === 'thirdfloor') floorName = '3 ЕТАЖ';
        if(floorName === 'forthfloor') floorName = '4 ЕТАЖ';

        document.getElementById('floor-img').src = `{{ asset('assets/') }}/${loc.floor}.jpg`;
        document.getElementById('floor-name').innerText = floorName;
        
        // Анимация на маркера
        const blip = document.getElementById('map-blip');
        // Първо леко мръдваме, за да се види анимацията
        setTimeout(() => {
            blip.style.left = loc.x + '%';
            blip.style.top = loc.y + '%';
        }, 100);
    }
</script>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
</style>
@endsection