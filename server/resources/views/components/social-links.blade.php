<div class="mt-4 d-grid gap-3">
    {{-- <a style="color:dodgerblue" class="btn btn-dark w-100 d-flex align-items-center justify-content-center" href="{{ route('socialite.auth', 'github') }}">
        <i class="fab fa-github me-2"></i> Sign in with GitHub
    </a> --}}
    
    <a style="color:dodgerblue" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center" href="{{ route('socialite.auth', 'google') }}">
        <i class="fab fa-google me-2"></i> Вход с Google
    </a>
    
    {{-- Uncomment this section if Facebook login is enabled --}}
    {{-- 
    <a class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" href="{{ route('socialite.auth', 'facebook') }}">
        <i class="fab fa-facebook-f me-2"></i> Sign in with Facebook
    </a>
    --}}
</div>
