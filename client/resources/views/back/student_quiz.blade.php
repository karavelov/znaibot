@extends('main')
@extends('keyboard')
@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-6 relative">
    
    <div class="absolute top-8 left-8">
        <a href="{{ route('student_home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Назад
        </a>
    </div>

    <div class="w-full max-w-2xl">
        @if(!$answered && $question)
            <div class="text-center mb-10">
                <div class="inline-flex items-center px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                    <i class="fas fa-star mr-2"></i> Викторина на деня
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Предизвикателство</h1>
            </div>

            <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-4">Въпрос за {{ $user->klas_name }}</p>
                    
                    {{-- ПОПРАВКА ТУК: Проверява за 'question' или 'text' колона --}}
                    <h2 class="text-xl font-semibold text-gray-800 mb-8">
                        {!! strip_tags($question->question ?? $question->text ?? 'Текстът на въпроса не е намерен в базата данни') !!}
                    </h2>

                    <div id="hint-box" class="hidden mb-6 p-4 bg-blue-50 text-blue-700 rounded-2xl text-sm italic"></div>

                    <form id="quiz-form" onsubmit="return handleQuizSubmit(event)">
                        @csrf
                        <input type="hidden" id="q_id" value="{{ $question->id }}">
                        <input type="text" id="answer-input" 
                            class="w-full bg-[#F5F5F7] border-2 border-transparent focus:border-amber-400 rounded-xl px-6 py-4 text-lg text-center outline-none transition-all"
                            placeholder="Напиши своя отговор..." required>

                        <button type="submit" id="submit-btn" class="w-full mt-6 px-8 py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-amber-500 transition-all shadow-lg">
                            Провери отговора
                        </button>
                    </form>

                    <div class="mt-8 flex justify-center items-center space-x-6 text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-coins text-amber-400 mr-2"></i>
                            <span class="text-xs font-medium">+{{ $question->points ?? 10 }} точки</span>
                        </div>
                        <div class="w-px h-4 bg-gray-200"></div>
                        <div class="flex items-center">
                             <i class="fas fa-redo-alt text-blue-400 mr-2"></i>
                            <span id="attempts-display" class="text-xs font-medium">Опити: {{ $attemptsLeft }}/3</span>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($answered)
            {{-- Секция за приключил куиз --}}
            <div class="text-center w-full">
                 <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Браво! Ти приключи за днес.</h1>
                <p class="text-sm text-gray-500 mb-8 px-4">Ела пак утре за нов въпрос. Ето как се справят твоите съученици:</p>
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-gray-400 uppercase font-bold text-[10px]">Място</th>
                                <th class="px-6 py-3 text-left text-gray-400 uppercase font-bold text-[10px]">Ученик</th>
                                <th class="px-6 py-3 text-right text-gray-400 uppercase font-bold text-[10px]">Точки</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($scoreboard ?? [] as $index => $topUser)
                            <tr class="{{ $topUser->id == $user->id ? 'bg-amber-50' : '' }}">
                                <td class="px-6 py-4 font-bold text-gray-800">#{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $topUser->name }}</td>
                                <td class="px-6 py-4 text-right font-bold text-amber-500">{{ $topUser->quiz_points }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    <a href="{{ route('student_home') }}" class="text-amber-500 font-bold hover:underline text-sm">Обратно към началото</a>
                </div>
            </div>
        @else
            <div class="text-center">
                <h2 class="text-xl font-semibold text-gray-800">Няма активни въпроси.</h2>
                <p class="text-gray-500 mt-2 text-sm">Моля, опитайте по-късно.</p>
            </div>
        @endif
    </div>
</div>

<script>
async function handleQuizSubmit(event) {
    event.preventDefault();
    const btn = document.getElementById('submit-btn');
    const input = document.getElementById('answer-input');
    const hintBox = document.getElementById('hint-box');
    const qId = document.getElementById('q_id').value;

    btn.disabled = true;
    btn.innerText = 'Проверка...';

    try {
        const response = await fetch("{{ route('student_quiz_submit') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ question_id: qId, answer: input.value })
        });

        const data = await response.json();

        if (data.correct || data.out_of_attempts) {
            location.reload();
        } else {
            btn.disabled = false;
            btn.innerText = 'Опитай пак';
            if(data.hint) {
                hintBox.innerText = "Подсказка: " + data.hint;
                hintBox.classList.remove('hidden');
            }
            input.value = '';
            
            const attemptsSpan = document.getElementById('attempts-display');
            if(attemptsSpan) {
                // Динамично обновяване на текста за опити
                let currentText = attemptsSpan.innerText;
                let currentVal = parseInt(currentText.match(/\d+/)[0]);
                if (currentVal > 1) {
                    attemptsSpan.innerText = `Опити: ${currentVal - 1}/3`;
                } else {
                    attemptsSpan.innerText = `Последен опит!`;
                }
            }
        }
    } catch (error) {
        btn.disabled = false;
        btn.innerText = 'Грешка. Опитай пак';
    }
    return false;
}
</script>
@endsection