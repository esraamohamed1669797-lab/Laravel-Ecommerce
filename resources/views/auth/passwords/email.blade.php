```blade
@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>

    <section class="login-register container">
        <ul class="nav nav-tabs mb-5">
            <li class="nav-item">
                <span class="nav-link nav-link_underscore active">
                    Reset Password
                </span>
            </li>
        </ul>

        <div class="login-form">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-floating mb-4">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
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

                <button type="submit" class="btn btn-primary w-100 text-uppercase">
                    Send Password Reset Link
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

