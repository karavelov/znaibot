@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} - Моите резултати
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

<style>
    .table-responsive {
        margin: 20px;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        color: #212529;
    }

    .table th,
    .table td {
        padding: 12px;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background-color: #f8f9fa;
        color: #495057;
    }

    .table tbody + tbody {
        border-top: 2px solid #dee2e6;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
        padding: 5px 10px;
        font-size: 14px;
        line-height: 1.5;
        border-radius: 3px;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .text-center {
        text-align: center;
    }

    .thead-light th {
        background-color: #e9ecef;
    }
</style>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Тест</th>
                <th scope="col">Резултат</th>
                <th scope="col">Време</th>
                <th scope="col">Дата</th>
                <th scope="col">Точки</th>
                <th scope="col">Действие</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tests as $test)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $test->quiz->title }}</td>
                    <td>{{ $test->result . '/' . $test->quiz->questions_count }}</td>
                    <td>{{ $test->time_spent % 60 }} минути</td>
                    <td>{{ $test->created_at->format('d/m/Y h:m A') }}</td>
                    <td>{{ $test->total_points }}</td>
                    <td>
                        <a href="{{ route('results.show', $test) }}" class="btn btn-primary btn-sm">Преглед</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Няма намерени резултати.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $tests->links() }}

@endsection