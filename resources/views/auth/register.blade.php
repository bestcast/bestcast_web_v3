@extends('layouts.frontend')

@section('header-script')
<script src="{{ asset('js/auth/tokenexist.js') }}" defer></script>
<script src="{{ asset('js/auth/register.js') }}" defer></script>

@endsection

@section('content')
    
    @php($name=empty($_GET['name'])?'':$_GET['name'])
    @php($email=empty($_GET['email'])?date("YmdHis").rand(1,999999999)."@bestcast.co":$_GET['email'])
    @php($phone=empty($_GET['phone'])?'':$_GET['phone'])

        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            <h3>Register</h3>
                            <form method="POST" action="{{ route('api.register') }}" class="login ajx-register-form" >
                                @csrf
                                <div class="load-error-msg"></div>
                                <div class="mb--30">
                                    <span><h6>Choose any one to receive an OTP message.</h6></span>
                                </div>
                                <div class="form-check form-check-inline mb--30">
                                  <input class="form-check-input" type="radio" name="otp_message_type" id="sms" autocomplete="off" value="sms">
                                  <label class="form-check-label" for="sms">SMS</label>
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="form-check form-check-inline mb--30">
                                  <input id="whatsapp" type="radio" class="form-check-input" name="otp_message_type" autocomplete="off" value="whatsapp" checked>
                                  <label class="form-check-label" for="whatsapp">Whatsapp</label>
                                </div>
                                <div class="input-box profile mb--30">
                                    <div class="icon-profile"> | </div>
                                    <input id="name" type="text" class="input-text" name="name" placeholder="Name *" autocomplete="off"  @if($name) value="{{ $name }}" @else autofocus @endif>
                                </div>
                                <div class="input-box phone mb--30">
                                    <div class="icon-phone"> +91 | </div>
                                    <input id="phone" type="number" class="input-text" name="phone" placeholder="Mobile number *" autocomplete="off"  @if($phone) value="{{ $phone }}" @else autofocus @endif>
                                </div>
                                <div class="input-box email mb--30 dnn" style="display:none;visibility: hidden;">
                                    <div class="icon-email"> | </div>
                                    <input id="email" type="text" class="input-text formemail_disable" name="email" placeholder="Email address *" autocomplete="off"  @if($email) value="{{ $email }}" @else autofocus @endif>
                                </div>
                                <div class="input-box profile mb--30 dnn" style="display:none;visibility: hidden">
                                    <div class="icon-profile"> | </div>
                                    <input id="password" type="password" class="input-text" name="password" placeholder="Password *" autocomplete="off" value="{{ Str::random(20) }}" @if($email) autofocus @endif>
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30 registerbtn" type="submit">Next</button>


                                <div class="lineBox text-center">
                                    <span class="darkgrey">Already have an account?</span><br>
                                    <a href="{{ url('login') }}">Sign in</a>
                                </div>

                            </form>
                        </div>
                </div>
            </div>
        </div>

@endsection
