@extends('layouts.app')

@section('content')
<style>
 
.auth-link {
    color: #6c757d;
    text-decoration: none;
    font-size: 14px;
    transition: all .3s ease;
}

.auth-link:hover {
    color: #0d6efd;
    text-decoration: underline;
}
</style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="login-register container">
            <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab" href="#tab-item-login"
                        role="tab" aria-controls="tab-item-login" aria-selected="true">Login</a>
                </li>
            </ul>
            <div class="tab-content pt-2" id="login_register_tab_content">
                <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                    <div class="login-form">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}" name="login-form" class="needs-validation"
                            novalidate="">
                            @csrf
                            <div class="form-floating mb-3">
                                <input class="form-control form-control_gray @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required="" autocomplete="email"
                                    autofocus="">
                                <label for="email">Email address *</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="pb-3"></div>

                            <div class="form-floating mb-3">
                                <input id="password" type="password"
                                    class="form-control form-control_gray @error('password') is-invalid @enderror"
                                    name="password" required="" autocomplete="current-password">
                                <label for="customerPasswodInput">Password *</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
    

                            <button class="btn btn-primary w-100 text-uppercase" type="submit">Log In</button>
                            
                            <div class="text-center my-4">
                                <div class="d-grid gap-3">

                                    <!-- Google -->
                                    <a href="{{ route('socialite.redirect', 'google') }}"
                                        class="btn btn-light border rounded-3 py-3 d-flex align-items-center justify-content-center shadow-sm social-btn">

                                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="22"
                                            height="22" class="me-3">

                                        <span class="fw-semibold text-dark">
                                            Continue with Google
                                        </span>
                                    </a>

                                    <!-- Facebook -->
                                    <a href="{{ route('socialite.redirect', 'facebook') }}"
                                        class="btn btn-light border rounded-3 py-3 d-flex align-items-center justify-content-center shadow-sm social-btn">

                                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" width="22"
                                            height="22" class="me-3">

                                        <span class="fw-semibold text-dark">
                                            Continue with Facebook
                                        </span>
                                    </a>
                                    <!-- GitHub -->
                                    {{-- <a href="{{ route('socialite.redirect', 'github') }}"
                                        class="btn btn-light border rounded-3 py-3 d-flex align-items-center justify-content-center shadow-sm social-btn">

                                        <img src="https://www.svgrepo.com/show/512317/github-142.svg" width="22"
                                            height="22" class="me-3">

                                        <span class="fw-semibold text-dark">
                                            Continue with GitHub
                                        </span>
                                    </a> --}}

                                </div>

                            </div>
                            <div class="d-flex justify-content-center align-items-center gap-4 mt-4">                            
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="auth-link">
                                        Forgot password
                                    </a>
                                @endif

                                <a href="{{ route('register') }}" class="auth-link">
                                    Create Account
                                </a>
                             </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
