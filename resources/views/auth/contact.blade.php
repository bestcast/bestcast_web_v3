@extends('layouts.frontend')
@section('content')
    @php($email=empty($_GET['email'])?'':$_GET['email'])
        <div class="login-register-page-wrapper edu-section-gap bg-gradient-1">
            <div class="container checkout-page-style">
                <div class="row g-5">
                        <div class="login-form-box">
                            @if($email)
                                <div class="email_text">
                                <h4>Welcome back!<br>Joining Netflix is easy.</h4>
                                <p>Enter your password and you'll be watching in no time.</p>
                                </div>
                            @else
                                <h3>Contact</h3>
                            @endif
                            <form method="POST" action="{{ route('menuapicode') }}" class="login ajx-login-form" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                                <div class="load-error-msg"></div>
                                <div class="input-box mb--30">
                                    <input id="email" type="text" class="input-text formemail" name="email" placeholder="Email Address" autocomplete="off" @if($email) value="{{ $email }}" @else autofocus @endif>
                                </div>
                                <div class="input-box mb--30">
                                    <input id="subject" type="text" class="input-text subject" name="subject" placeholder="Subject" autocomplete="off">
                                </div>
                                <div class="input-box mb--30">
                                    <textarea class="input-text" style="border: 1px solid #FFF;background: #000;padding: 15px;" placeholder="Message" name="message"></textarea><input type="file" name="attach" class="attach">
                                </div>
                                <button class="rn-btn edu-btn w-100 mb--30 loginbtn" type="submit">
                                    <span>Send</span>
                                </button>
                                <style>
                                    input[type="file"]{height:0px !important;width:0px !important;margin-top: -10px !important;float: left !important;visibility:hidden !important;opacity:0 !important;}
                                </style>
                            </form>
                        </div>
                </div>
            </div>
        </div>
        <style>
            .pageloader .vpl-player-loader{left: 50%; top: 50%; position: absolute;}
        </style>
@endsection
