@extends('layouts.frontend')

@section('content')


        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            <h4>Forgot Email/Password</h4>
                            <p>We will send you an email with instructions on how to reset your password.</p>
                            <form method="POST" action="{{ route('password.email') }}" class="wpcf7 wpcf7-form">  @csrf
                                <div class="input-box mb--30">
                                    <label>{{ __('Email Address') }} <em>*</em></label>
                                    <input id="email" type="email" class="input-text @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30" name="login" type="submit">
                                    <span>{{ __('Email Me') }}</span>
                                </button>
                                

                            </form>
                        </div>
                </div>
            </div>
        </div>
        
@endsection
