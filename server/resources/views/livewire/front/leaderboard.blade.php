@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} - Класиране
@endsection

@section('content')
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="py-12">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('leaderboard') }}">
                        <select name="quiz_id" class="form-select mb-3" onchange="this.form.submit()">
                            <option value="0" {{ $quiz_id == 0 ? 'selected' : '' }}>Всички</option>
                            @foreach ($quizzes as $quiz)
                                <option value="{{ $quiz->id }}" {{ $quiz_id == $quiz->id ? 'selected' : '' }}>
                                    {{ $quiz->title }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <style>
                        .glow-1 {
                            text-shadow: 0px 0px 10px gold;
                            color: gold;
                        }

                        .glow-2 {
                            text-shadow: 0px 0px 10px silver;
                            color: silver;
                        }

                        .glow-3 {
                            text-shadow: 0px 0px 10px #cd7f32;
                            color: #cd7f32;
                        }

                        /* Bronze */
                    </style>

                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Потребител</th>
                                <th scope="col">Текущ ранг</th>
                                <th scope="col">Натрупани точки</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $topUsersIds = $topUsers->pluck('id')->toArray(); // ID-та на топ 3 потребители
                            @endphp

                            @forelse ($users as $user)
                                @php
                                    $rank = $user->rank;
                                    $glowClass = '';

                                    if (in_array($user->id, $topUsersIds)) {
                                        $index = array_search($user->id, $topUsersIds) + 1;
                                        $glowClass = match ($index) {
                                            1 => 'glow-1',
                                            2 => 'glow-2',
                                            3 => 'glow-3',
                                            default => '',
                                        };
                                    }
                                @endphp

                                <tr class="{{ $glowClass }}">
                                    <th scope="row">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @if ($rank)
                                            <img src="{{ $rank->image }}" alt="{{ $rank->title }}" class="img-fluid"
                                                style="max-width: 40px;">
                                            <span>{{ $rank->title }}</span>
                                        @else
                                            Няма ранг
                                        @endif
                                    </td>
                                    <td>{{ $user->tests_sum_total_points }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Няма участници в класацията</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                  

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection
