@extends('frontend.layouts.master')

@section('content')

<br><br><br><br><br><br><br><br>

<style>
    /* Центриране на текста в таблиците */
    .table-view th,
    .table-view td {
        text-align: center;
        vertical-align: middle;
    }

    /* Стилове за заглавните клетки */
    .table-view th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-transform: uppercase;
        padding: 12px;
    }

    /* Стилове за клетките с данни */
    .table-view td {
        padding: 12px;
    }

    /* Стилове за таблицата с класиране */
    .table-view tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-view tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    /* Стилове за AI Учител секцията */
    .bg-blue-100 {
        background-color: #e9f5ff;
    }

    .border-blue-300 {
        border-color: #90cdf4;
    }

    .text-blue-700 {
        color: #2b6cb0;
    }

    /* Стилове за линковете */
    .text-blue-600 {
        color: #3182ce;
    }

    .text-blue-600:hover {
        text-decoration: underline;
    }

    /* Стилове за грешни отговори */
    .text-red-600 {
        color: #e53e3e;
    }

    /* Стилове за верни отговори */
    .text-success {
        color: #38a169;
    }

    /* Стилове за разделителните линии */
    hr {
        border-color: #e2e8f0;
    }
</style>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-bold text-center">Резултати от тест</h3>

                <table class="mt-4 table w-full table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th class="border border-solid bg-gray-100 px-6 py-3 text-sm font-semibold uppercase text-slate-600">
                                Дата
                            </th>
                            <td class="border border-solid px-6 py-3">
                                {{ $test->created_at->format('D d/m/Y, h:i A') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border border-solid bg-gray-100 px-6 py-3 text-sm font-semibold uppercase text-slate-600">
                                Резултат
                            </th>
                            <td class="border border-solid px-6 py-3">
                                {{ $test->result }} / {{ $questions_count }}
                            </td>
                        </tr>
                        <tr>
                            <th class="border border-solid bg-gray-100 px-6 py-3 text-sm font-semibold uppercase text-slate-600">
                                Общо точки
                            </th>
                            <td class="border border-solid px-6 py-3">
                                {{ $test->total_points }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>

    @isset($leaderboard)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h6 class="text-xl font-bold text-center">Класиране</h6>

                    <table class="table mt-4 w-full table-view">
                        <thead>
                            <tr>
                                <th class="text-center">Ранг</th>
                                <th class="text-center">Потребител</th>
                                <th class="text-center">Резултат</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($leaderboard as $test)
                                <tr @class([
                                    'bg-gray-100' => auth()->user()->name == $test->user->name,
                                ])>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $test->user->name }}</td>
                                    <td>{{ $test->result }} / {{ $questions_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endisset

    <br>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @foreach ($results as $result)
                    <table class="table table-view w-full my-4 bg-white">
                        <tbody class="bg-white">
                            <tr class="bg-gray-100">
                                <td class="w-1/2 text-center">Въпрос #{{ $loop->iteration }}</td>
                                <td class="text-center">{!! nl2br($result->question->text) !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Възможни отговори</td>
                                <td>
                                    @if (!$result->question->uses_textarea)
                                        <ul class="list-group">
                                            @foreach ($result->question->options as $option)
                                                <li @class([
                                                    'list-group-item',
                                                    'text-primary' => $result->option_id == $option->id,
                                                    'border-l-4 text-success' => $option->correct == 1,
                                                ])>
                                                    <div @class([
                                                        'd-flex',
                                                        'text-primary' => $result->option_id == $option->id,
                                                        'font-bold text-success' => $option->correct == 1,
                                                        'text-red-600' => $result->option_id != $option->id && $option->correct != 1,
                                                    ])>
                                                        {!! $option->text !!}
                                                    </div>
                                                    <div class="flex gap-2 items-center text-sm justify-center">
                                                        @if ($option->correct == 1)
                                                            <span class="text-success font-medium">✓ верен отговор</span>
                                                        @endif
                                                        @if ($result->option_id == $option->id)
                                                            <span class="text-blue-600 font-medium">ⓞ вашият отговор</span>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if (is_null($result->option_id))
                                            <span class="font-bold italic text-red-600 text-center block">Неотговорен въпрос</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @if ($result->question->uses_textarea)
                                <tr>
                                    <td class="text-center">Вашият отговор</td>
                                    <td class="text-center">{{ $result->textarea_response ?? 'N/A' }}</td>
                                </tr>
                            @endif
                            
                            @if ($result->question->uses_textarea && $result->feedback)
                                <tr>
                                    <td class="text-center">AI Учител</td>
                                    <td class="bg-blue-100 p-3 rounded border border-blue-300 text-center">
                                        <strong class="text-blue-700">Отговор:</strong> {{ $result->feedback }}
                                    </td>
                                </tr>
                            @endif

                            @if ($result->question->answer_explanation || $result->question->more_info_link)
                                <tr>
                                    <td class="text-center">Обяснение</td>
                                    <td class="prose max-w-none text-center">{!! $result->question->answer_explanation !!}</td>
                                </tr>

                                @if ($result->question->more_info_link)
                                    <tr>
                                        <td class="text-center">Виж още:</td>
                                        <td class="text-center">
                                            <div class="mt-4">
                                                <a href="{{ $result->question->more_info_link }}" class="hover:underline text-blue-600" target="_blank">
                                                    {{ $result->question->more_info_link }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>

                    @if (!$loop->last)
                        <hr class="my-4 md:min-w-full">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection