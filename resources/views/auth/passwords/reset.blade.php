@extends('layouts.frontend')


@section('content')


        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            <h3>Reset Password</h3>
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf

                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="input-box mb--30">
                                    <label>{{ __('Email Address') }} <em>*</em></label>
                                    <input id="email" type="email" class="input-text @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                </div>
                                <div class="input-box mb--30">
                                    <label>{{ __('Password') }} <em>*</em></label>
                                    <input id="password" type="password" class="input-text @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                </div>
                                <div class="input-box mb--30">
                                    <label>{{ __('Confirm Password') }} <em>*</em></label>
                                    <input id="password-confirm" type="password" class="input-text" name="password_confirmation" required autocomplete="new-password">
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30" type="submit">
                                    <span>Reset Password</span>
                                </button>
                                
                                @if (Route::has('login'))
                                    <a class="rn-btn edu-btn w-100 mb--30 button-grey text-center" href="{{ route('login') }}">Login</a>
                                @endif

                            </form>
                        </div>
                </div>
            </div>
        </div>

@endsection
