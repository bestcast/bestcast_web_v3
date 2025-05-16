@extends('layouts.frontend')

@section('header-script')
<script src="{{ asset('js/auth/tokenexist.js') }}?v=1" defer></script>
<script src="{{ asset('js/auth/send-otp.js') }}?v=1" defer></script>
@endsection

@section('content')

    @php($email=empty($_GET['email'])?'':$_GET['email'])

        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            <h3>SIGNIN</h3>
                            <form method="POST" action="{{ route('api.send.otp') }}" data-otp-url="{{ route('login.otp') }}" class="login ajx-sendotp-form" autocomplete="off">
                                @csrf
                                <div class="load-error-msg"></div>
                                <div class="mb--30">
                                    <span><h6>Choose any one to receive an OTP message.</h6></span>
                                </div>
                                <div class="form-check form-check-inline mb--30">
                                  <input class="form-check-input" type="radio" name="otp_message_type" id="sms" value="sms">
                                  <label class="form-check-label" for="sms">SMS</label>
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="form-check form-check-inline mb--30">
                                  <input class="form-check-input" type="radio" name="otp_message_type" id="whatsapp" value="whatsapp" checked>
                                  <label class="form-check-label" for="whatsapp">Whatsapp</label>
                                </div>
                                <div class="fieldtab dnn">
                                    <div class="fieldtab-phone active">Mobile</div> | <div class="fieldtab-email">Email</div>
                                </div>
                                <div class="input-box fieldtab-action phone mb--30">
                                    <div class="icon-phone"> +91 | </div>
                                    <div class="icon-email"> | </div>
                                    <input id="email" type="number" class="input-text fieldtab-field formemail_disable" name="email" placeholder="Enter mobile number" autocomplete="off" autofocus>
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30 loginbtn" type="submit">
                                    <span>Next</span>
                                </button>
                            </form>

                            <div class="lineBox text-center">
                                <span class="darkgrey">Are you a New User?</span>
                                <br>
                                <a href="{{ route('register') }}" class="createlink">Register Now</a>
                            </div>

                        </div>
                </div>
            </div>
        </div>
@endsection
