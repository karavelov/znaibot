@extends('main')

@section('content')
<div class="relative pt-12 max-w-4xl mx-auto">
    <!-- Бутон Начало (същия стил) -->
    <a href="{{ route('welcome') }}" class="absolute top-0 left-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition shadow-sm">
        <i class="fas fa-home mr-2 text-blue-600"></i> Начало
    </a>

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col h-[70vh]">
        <!-- Header на чата -->
        <div class="bg-blue-600 p-4 flex items-center shadow-md">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 mr-3">
                <i class="fas fa-robot text-xl"></i>
            </div>
            <div>
                <h2 class="text-white font-bold leading-none">ЗнаиБот AI</h2>
                <span class="text-blue-100 text-xs">BgGPT-v1.0 е на линия</span>
            </div>
        </div>

        <!-- Зона за съобщения -->
        <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
            <!-- Начално съобщение от AI -->
            <div class="flex justify-start">
                <div class="bg-white border border-gray-200 text-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                    Здравей, Иване! Аз съм твоят училищен асистент. С какво мога да ти помогна днес?
                </div>
            </div>
        </div>

        <!-- Индикатор за писане (скрит по подразбиране) -->
        <div id="typing-indicator" class="hidden px-6 py-2 text-xs text-gray-500 italic">
            ЗнаиБот мисли...
        </div>

        <!-- Форма за въпрос -->
        <div class="p-4 bg-white border-t">
            <form id="chat-form" class="flex space-x-3">
                @csrf
                <input type="text" id="user-input" 
                    class="flex-1 border border-gray-300 rounded-full px-5 py-3 focus:outline-none focus:border-blue-500 transition shadow-sm" 
                    placeholder="Напиши своя въпрос тук..." required>
                <button type="submit" 
                    class="bg-blue-600 text-white w-12 h-12 rounded-full hover:bg-blue-700 transition flex items-center justify-center shadow-lg">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript за комуникация с AI -->
<script>
document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const inputField = document.getElementById('user-input');
    const chatBox = document.getElementById('chat-box');
    const typingIndicator = document.getElementById('typing-indicator');
    const message = inputField.value;

    if (!message) return;

    // Добавяне на потребителското съобщение
    chatBox.innerHTML += `
        <div class="flex justify-end">
            <div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none shadow-md max-w-[80%]">
                ${message}
            </div>
        </div>
    `;
    
    inputField.value = '';
    typingIndicator.classList.remove('hidden');
    chatBox.scrollTop = chatBox.scrollHeight;

    try {
        const response = await fetch("{{ route('ai.ask') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ prompt: message })
        });

        const data = await response.json();
        
        // Добавяне на отговора от AI
        chatBox.innerHTML += `
            <div class="flex justify-start">
                <div class="bg-white border border-gray-200 text-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                    ${data.answer}
                </div>
            </div>
        `;
    } catch (error) {
        console.error("Грешка:", error);
    } finally {
        typingIndicator.classList.add('hidden');
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
</script>
@endsection