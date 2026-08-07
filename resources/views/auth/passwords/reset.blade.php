```blade
@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>

    <section class="login-register container">
        <ul class="nav nav-tabs mb-5">
            <li class="nav-item">
                <span class="nav-link nav-link_underscore active">
                    Create New Password
                </span>
            </li>
        </ul>

        <div class="login-form">

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-floating mb-3">
                    <input id="email"
                        type="email"
                        name="email"
                        value="{{ $email ?? old('email') }}"
                        class="form-control form-control_gray @error('email') is-invalid @enderror"
                        placeholder="Email Address"
                        required
                        autocomplete="email"
                        autofocus>

                    <label for="email">Email Address *</label>

                    @error('email')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="password"
                        type="password"
                        name="password"
                        class="form-control form-control_gray @error('password') is-invalid @enderror"
                        placeholder="New Password"
                        required
                        autocomplete="new-password">

                    <label for="password">New Password *</label>

                    @error('password')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input id="password-confirm"
                        type="password"
                        name="password_confirmation"
                        class="form-control form-control_gray"
                        placeholder="Confirm Password"
                        required
                        autocomplete="new-password">

                    <label for="password-confirm">Confirm Password *</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 text-uppercase">
                    Reset Password
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="auth-link">
                        ← Back to Login
                    </a>
                </div>

            </form>

        </div>
    </section>
</main>
@endsection

