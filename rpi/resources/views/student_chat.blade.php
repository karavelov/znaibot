@extends('main')
@include('keyboard')
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
            <div id="messages-layer" class="h-full overflow-y-auto p-8 pb-28 space-y-6 scroll-smooth">
                <div class="flex justify-start animate-fade-in-up">
                    <div class="bg-white border border-gray-100 text-gray-700 p-5 rounded-[1.8rem] rounded-tl-none shadow-sm max-w-[85%] md:max-w-[70%] leading-relaxed">
                        Здравей! Аз съм твоят училищен асистент. С какво мога да ти помогна днес? 😊
                    </div>
                </div>
            </div>
            <div id="voice-mode-panel" class="hidden absolute inset-0 z-20 bg-[#FDFDFE] items-center justify-center p-8 flex-col gap-6">
                <div id="voice-sphere" class="voice-orb idle"></div>
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
                    class="w-full bg-[#F5F5F7] border-none rounded-2xl px-6 py-4 pr-40 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 outline-none" 
                    placeholder="Задай въпрос..." required autocomplete="off">

                <div id="mic-mode-menu" class="hidden absolute right-[3.9rem] bottom-[3.25rem] z-30 bg-white border border-gray-100 rounded-2xl shadow-lg p-2 w-48">
                    <button type="button" id="mode-dictation"
                        class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold tracking-wide text-gray-700 hover:bg-gray-100 transition-colors">
                        Транскрипция
                    </button>
                    <button type="button" id="mode-conversation"
                        class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold tracking-wide text-gray-700 hover:bg-gray-100 transition-colors">
                        Гласов разговор
                    </button>
                </div>

                <button type="button" id="speaker-toggle"
                    class="absolute right-28 w-11 h-11 bg-gray-200 text-gray-500 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center justify-center active:scale-95"
                    aria-label="Включи или изключи говорителя" title="Говорител">
                    <i class="fas fa-volume-up text-sm"></i>
                </button>

                <button type="button" id="mic-toggle"
                    class="absolute right-[3.9rem] w-11 h-11 bg-gray-200 text-gray-500 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center justify-center active:scale-95"
                    aria-label="Слушай от микрофона" title="Микрофон">
                    <i class="fas fa-microphone text-sm"></i>
                </button>
                
                <button type="submit" id="chat-submit-btn"
                    class="absolute right-2 w-11 h-11 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300 flex items-center justify-center shadow-lg shadow-blue-200 active:scale-95">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
            <div class="mt-1 text-center">
                <span id="stt-session-id" class="text-[10px] text-gray-400 tracking-wide">ID на сесия: -</span>
            </div>
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

    @keyframes black-hole {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes black-hole-core {
        0% { transform: scale(0.9); opacity: 0.78; }
        50% { transform: scale(1.06); opacity: 0.96; }
        100% { transform: scale(0.9); opacity: 0.78; }
    }

    @keyframes ringPulse {
        0% { transform: scale(0.94); opacity: 0.42; }
        50% { transform: scale(1.06); opacity: 0.78; }
        100% { transform: scale(0.94); opacity: 0.42; }
    }

    .voice-orb {
        position: relative;
        width: 170px;
        height: 170px;
        aspect-ratio: 1 / 1;
        flex-shrink: 0;
        border-radius: 9999px;
        background: radial-gradient(circle at 35% 30%, #1f2937 0%, #0f172a 52%, #020617 100%);
        box-shadow: inset -10px -12px 24px rgba(0,0,0,0.85), inset 8px 8px 18px rgba(255,255,255,0.06), 0 0 38px rgba(30,41,59,0.34);
        animation: none;
        transition: box-shadow 220ms ease, background 220ms ease, transform 220ms ease;
        overflow: visible;
    }

    .voice-orb::before,
    .voice-orb::after {
        content: '';
        position: absolute;
        border-radius: 9999px;
        inset: -7px;
        pointer-events: none;
    }

    .voice-orb::before {
        inset: -14px;
        border: 2px solid rgba(148,163,184,0.3);
        border-right-color: rgba(148,163,184,0.62);
        border-bottom-color: rgba(148,163,184,0.58);
        transform-origin: center;
        animation: black-hole 2.6s linear infinite, ringPulse 2s ease-in-out infinite;
    }

    .voice-orb::after {
        inset: 28px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.04) 55%, transparent 78%);
        animation: black-hole-core 1.7s ease-in-out infinite;
    }

    .voice-orb.listening {
        box-shadow: inset -10px -12px 24px rgba(0,0,0,0.85), inset 8px 8px 18px rgba(255,255,255,0.06), 0 0 44px rgba(37,99,235,0.55), 0 0 72px rgba(37,99,235,0.2);
    }

    .voice-orb.bot-speaking {
        box-shadow: inset -10px -12px 24px rgba(0,0,0,0.85), inset 8px 8px 18px rgba(255,255,255,0.06), 0 0 44px rgba(250,204,21,0.58), 0 0 72px rgba(250,204,21,0.2);
    }
</style>

<script>
const chatForm = document.getElementById('chat-form');
const inputField = document.getElementById('user-input');
const submitBtn = document.getElementById('chat-submit-btn');
const chatBox = document.getElementById('chat-box');
const messagesLayer = document.getElementById('messages-layer');
const typingIndicator = document.getElementById('typing-indicator');
const micToggleBtn = document.getElementById('mic-toggle');
const speakerToggleBtn = document.getElementById('speaker-toggle');
const micModeMenu = document.getElementById('mic-mode-menu');
const dictationModeBtn = document.getElementById('mode-dictation');
const conversationModeBtn = document.getElementById('mode-conversation');
const voiceModePanel = document.getElementById('voice-mode-panel');
const voiceSphere = document.getElementById('voice-sphere');
const sttSessionLabel = document.getElementById('stt-session-id');

const ttsConfig = {
    endpoint: "{{ route('tts.proxy') }}"
};

let speakerEnabled = false;
let remoteListening = false;
let isSubmitting = false;
let awaitingBotResponse = false;
let sttPollTimer = null;
let sttPollInFlight = false;
let currentSttSessionId = '';
let sttMode = 'dictation';
let conversationActive = false;
let sttAwaitingResult = false;
const STT_POLL_MS = 250;

function setMicStatus(text) {
    const micStatus = document.getElementById('mic-status');
    if (micStatus) {
        micStatus.textContent = text;
    }
}

function setSubmitLocked(locked) {
    submitBtn.disabled = locked;
    if (locked) {
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed', 'pointer-events-none');
    } else {
        submitBtn.classList.remove('opacity-60', 'cursor-not-allowed', 'pointer-events-none');
    }
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;
    return div.innerHTML;
}

function getPrimaryTextField() {
    if (inputField && !inputField.readOnly) {
        return inputField;
    }

    const fallback = chatForm.querySelector('input[type="text"], textarea');
    return fallback || null;
}

function setNativeInputValue(element, value) {
    const prototype = Object.getPrototypeOf(element);
    const descriptor = Object.getOwnPropertyDescriptor(prototype, 'value');
    const setter = descriptor && descriptor.set;

    if (setter) {
        setter.call(element, value);
    } else {
        element.value = value;
    }
}

