@extends('main')
@section('body_class', 'scroll-mode')

@section('content')
<div class="h-screen bg-[#FBFBFD] flex flex-col p-4 md:p-6 overflow-hidden">
    <a href="{{ route('student_home') }}" class="z-20 inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group mb-4 w-fit">
        <i class="fas fa-arrow-left mr-2 text-blue-500 group-hover:scale-110 transition-transform"></i> Назад
    </a>

    <div class="w-full bg-white overflow-hidden flex flex-col flex-1 min-h-0 animate-fade-in">
        <div class="px-8 py-6 bg-white/80 backdrop-blur-md border-b border-gray-50 flex items-center justify-between z-10">
            <div class="flex items-center">
                <div class="relative">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-robot text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">ЗнайБот AI</h2>
                    <div class="flex items-center text-xs text-gray-400 font-medium tracking-wide">
                        <span class="mr-1">Вашият училищен асистент</span>
                        <span class="w-1 h-1 bg-gray-300 rounded-full mx-1"></span>
                        <span class="text-green-500">Онлайн</span>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <span class="text-[10px] bg-gray-100 text-gray-500 px-3 py-1 rounded-full uppercase tracking-[0.1em] font-bold">Интелигентен асистент</span>
            </div>
        </div>

        <div id="chat-box" class="flex-1 relative bg-[#FDFDFE] min-h-0">
            <div id="messages-layer" class="h-full overflow-y-auto p-8 pb-10 space-y-6 scroll-smooth">
                <div class="flex justify-start animate-fade-in-up">
                    <div class="bg-white border border-gray-100 text-gray-700 p-5 rounded-[1.8rem] rounded-tl-none shadow-sm max-w-[85%] md:max-w-[70%] leading-relaxed">
                        Здравей! Аз съм твоят училищен асистент. С какво мога да ти помогна днес? 😊
                    </div>
                </div>
            </div>
        </div>

        <div id="typing-indicator" class="hidden px-8 py-3 bg-[#FDFDFE]">
            <div class="flex items-center space-x-2 text-gray-400 text-xs font-medium italic">
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                </div>
                <span>ZnaiBot мисли...</span>
            </div>
        </div>

        <div class="p-6 bg-white border-t border-gray-50 sticky bottom-0 z-30">
            <form id="chat-form" class="relative flex items-center gap-2">
                @csrf
                <input type="text" id="user-input" 
                    class="w-full bg-[#F5F5F7] border-none rounded-2xl px-6 py-4 pr-14 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 outline-none" 
                    placeholder="Задай въпрос..." required autocomplete="off">
                
                <button type="submit" id="chat-submit-btn"
                    class="absolute right-2 w-11 h-11 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300 flex items-center justify-center shadow-lg shadow-blue-200 active:scale-95">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    #messages-layer::-webkit-scrollbar { width: 5px; }
    #messages-layer::-webkit-scrollbar-track { background: transparent; }
    #messages-layer::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out forwards;
    }
</style>

<script>
const chatForm = document.getElementById('chat-form');
const inputField = document.getElementById('user-input');
const submitBtn = document.getElementById('chat-submit-btn');
const messagesLayer = document.getElementById('messages-layer');
const typingIndicator = document.getElementById('typing-indicator');

let isSubmitting = false;

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;
    return div.innerHTML;
}

function appendUserMessage(message) {
    messagesLayer.innerHTML += `
        <div class="flex justify-end animate-fade-in-up">
            <div class="bg-blue-600 text-white p-5 rounded-[1.8rem] rounded-tr-none shadow-lg shadow-blue-100 max-w-[85%] md:max-w-[70%] leading-relaxed">
                ${escapeHtml(message)}
            </div>
        </div>
    `;
    scrollToBottom();
}

function appendAiMessage(message) {
    messagesLayer.innerHTML += `
        <div class="flex justify-start animate-fade-in-up">
            <div class="bg-white border border-gray-100 text-gray-700 p-5 rounded-[1.8rem] rounded-tl-none shadow-sm max-w-[85%] md:max-w-[70%] leading-relaxed">
                ${escapeHtml(message)}
            </div>
        </div>
    `;
    scrollToBottom();
}

function scrollToBottom() {
    messagesLayer.scrollTop = messagesLayer.scrollHeight;
}

function setSubmitLocked(locked) {
    isSubmitting = locked;
    submitBtn.disabled = locked;
    inputField.disabled = locked;
    if (locked) {
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        typingIndicator.classList.remove('hidden');
    } else {
        submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        typingIndicator.classList.add('hidden');
        inputField.focus();
    }
}

async function sendMessage(message) {
    if (!message || isSubmitting) return;

    setSubmitLocked(true);
    appendUserMessage(message);
    inputField.value = '';

    try {
        const response = await fetch("{{ route('ai.ask') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();
        const aiMessage = data.answer || data.ai_response || 'Извинявай, но не успях да генерирам отговор.';
        appendAiMessage(aiMessage);
    } catch (error) {
        console.error('Error:', error);
        messagesLayer.innerHTML += `
            <div class="flex justify-start text-red-500 text-xs italic p-4">
                Грешка при връзка със сървъра.
            </div>
        `;
    } finally {
        setSubmitLocked(false);
    }
}

chatForm.addEventListener('submit', (e) => {
    e.preventDefault();
    sendMessage(inputField.value.trim());
});

document.addEventListener('click', (event) => {
    if (!chatForm.contains(event.target)) {
        inputField.blur();
    }
});

scrollToBottom();
</script>
@endsection