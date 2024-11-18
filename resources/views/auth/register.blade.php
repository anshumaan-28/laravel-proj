@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-5">
                <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold">Join Us Today! 🎉</h2>
                <p class="text-muted">Create your account and start organizing your tasks</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">{{ __('Full Name') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input id="name" type="text" 
                                    class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" 
                                    placeholder="John Doe"
                                    required autocomplete="name" autofocus>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

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
                                    required autocomplete="email">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">{{ __('Password') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input id="password" type="password" 
                                    class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                    name="password" 
                                    placeholder="Choose a strong password"
                                    required autocomplete="new-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-medium">{{ __('Confirm Password') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input id="password-confirm" type="password" 
                                    class="form-control border-start-0 ps-0" 
                                    name="password_confirmation" 
                                    placeholder="Confirm your password"
                                    required autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                            {{ __('Create Account') }} <i class="fas fa-rocket ms-2"></i>
                        </button>

                        <p class="text-center mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Sign in here</a>
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

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

/* Fun animation for the rocket icon */
.fa-rocket {
    transition: transform 0.3s ease;
}

.btn:hover .fa-rocket {
    transform: translateX(5px) translateY(-5px) rotate(45deg);
}
</style>
@endpush
