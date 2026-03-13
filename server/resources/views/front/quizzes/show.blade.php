@extends('frontend.layouts.master')

@section('content')

    <br><br><br><br>

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Тест: {{ $quiz->title }}
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (!$quiz->public && !auth()->check())
                        <div class="alert alert-warning px-6 py-4 border-0 rounded relative mb-4">
                            <span class="inline-block align-middle mr-8">
                              Този тест е достъпен само за <b>регистрирани потребители</b>. Моля,
                                <a href="{{ route('login') }}" ><b>влезте в профила си</b></a> или
                                <a href="{{ route('register') }}"><b>се регистрирайте</b></a>.
                            </span>
                        </div>
                    @else
                        <div id="quiz-container" style="margin-left:100px;" class="ml-4" data-start-time="{{ $startTimeInMinutes }}">
                            <div class="mb-2">
                                Оставащо време:
                                <span id="timeLeft" class="font-bold">{{ gmdate('H:i:s', $quiz->time * 60) }}</span>
                            </div>
                            <form id="submit-quiz-form" method="POST" action="{{ route('quiz.submit', $quiz) }}">
                                @csrf
                                <input type="hidden" name="startTimeInMinutes" value="{{ $startTimeInMinutes }}">
                                @foreach ($questions as $index => $question)
                                    <div class="mb-4">
                                        <span class="text-bold">Въпрос {{ $index + 1 }} от
                                            {{ $questions->count() }}:</span>
                                        <h3 class="mb-4 text-2xl">{{ $question->text }}</h3>

                                        @if ($question->code_snippet)
                                            <pre class="mb-4 border-2 border-solid bg-gray-50 p-2">{{ $question->code_snippet }}</pre>
                                        @endif

                                        @if ($question->uses_textarea && !empty($question->code_snippet))
                                        {{-- <textarea name="textarea_responses[{{ $question->id }}]" class="w-full p-2 border rounded"
                                            placeholder="Напишете вашия отговор тук...">{{ old("textarea_responses.{$question->id}") }}</textarea> --}}
                                            <textarea placeholder="Въведете вашия отговор тук..." class="form-control" name="textarea_responses[{{ $question->id }}]" id="exampleFormControlTextarea1" rows="3">{{ old("textarea_responses.{$question->id}") }}</textarea>

                                        {{-- Display feedback if available --}}
                                        @if (session()->has("feedback_{$question->id}"))
                                            <div class="mt-2 p-4 bg-gray-100 border border-gray-300 rounded">
                                                <strong>РобоУчител съветва:</strong>
                                                <div class="alert alert-warning" role="alert">
                                                {{ session("feedback_{$question->id}") }}
                                                </div>
                                            </div>
                                        @endif

                                    @else
                                        @foreach ($question->options as $option)
                                            <div>
                                                <label for="option-{{ $option->id }}">
                                                    <input type="radio" class="form-check-input" id="option-{{ $option->id }}"
                                                           name="answers[{{ $question->id }}]"
                                                           value="{{ $option->id }}" {{ old("answers.{$question->id}") == $option->id ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="option-{{ $option->id }}">
                                                        {!! $option->text !!}
                                                    </label>
                                                 
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif


                                    </div>
                                @endforeach
                                <button type="submit" class="mt-4 btn btn-success" id="submitButton">
                                    Предай теста
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let secondsLeft = parseInt(document.getElementById('timeLeft').textContent.split(':').reduce((acc, time) => (60 * acc) + +time));
            const timeLeftElement = document.getElementById('timeLeft');
            const submitQuizForm = document.getElementById('submit-quiz-form');

            function updateTimer() {
                if (secondsLeft > 0) {
                    secondsLeft--;
                } else {
                    submitQuizForm.submit();
                }
                const hours = Math.floor(secondsLeft / 3600);
                const minutes = Math.floor((secondsLeft % 3600) / 60);
                const seconds = secondsLeft % 60;
                timeLeftElement.textContent =
                    `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }

            setInterval(updateTimer, 1000); // Update every second

            // Pass remaining time as a hidden input on form submit
            submitQuizForm.addEventListener('submit', function() {
                const timeSpentInput = document.createElement('input');
                timeSpentInput.type = 'hidden';
                timeSpentInput.name = 'timeSpent';
                timeSpentInput.value = secondsLeft;
                submitQuizForm.appendChild(timeSpentInput);
            });

            // Disable right-click (extra precaution, not secure)
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });

            // Disable F12, Ctrl+Shift+I, F5, Ctrl+R, Ctrl+F5, and Ctrl+Shift+R
            document.addEventListener('keydown', function(e) {
                // Block F12, Ctrl+Shift+I, Ctrl+R, F5, Ctrl+Shift+R, Ctrl+F5
                if (
                    e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                    (e.key === 'F5') ||
                    (e.ctrlKey && e.key === 'r') ||
                    (e.ctrlKey && e.shiftKey && e.key === 'R') ||
                    (e.ctrlKey && e.shiftKey && e.key === 'F5')
                ) {
                    e.preventDefault();
                }
            });

            // Confirmation before submitting the quiz
            const submitButton = document.getElementById('submitButton');
            submitButton.addEventListener('click', function(e) {
                const confirmation = confirm(
                    "Сигурни ли сте, че искате да предадете теста? След предаване няма да можете да го редактирате."
                    );
                if (!confirmation) {
                    e.preventDefault(); // Prevent form submission if the user cancels
                }
            });
        });
    </script>

@endsection
