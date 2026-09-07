@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container py-5 my-auto">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <!-- Card Header Decoration -->
                <div class="bg-orange-subtle border-bottom border-orange-subtle px-4 py-4 text-center">
                    <div class="d-inline-flex p-3 rounded-circle bg-white text-orange shadow-sm mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Create Your Account</h4>
                    <p class="text-secondary fs-7 mb-0">Join NOW Cognitive Counseling Center for personalized AI guidance</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="guest_session_token" id="register_guest_session_token" value="{{ request('guest_session_token', session('guest_session_token')) }}">

                        <!-- Full Name Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium text-dark fs-7">{{ __('Full Name') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z"/>
                                    </svg>
                                </span>
                                <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe">
                            </div>
                            @error('name')
                                <div class="text-danger fs-8 mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <!-- Email Address Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium text-dark fs-7">{{ __('Email Address') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                    </svg>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">
                            </div>
                            @error('email')
                                <div class="text-danger fs-8 mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium text-dark fs-7">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                        <path d="M5.072 11.993a10 10 0 0 1-2.922-2.36C3.006 6.51 4.212 3.978 8 2.094c3.788 1.884 4.994 4.416 5.85 7.539a10 10 0 0 1-2.922 2.363 8 8 0 0 1-2.928.75 8 8 0 0 1-2.928-.753M8 1.056c-4.053 2.05-5.467 4.96-6.38 8.423a11 11 0 0 0 3.327 2.71 9 9 0 0 0 3.053.844 9 9 0 0 0 3.053-.844 11 11 0 0 0 3.327-2.71c-.913-3.464-2.327-6.374-6.38-8.423"/>
                                        <path d="M8 6a1 1 0 0 0-1 1v1a1 1 0 0 0 2 0V7a1 1 0 0 0-1-1"/>
                                    </svg>
                                </span>
                                <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters">
                            </div>
                            @error('password')
                                <div class="text-danger fs-8 mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-medium text-dark fs-7">{{ __('Confirm Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                        <path d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 0 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                                    </svg>
                                </span>
                                <input id="password-confirm" type="password" class="form-control border-start-0 ps-0" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter your password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-orange btn-lg w-100 rounded-3 fw-bold shadow-sm py-2 mb-3">
                            {{ __('Create Account') }}
                        </button>
                    </form>
                </div>

                <!-- Footer Prompt -->
                <div class="bg-light border-top border-stone-200 px-4 py-3 text-center">
                    <span class="text-secondary fs-7">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-orange fw-bold text-decoration-none ms-1 fs-7">
                        Log In Here &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tokenInput = document.getElementById('register_guest_session_token');
    const savedToken = localStorage.getItem('counseling_session_token');
    if (tokenInput && !tokenInput.value && savedToken) {
        tokenInput.value = savedToken;
    }
});
</script>
@endsection
