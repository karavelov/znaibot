@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} - Регистрация
@endsection

@section('content')

<div class="clearfix space"></div>

<!-- Register Section -->
<section class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Register Header -->
                <div class="text-center mb-4 mt-4">
                    <h3 class="section-title">Регистрация</h3>
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

                <!-- Register Form -->
                <form method="POST" action="{{route('register')}}" class="p-4 border rounded shadow-sm bg-white">
                    @csrf
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Име</label>
                        <input id="name" type="text" name="name" class="form-control" required placeholder="Name" value="{{old('name')}}">
                        <p class="text-danger">@error('name') {{$message}} @enderror</p>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Имейл</label>
                        <input id="email" type="email" name="email" class="form-control" required placeholder="Email" value="{{old('email')}}">
                        <p class="text-danger">@error('email') {{$message}} @enderror</p>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Парола</label>
                        <input id="password" type="password" name="password" class="form-control" required placeholder="Password">
                        <p class="text-danger">@error('password') {{$message}} @enderror</p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Потвърди паролата</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required placeholder="Confirm Password">
                    </div>

                  

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-decoration-none text-primary" href="{{route('login')}}">Вече имаш профил ?</a>
                    </div>

                      <!-- Submit Button -->
                      <button class="btn btn-primary w-100 mb-3" type="submit">Регистрация</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection