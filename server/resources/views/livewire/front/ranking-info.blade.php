@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} - Легенда с медали
@endsection

@section('content')

<br><br><br><br><br><br><br><br>

    <div class="py-12">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Legend --}}
                    <div class="mt-4 text-center">
                        <h3 class="mb-5"><b>Необходими точки за ранг</b></h3> <!-- Increased bottom margin -->
                        <div class="row justify-content-center">
                            @foreach ($requiredPoints as $row)
                                <div class="col-md-4 mb-5"> <!-- Increased bottom margin -->
                                    <div class="card h-100">
                                        <div class="card-body" style="border: 1px solid #ddd; margin-top:30px;"> <!-- Added border -->
                                            
                                            <img src="{{ asset($row->image) }}" alt="{{ $row->title }}" title="{{$row->title}} медал" class="img-fluid mb-3" style="max-width: 200px; margin-top: 20px; margin-bottom: 20px;">


                                            <h3 class="card-title"><b>{{ $row->title }}</b></h3>
                                            <p style="font-weight:bold;"class="card-text">{{ $row->required_points }} точки</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection