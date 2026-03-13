@extends('main')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative">
    <div class="max-w-7xl mx-auto gap-6 mb-12">
        <a href="{{ route('logout') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group" style="margin-bottom: 20px">
            <i class="fas fa-right-from-bracket mr-2 text-blue-500 group-hover:scale-110 transition-transform"></i> Излизане
        </a>

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
            <div class="animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F]">
                    Здравейте, <span class="text-blue-600">{{ $parent->first_name ?? $parent->name ?? 'Родител' }}</span>
                </h1>
                <p class="text-gray-500 mt-2 text-lg font-light">
                    Информация за учебния ден на 
                    <span class="font-medium text-blue-700">
                        {{ $child->name ?? 'Вашето дете' }} 
                        @if(isset($child->klas_name))
                            <span class="text-sm bg-blue-100 text-blue-800 px-2 py-0.5 rounded ml-1">{{ $child->klas_name }} клас</span>
                        @endif
                    </span>
                </p>
            </div>

            <div class="inline-flex items-center self-start bg-white border border-gray-100 px-4 py-2 rounded-full shadow-sm">
                <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse mr-3"></div>
                <span class="text-sm font-medium text-gray-600 tracking-wide">Родителски достъп активен</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Класна стая -->
            <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1 flex flex-col justify-between h-full">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-map-marked-alt text-xl text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 tracking-tight">Класната стая</h3>
                    <p class="text-gray-400 mt-3 leading-relaxed">Намери точното място, където детето ти има час в момента.</p>
                </div>
                <a href="{{ route('parent_classroom') }}" class="mt-8 w-full py-4 bg-[#F5F5F7] text-gray-700 text-center font-semibold rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300">
                    Покажи на картата
                </a>
            </div>

            <!-- Събития
            <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1 flex flex-col justify-between h-full">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-calendar-day text-xl text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 tracking-tight">Събития</h3>
                    <p class="text-gray-400 mt-3 leading-relaxed">Предстоящи родителски срещи, празници и важни дати.</p>
                </div>
                <a href="{{ route('parent_events') }}" class="mt-8 w-full py-4 bg-[#F5F5F7] text-center text-gray-700 font-semibold rounded-2xl hover:bg-blue-500 hover:text-white transition-all duration-300">
                    Виж календар
                </a>
            </div> -->

            <!-- Упътване -->
            <div class="group relative bg-white border border-gray-100 rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-gray-200/50 hover:-translate-y-1 flex flex-col justify-between h-full">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-location-arrow text-xl text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-blue-800 tracking-tight">Упътване</h3>
                    <p class="text-blue-400 mt-3 leading-relaxed">Интелигентен маршрут до всяка точка в сградата.</p>
                </div>
                <a href="{{ route('parent.navigation') }}" class="mt-8 w-full py-4 bg-[#F5F5F7] text-center text-gray-700 font-semibold rounded-2xl hover:bg-blue-500 hover:text-white transition-all duration-300">
                    Старт навигация
                </a>
            </div>

        </div>

        <!-- Динамичен AI статус банер -->
        <a href="{{ route('parent_classroom') }}" class="block mt-12 p-8 bg-blue-50 rounded-[2.5rem] flex items-center justify-between border border-blue-100/50 hover:bg-blue-100 transition-colors cursor-pointer group">
            <div class="flex items-center space-x-4">
                <div class="bg-white p-3 rounded-xl shadow-sm italic font-serif text-blue-600 font-bold">i</div>
                <p id="ai-status-text" class="text-blue-800 font-medium">ZnaiBot зарежда учебния график...</p>
            </div>
            <i class="fas fa-chevron-right text-blue-300 group-hover:text-blue-500 transition-colors"></i>
        </a>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Автоматично извикване на RAG системата, за да покажем текущия статус
        fetch('{{ route("api.student.location") }}')
            .then(response => response.json())
            .then(data => {
                const statusText = document.getElementById('ai-status-text');
                
                if (data.ok) {
                    if (data.status === 'free') {
                        statusText.innerHTML = `В момента: <span class="font-bold">${data.subject}</span>`;
                    } else {
                        statusText.innerHTML = `В момента: <span class="font-bold">${data.subject}</span>, кабинет <span class="font-bold">${data.room}</span> (до ${data.end_time})`;
                    }
                } else {
                    statusText.innerText = "Графикът в момента не е наличен.";
                }
            })
            .catch(error => {
                console.error('Error fetching schedule:', error);
                document.getElementById('ai-status-text').innerText = "Неуспешна връзка с училищния график.";
            });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }
</style>
@endsection