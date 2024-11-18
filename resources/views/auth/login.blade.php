@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-5">
                <i class="fas fa-tasks-alt fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold">Welcome Back! 👋</h2>
                <p class="text-muted">Ready to tackle your tasks? Let's get started!</p>
            </div>
            
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">{{ __('Email Address') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input id="email" type="email" 
                                    class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" 
                                    placeholder="you@example.com"
                                    required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-medium">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input id="password" type="password" 
                                    class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                    name="password" 
                                    placeholder="Enter your password"
                                    required autocomplete="current-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Keep me signed in') }}
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                            {{ __('Sign In') }} <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        <p class="text-center mb-0">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-decoration-none">Create one now</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.input-group-text {
    border-radius: 1rem 0 0 1rem;
}

.input-group .form-control {
    border-radius: 0 1rem 1rem 0;
}

.btn {
    border-radius: 1rem;
    padding: 0.8rem 1.5rem;
    transition: transform 0.2s;
}

.btn:hover {
    transform: translateY(-2px);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
@endpush