function injectTranscriptIntoField(text, options = {}) {
    const shouldFocus = options.focus === true;
    const field = getPrimaryTextField();
    if (!field) return false;

    field.disabled = false;
    setNativeInputValue(field, text);
    field.value = text;
    field.setAttribute('value', text);
    field.dispatchEvent(new Event('input', { bubbles: true }));
    field.dispatchEvent(new Event('change', { bubbles: true }));

    const endPos = field.value.length;
    if (typeof field.setSelectionRange === 'function') {
        field.setSelectionRange(endPos, endPos);
    }

    if (shouldFocus) {
        requestAnimationFrame(() => {
            field.focus({ preventScroll: true });
        });
    }

    const expectedText = String(text);
    setTimeout(() => {
        const current = String(field.value || '');
        if (current !== expectedText) {
            setNativeInputValue(field, expectedText);
            field.value = expectedText;
            field.setAttribute('value', expectedText);
            field.dispatchEvent(new Event('input', { bubbles: true }));
            field.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }, 120);

    return true;
}

function appendUserMessage(message) {
    messagesLayer.innerHTML += `
        <div class="flex justify-end animate-fade-in-up">
            <div class="bg-blue-600 text-white p-5 rounded-[1.8rem] rounded-tr-none shadow-lg shadow-blue-100 max-w-[85%] md:max-w-[70%] leading-relaxed">
                ${escapeHtml(message)}
            </div>
        </div>
    `;
}

function appendAiMessage(message) {
    messagesLayer.innerHTML += `
        <div class="flex justify-start animate-fade-in-up">
            <div class="bg-white border border-gray-100 text-gray-700 p-5 rounded-[1.8rem] rounded-tl-none shadow-sm max-w-[85%] md:max-w-[70%] leading-relaxed">
                ${escapeHtml(message)}
            </div>
        </div>
    `;
}

function setSttMode(mode) {
    sttMode = mode === 'conversation' ? 'conversation' : 'dictation';

    const isConversationMode = sttMode === 'conversation';

    voiceModePanel.classList.toggle('hidden', !isConversationMode);
    voiceModePanel.classList.toggle('flex', isConversationMode);
    messagesLayer.classList.toggle('hidden', isConversationMode);
    messagesLayer.classList.toggle('overflow-y-auto', !isConversationMode);
    messagesLayer.classList.toggle('overflow-hidden', isConversationMode);
    typingIndicator.classList.add('hidden');

    if (isConversationMode) {
        inputField.value = '';
        inputField.disabled = true;
        inputField.placeholder = 'Гласов режим е активен';
        setSpeakerState(true);
        setOrbState('idle', 'Готов за слушане');
    } else {
        inputField.disabled = false;
        inputField.placeholder = 'Задай въпрос...';
        setOrbState('idle', 'Готов за слушане');
    }
}

function showMicModeMenu() {
    micModeMenu.classList.remove('hidden');
}

function hideMicModeMenu() {
    micModeMenu.classList.add('hidden');
}

function toggleMicModeMenu() {
    micModeMenu.classList.toggle('hidden');
}

function setOrbState(state, text) {
    voiceSphere.classList.remove('listening', 'bot-speaking');
    if (state === 'listening') {
        voiceSphere.classList.add('listening');
    }
    if (state === 'bot-speaking') {
        voiceSphere.classList.add('bot-speaking');
    }
}

function setListeningState(isListening, text) {
    remoteListening = isListening;
    setMicStatus(text);

    if (isListening) {
        micToggleBtn.classList.remove('bg-gray-200', 'text-gray-500', 'hover:bg-gray-300');
        micToggleBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        if (sttMode === 'conversation') {
            setOrbState('listening', 'Човекът говори...');
        }
    } else {
        micToggleBtn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        micToggleBtn.classList.add('bg-gray-200', 'text-gray-500', 'hover:bg-gray-300');
        if (sttMode === 'conversation') {
            setOrbState('idle', 'Готов за слушане');
        }
    }
}

function clearSttPoll() {
    if (sttPollTimer) {
        clearTimeout(sttPollTimer);
        sttPollTimer = null;
    }
    sttPollInFlight = false;
    sttAwaitingResult = false;
}

function setSpeakerState(enabled) {
    speakerEnabled = enabled;
    if (enabled) {
        speakerToggleBtn.classList.remove('bg-gray-200', 'text-gray-500', 'hover:bg-gray-300');
        speakerToggleBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
    } else {
        speakerToggleBtn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        speakerToggleBtn.classList.add('bg-gray-200', 'text-gray-500', 'hover:bg-gray-300');
    }
}

async function speakWithTts(text, providedUrl = null) {
    if (!speakerEnabled || !text) return;

    const ttsUrl = providedUrl || `${ttsConfig.endpoint}?text=${encodeURIComponent(text)}`;
    if (sttMode === 'conversation') {
        setOrbState('bot-speaking', 'Ботът говори...');
    }

    try {
        const response = await fetch(ttsUrl, { method: 'GET' });
        if (!response.ok) {
            throw new Error(`TTS HTTP ${response.status}`);
        }
    } catch (error) {
        console.warn('TTS не може да се възпроизведе:', error);
    } finally {
        if (sttMode === 'conversation') {
            setOrbState('idle', 'Готов за слушане');
        }
    }
}

async function sendMessage(message) {
    if (!message || isSubmitting || awaitingBotResponse) return;
    isSubmitting = true;
    awaitingBotResponse = true;
    setSubmitLocked(true);

    appendUserMessage(message);
    inputField.value = '';
    typingIndicator.classList.remove('hidden');
    messagesLayer.scrollTop = messagesLayer.scrollHeight;

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
        const aiMessage = data.answer || data.ai_response || 'Извинявай, {{ explode(" ", $user->name)[0] }}, но не успях да генерирам отговор.';
        appendAiMessage(aiMessage);
        await speakWithTts(aiMessage, data.tts_url || null);
    } catch (error) {
        console.error('Грешка при връзка с контролера:', error);
        messagesLayer.innerHTML += `
            <div class="flex justify-start animate-fade-in-up text-red-500 text-xs italic p-4">
                Грешка при връзка със сървъра. Провери дали AI машината работи.
            </div>
        `;
    } finally {
        typingIndicator.classList.add('hidden');
        messagesLayer.scrollTop = messagesLayer.scrollHeight;
        isSubmitting = false;
        awaitingBotResponse = false;
        setSubmitLocked(false);
    }
}

async function startRemoteStt() {
    if (remoteListening || sttAwaitingResult || isSubmitting || awaitingBotResponse) return;

    setListeningState(true, 'Стартирам Raspberry микрофона...');
    clearSttPoll();

    try {
        const startResponse = await fetch("{{ route('stt.start') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ mode: sttMode })
        });

        const startData = await startResponse.json();
        if (!startResponse.ok || !startData.ok || !startData.session_id) {
            throw new Error(startData.message || 'Неуспешен старт на STT');
        }

        currentSttSessionId = String(startData.session_id);
        const mode = (startData.mode === 'conversation' || startData.mode === 'dictation') ? startData.mode : sttMode;
        sttAwaitingResult = true;
        sttSessionLabel.textContent = `Session ID: ${currentSttSessionId}`;
        setListeningState(true, 'Слушам през Raspberry...');

        const resultUrlTemplate = "{{ route('stt.result.api', ['sessionId' => '__SESSION__']) }}";
        const resultUrl = resultUrlTemplate.replace('__SESSION__', encodeURIComponent(currentSttSessionId));

        const pollOnce = async () => {
            if (!sttAwaitingResult) return;

            if (sttPollInFlight) {
                sttPollTimer = setTimeout(pollOnce, STT_POLL_MS);
                return;
            }

            sttPollInFlight = true;
            try {
                const response = await fetch(`${resultUrl}?_ts=${Date.now()}`, {
                    method: 'GET',
                    cache: 'no-store',
                    headers: {
                        'Cache-Control': 'no-cache',
                        'Pragma': 'no-cache'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json().catch(() => null);
                if (!data || data.status !== 'done') return;

                const transcript = String(data.transcript || data.text || '').trim();
                if (!transcript) return;

                sttAwaitingResult = false;
                clearSttPoll();
                setListeningState(false, 'Микрофонът е изключен');
                sttSessionLabel.textContent = `Session ID: ${currentSttSessionId} (done)`;

                if (mode === 'dictation') {
                    const injected = injectTranscriptIntoField(transcript, { focus: false });
                    setMicStatus(injected
                        ? `STT: текстът е добавен (${transcript.length} знака)`
                        : 'STT: не успях да добавя текста в полето');
                } else {
                    await sendMessage(transcript);
                }

                messagesLayer.scrollTop = messagesLayer.scrollHeight;

                if (mode === 'conversation' && conversationActive) {
                    setTimeout(() => {
                        if (conversationActive && !remoteListening && !sttAwaitingResult && !awaitingBotResponse) {
                            startRemoteStt();
                        }
                    }, 220);
                }
            } catch (pollError) {
                console.error('Грешка при polling на STT:', pollError);
            } finally {
                sttPollInFlight = false;
                if (sttAwaitingResult) {
                    sttPollTimer = setTimeout(pollOnce, STT_POLL_MS);
                }
            }
        };

        sttPollTimer = setTimeout(pollOnce, STT_POLL_MS);
    } catch (error) {
        sttAwaitingResult = false;
        clearSttPoll();
        setListeningState(false, 'Микрофонът е изключен');
        setMicStatus('STT грешка. Провери Raspberry/AI връзката.');
        console.error('Грешка при стартиране на STT:', error);
    }
}

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (sttMode === 'conversation') return;
    if (awaitingBotResponse) return;

    if (sttAwaitingResult) {
        sttAwaitingResult = false;
        clearSttPoll();
        setListeningState(false, 'Микрофонът е изключен');
    }

    await sendMessage(inputField.value.trim());
});

micToggleBtn.addEventListener('click', () => {
    if (remoteListening || conversationActive) {
        conversationActive = false;
        clearSttPoll();
        sttAwaitingResult = false;
        setListeningState(false, 'Микрофонът е изключен');
        setSttMode('dictation');
        hideMicModeMenu();
        return;
    }

    if (awaitingBotResponse || isSubmitting) {
        setMicStatus('Изчакай отговора на бота');
        return;
    }

    toggleMicModeMenu();
});

speakerToggleBtn.addEventListener('click', () => {
    setSpeakerState(!speakerEnabled);
});

document.addEventListener('click', (event) => {
    if (!chatForm.contains(event.target)) {
        inputField.blur();
    }
});

dictationModeBtn.addEventListener('click', () => {
    if (awaitingBotResponse || isSubmitting) {
        hideMicModeMenu();
        return;
    }

    conversationActive = false;
    hideMicModeMenu();
    clearSttPoll();
    sttAwaitingResult = false;
    setListeningState(false, 'Микрофонът е изключен');
    setSttMode('dictation');
    startRemoteStt();
});

conversationModeBtn.addEventListener('click', () => {
    if (awaitingBotResponse || isSubmitting) {
        hideMicModeMenu();
        return;
    }

    conversationActive = true;
    hideMicModeMenu();
    clearSttPoll();
    sttAwaitingResult = false;
    setListeningState(false, 'Микрофонът е изключен');
    setSttMode('conversation');
    startRemoteStt();
});

document.addEventListener('click', (event) => {
    if (!micModeMenu.contains(event.target) && !micToggleBtn.contains(event.target)) {
        hideMicModeMenu();
    }
});

setSpeakerState(false);
setListeningState(false, 'Микрофонът е изключен');
setSttMode('dictation');
setSubmitLocked(false);
hideMicModeMenu();
</script>
@endsection