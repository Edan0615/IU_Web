@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5 my-auto">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <!-- Card Header Decoration -->
                <div class="bg-orange-subtle border-bottom border-orange-subtle px-4 py-4 text-center">
                    <div class="d-inline-flex p-3 rounded-circle bg-white text-orange shadow-sm mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                            <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5V5h-1v-.5a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5V5H3zM13 6v8a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Welcome Back</h4>
                    <p class="text-secondary fs-7 mb-0">Sign in to access your cognitive counseling sessions</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Quick Demo Credentials Helper Banner -->
                    <div class="alert alert-light border border-orange-subtle rounded-3 p-3 mb-4 bg-orange-subtle d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="fw-bold text-dark fs-7 mb-1">Quick Demo Login</div>
                            <div class="text-secondary fs-8 font-monospace">tester@gmail.com / abc123456789</div>
                        </div>
                        <button type="button" id="btn-fill-demo" class="btn btn-orange btn-sm rounded-pill fw-semibold px-3 text-nowrap shadow-sm">
                            Auto Fill
                        </button>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium text-dark fs-7">{{ __('Email Address') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                    </svg>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            </div>
                            @error('email')
                                <div class="text-danger fs-8 mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-medium text-dark fs-7 mb-0">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none fs-8 text-orange fw-medium" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </span>
                                <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('password')
                                <div class="text-danger fs-8 mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-secondary fs-7" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-orange btn-lg w-100 rounded-3 fw-bold shadow-sm py-2">
                            {{ __('Login to Counseling Workspace') }}
                        </button>
                    </form>
                </div>

                <!-- Footer Prompt -->
                <div class="bg-light border-top border-stone-200 px-4 py-3 text-center">
                    <span class="text-secondary fs-7">Don't have an account yet?</span>
                    <a href="{{ route('register') }}" class="text-orange fw-bold text-decoration-none ms-1 fs-7">
                        Register Now &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fillBtn = document.getElementById('btn-fill-demo');
    if (fillBtn) {
        fillBtn.addEventListener('click', function() {
            document.getElementById('email').value = 'tester@gmail.com';
            document.getElementById('password').value = 'abc123456789';
        });
    }
});
</script>
@endsection
