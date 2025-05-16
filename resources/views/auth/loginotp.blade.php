@extends('layouts.frontend')

@section('header-script')
@guest 
<script src="{{ asset('js/auth/tokenexist.js') }}?v=1" defer></script>
@else
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/movies-new.js') }}?v=1" defer></script>
@endguest

<script src="{{ asset('js/auth/login-otp.js') }}?v=1" defer></script>
<script src="{{ asset('js/auth/send-otp.js') }}?v=1" defer></script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            if (this.value.length === 1) {
                if (index < otpInputs.length - 1) {
                    document.getElementById("otp-a").disabled = true;
                    document.getElementById("otp-b").disabled = true;
                    document.getElementById("otp-c").disabled = true;
                    document.getElementById("otp-d").disabled = true;
                    otpInputs[index + 1].disabled = false;
                    otpInputs[index + 1].focus();
                } else {
                    document.getElementById('otp').value=
                            document.getElementById('otp-a').value+
                            document.getElementById('otp-b').value+
                            document.getElementById('otp-c').value+
                            document.getElementById('otp-d').value;

                    var otpForm = document.getElementById("loginotpbtn");
                    otpForm.click();
                }
            }
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && index > 0 && this.value.length === 0) {
                document.getElementById('otp').value='';
                document.getElementById("otp-a").disabled = true;
                document.getElementById("otp-b").disabled = true;
                document.getElementById("otp-c").disabled = true;
                document.getElementById("otp-d").disabled = true;
                otpInputs[index - 1].disabled = false;
                otpInputs[index - 1].focus();
                otpInputs[index - 1].select();
            }
        });
    });
});
</script>
@endsection

@section('content')
        <style type="text/css">
            .pageloader{display: none;}
        </style>
        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            <h4>Enter the 4-digit code</h4>
                            @guest 
                                <form method="POST" action="{{ route('api.login.otp') }}" id="ajx-login-form" class="login ajx-login-form" autocomplete="off">
                            @else
                                <form method="POST" action="{{ route('api.user.login.otp') }}" id="ajx-login-form" class="login ajx-login-form" autocomplete="off">
                            @endguest

                                @csrf
                                <div class="load-error-msg">
                                    @if(isset($_GET['sent']))
                                    <div class="warning-box">Re-sent the 4-digit OTP.</div>
                                    @endif
                                </div>


                                <div class="input-box mb--30">
                                    <p class="darkgrey">Code sent to <span class="formemail_text">
                                        @guest 
                                        @else
                                        {{ isset($_GET['phone'])?Auth::user()->phone:Auth::user()->email }}
                                        @endguest
                                    </span>. Expires in 15 minutes.</p>
                                    <div class="text-center">
                                        <div class="otpboxes">
                                            <input type="text" maxlength="1" class="otp-input" id="otp-a" autofocus />
                                            <input type="text" maxlength="1" class="otp-input" id="otp-b" disabled="disabled" />
                                            <input type="text" maxlength="1" class="otp-input" id="otp-c" disabled="disabled" />
                                            <input type="text" maxlength="1" class="otp-input" id="otp-d" disabled="disabled" />
                                        </div>
                                        
                                        @guest 
                                            <input id="email" type="hidden" class="input-text formemail" name="email" placeholder="Email or mobile number" readonly autocomplete="off" value="" />
                                        @else
                                            <input id="email" type="hidden" class="input-text formemail" name="email" placeholder="Email or mobile number" readonly autocomplete="off" value="{{ isset($_GET['phone'])?Auth::user()->phone:Auth::user()->email }}" />
                                        @endguest
                                        <input id="otp" type="hidden" readonly class="input-text" name="otp" autocomplete="off" />
                                    </div>
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30 loginbtn" id="loginotpbtn" type="submit">
                                    <span>Proceed</span>
                                </button>

                            </form>


                            @guest 
                                <form method="POST" action="{{ route('api.send.otp') }}" data-otp-url="{{ route('login.otp') }}?sent" class="login ajx-sendotp-form" autocomplete="off">
                                    <input id="email" type="hidden" class="input-text formemail" name="email" readonly autocomplete="off">
                                    <input type="hidden" id="otp_message_type" name="otp_message_type" value={{session()->get('otp_message_type')}}>
                            @else
                                <form method="POST" action="{{ route('api.send.otp') }}" data-otp-url="{{ route('otp.verification') }}?sent&{{ isset($_GET['phone'])?'phone':'' }}" class="login ajx-sendotp-form" autocomplete="off">
                                    <input id="email" type="hidden" class="input-text formemail" name="email" readonly autocomplete="off" value="{{ isset($_GET['phone'])?Auth::user()->phone:Auth::user()->email }}">
                                    <input type="hidden" id="otp_message_type" name="otp_message_type" value={{session()->get('otp_message_type')}}>                                       
                            @endguest

                                @csrf
                                <div class="lineBox text-center">

                                    <span class="darkgrey">Did not receive a code?</span><br>
                                    <button class="btnlink" type="submit"><span>Resend Code</span></button> &nbsp; @guest | &nbsp; <a href="{{ url('login') }}">Switch account</a> @else  @endguest
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
@endsection
