@extends('layouts.frontend')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

@section('header-script')
<script src="{{ asset('js/auth/tokenexist.js') }}?v=1" defer></script>
<script src="{{ asset('js/auth/send-otp.js') }}?v=1" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
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
                                    <input id="email" type="tel" class="input-text fieldtab-field formemail_disable w-100" name="email"
                                           placeholder="Enter mobile number" autocomplete="off" autofocus>
                                    <input type="hidden" name="country_code" id="country_code"> 
                                    <!-- hidden field -->
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
@section('footer-script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const input = document.querySelector("#email");
    if (!input) return;

    const iti = window.intlTelInput(input, {
    initialCountry: "auto",
    geoIpLookup: function(callback) {
      fetch("https://ipapi.co/json/")
        .then(res => res.json())
        .then(data => callback(data.country_code ? data.country_code.toLowerCase() : "in"))
        .catch(() => callback("in"));
    },
    separateDialCode: true,
    preferredCountries: ["in", "us", "gb", "ae", "sg"],
    allowDropdown: true,
    showSearch: true,
    customPlaceholder: function() { return "Enter mobile number"; },
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });

    // Update hidden input whenever user changes country
    input.addEventListener("countrychange", function() {
    const countryCode = "+" + iti.getSelectedCountryData().dialCode;
    document.querySelector("#country_code").value = countryCode;
    });

    // Initialize default country code
    setTimeout(() => {
    const countryCode = "+" + iti.getSelectedCountryData().dialCode;
    document.querySelector("#country_code").value = countryCode;
    }, 1000);
});
</script>
@endsection



