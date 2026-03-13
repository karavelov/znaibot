@extends('main')
@section('body_class', 'scroll-mode')
@section('content')

<div class="min-h-screen bg-[#FBFBFD] p-4 md:p-8">
    <div class="max-w-7xl mx-auto flex items-center justify-between mb-8">
        <a href="{{ route('student_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-emerald-500"></i> Назад
        </a>
        
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4 bg-white p-1.5 pr-5 rounded-full border border-gray-100 shadow-sm">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=10b981&color=fff" class="w-10 h-10 rounded-full border-2 border-emerald-50">
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ $user->name }}</p>
                    <p class="text-[10px] text-emerald-500 font-medium">{{ $user->klas_name }} Клас</p>
                </div>
            </div>
        </div>
    </div>



    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-4 flex flex-col space-y-6">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm flex-1 flex flex-col">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-street-view text-emerald-500 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Навигатор</h2>
                <div id="chat-scroller" class="flex-1 overflow-y-auto mb-6 pr-2 min-h-[350px] space-y-4">
                    <div class="bg-emerald-50/50 p-4 rounded-2xl rounded-tl-none border border-emerald-100/30 text-sm text-emerald-800">
                        Кой учител търсиш?
                    </div>
                </div>
                <div id="ai-loading" class="hidden mb-4 flex items-center gap-3 text-emerald-500 text-xs font-bold">
                    <div class="flex gap-1"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce"></span></div>
                    Локализиране...
                </div>
                <form id="location-form" class="relative">
                    @csrf
                    <input type="text" id="query-input" autocomplete="off" class="kb-mini w-full bg-gray-50 border-none rounded-2xl px-5 py-4 pr-12 text-sm outline-none" placeholder="Къде е г-жа Иванова?">
                    <button type="submit" class="absolute right-2 top-2 w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center"><i class="fas fa-location-arrow text-xs"></i></button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div id="map-wrapper" class="bg-white border border-gray-100 rounded-[2.5rem] p-4 shadow-sm h-full min-h-[550px] relative overflow-hidden flex items-center justify-center">
                <div id="empty-map-state" class="text-center opacity-20">
                    <i class="fas fa-map-marked-alt text-8xl mb-4"></i>
                    <p class="font-bold uppercase tracking-widest">Картата се зарежда при отговор</p>
                </div>
                <div id="active-map" class="hidden relative w-full h-full cursor-crosshair">
                    <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-4 py-2 rounded-xl border border-gray-100">
                        <p id="floor-name" class="text-xs font-black text-gray-800 uppercase tracking-tighter"></p>
                    </div>
                    <img id="floor-img" src="" class="w-full h-full object-contain rounded-[1.5rem]" onclick="handleMapClick(event)">
                    <div id="map-blip" class="absolute w-8 h-8 -ml-4 -mt-4 z-20 pointer-events-none">
                        <div class="absolute inset-0 rounded-full bg-emerald-500 animate-ping opacity-50"></div>
                        <div class="relative w-8 h-8 bg-emerald-500 border-4 border-white rounded-full shadow-2xl flex items-center justify-center">
                            <i class="fas fa-user-tie text-[10px] text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('keyboard')

<script>
    let currentFloor = 'firstfloor';

    function handleMapClick(event) {
        if (!isDevMode) return;
        const rect = event.target.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;
        document.getElementById('map-blip').style.left = x.toFixed(2) + '%';
        document.getElementById('map-blip').style.top = y.toFixed(2) + '%';
        document.getElementById('dev-coords-display').value = `X: ${x.toFixed(2)}%, Y: ${y.toFixed(2)}%`;
        document.getElementById('dev-php-code').value = `'ROOM' => ['floor' => '${currentFloor}', 'x' => ${x.toFixed(2)}, 'y' => ${y.toFixed(2)}],`;
    }

    function addBubble(text, side) {
        const isUser = side === 'user';
        const html = `<div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-fade-in"><div class="${isUser ? 'bg-emerald-500 text-white' : 'bg-white border border-emerald-100 text-gray-700'} p-4 rounded-2xl ${isUser ? 'rounded-tr-none' : 'rounded-tl-none'} text-sm shadow-sm max-w-[90%]">${text}</div></div>`;
        const scroller = document.getElementById('chat-scroller');
        scroller.insertAdjacentHTML('beforeend', html);
        scroller.scrollTop = scroller.scrollHeight;
    }

    document.getElementById('location-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const query = document.getElementById('query-input').value.trim();
        if(!query) return;
        addBubble(query, 'user');
        document.getElementById('query-input').value = '';
        document.getElementById('ai-loading').classList.remove('hidden');

        try {
            const response = await fetch("{{ route('ai.teacher.find') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message: query })
            });
            const data = await response.json();
            document.getElementById('ai-loading').classList.add('hidden');
            if(data.ok) {
                addBubble(data.answer, 'bot');
                if(data.location) updateMap(data.location);
                if(data.tts_url) new Audio(data.tts_url).play().catch(()=>{});
            } else {
                addBubble(data.answer || "Грешка", 'bot');
            }
        } catch(e) {
            document.getElementById('ai-loading').classList.add('hidden');
            addBubble("Грешка при връзка", 'bot');
        }
    });

    function updateMap(loc) {
        document.getElementById('empty-map-state').classList.add('hidden');
        document.getElementById('active-map').classList.remove('hidden');
        document.getElementById('floor-img').src = `{{ asset('assets/') }}/${loc.floor}.jpg`;
        document.getElementById('floor-name').innerText = loc.floor.replace('floor', ' ЕТАЖ').toUpperCase();
        document.getElementById('map-blip').style.left = loc.x + '%';
        document.getElementById('map-blip').style.top = loc.y + '%';
    }
</script>
@endsection