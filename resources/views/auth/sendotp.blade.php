@extends('layouts.frontend')

@section('header-script')
<script src="{{ asset('js/auth/tokenexist.js') }}?v=1" defer></script>
<script src="{{ asset('js/auth/send-otp.js') }}?v=1" defer></script>
@endsection

@section('content')

        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            <h3>Login</h3>
                            <form method="POST" action="{{ route('api.send.otp') }}" data-otp-url="{{ route('login.otp') }}" class="login ajx-sendotp-form" autocomplete="off">
                                @csrf
                                <div class="load-error-msg"></div>
                                <div class="input-box mb--30">
                                    <input id="email" type="text" class="input-text formemail" name="email" placeholder="Email or mobile number" autocomplete="off" autofocus>
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30 loginbtn" type="submit">
                                    <span>Send sign-in code</span>
                                </button>

                                <div class="lineBox mb--20 text-center">
                                    (OR)
                                </div>

                                @if (Route::has('login'))
                                    <a class="rn-btn edu-btn w-100 mb--30 button-grey text-center" href="{{ route('login') }}">Use Password</a>
                                @endif

                                @if (Route::has('password.request'))
                                <div class="input-box mb--20">
                                    <a class="lostpwdlink" href="{{ route('password.request') }}">{{ __('Forgot email or mobile number?') }}</a>
                                </div>
                                @endif

                                <div class="lineBox mb--20">
                                    <input type="checkbox" id="rememberMe" checked="checked" name="rememberMe">
                                    <label for="rememberMe">Remember Me</label>
                                </div>

                                <div class="lineBox">
                                    New to BESTCAST? &nbsp; <a href="{{ route('register') }}" class="createlink">Register Now</a>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
@endsection
