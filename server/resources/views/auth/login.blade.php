@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} - Влизане
@endsection

@section('content')

<div class="clearfix space"></div>

<!-- Login Section -->
<section class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Login Header -->
                <div class="text-center mb-4 mt-4">
                    <h3 class="section-title">Влизане</h3>
                </div>

                <!-- Error Messages -->
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{$error}}
                </div>
                @endforeach

                @if(Session::has('message'))
                <div class="alert alert-danger" role="alert">
                    {{Session::get('message')}}
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{route('login')}}" class="p-4 border rounded shadow-sm bg-white">
                    @csrf
                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Имейл</label>
                        <input id="email" type="email" name="email" class="form-control" required placeholder="Email" value="{{old('email')}}">
                        <p class="text-danger">@error('email') {{$message}} @enderror</p>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Парола</label>
                        <input id="password" type="password" name="password" class="form-control" required placeholder="Password" value="{{old('password')}}">
                        <p class="text-danger">@error('password') {{$message}} @enderror</p>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">Запомни ме</label>
                        </div>
                        {{-- <a class="text-decoration-none text-primary" href="{{route('password.request')}}">Забравена парола?</a> --}}
                        <a class="text-decoration-none text-primary" href="{{route('register')}}">Регистрация</a>
                    </div>

                    <!-- Submit Button -->
                    <button class="btn btn-primary w-100 mb-3" type="submit">Влизане</button>

                    <!-- Social Login Section -->
                    <p class="text-center mb-2">Влизане с Google профил</p>
                    <div class="d-flex justify-content-center gap-3">
                        <x-social-links /> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection