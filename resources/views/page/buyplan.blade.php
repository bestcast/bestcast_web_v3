@extends('layouts.myaccount')

@section('header-script')
@endsection

@section('content') 


    @php($bgurl=empty($meta['banner_background_urlkey'])?'':$meta['banner_background_urlkey'])
    @php($bgimg=empty($bgurl)?'':Lib::publicImgSrc($bgurl))
    <div class="main-wrapper {{ empty($bgimg)?'':'bgimage' }}" @if(!empty($bgimg)) style='background-image:url("{{ $bgimg }}");' @endif>
        <div class="edu-newsletter-area newsletter-style-1 edu-section-gap bg-black">
            <div class="container eduvibe-animated-shape">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="inner text-center">
                            <div class="section-title text-white text-center">

         						<?php 
						     		$name=auth()->user()->firstname." ".auth()->user()->lastname;
						     		$phone=auth()->user()->phone;
						     		$email=auth()->user()->email;

						         	$rpay=Paymentgateway::razorpay('core');
						         	$logo=empty($rpay['razorpay_logo_urlkey'])?Lib::img($core['general_header_logo']):Lib::img($rpay['razorpay_logo_urlkey']);
						     	?>

         						@if(!empty($rpay['buyplan_title']))<h1 class="title">{{ $rpay['buyplan_title'] }}</h1>@endif
         						@if(!empty($rpay['buyplan_content']))<div class="description small text-center">{!! $rpay['buyplan_content'] !!}</div>@endif

					            <!-- <div class="checkout-page-style nameform">
					                    <div class="input-box mb--20">
					                        <input type="text" placeholder="First name*" id="firstname" value="{{ auth()->user()->firstname }}">
					                        <div class="error-msg" id="namemsg"></div>
					                    </div>
					                    <div class="input-box mb--20">
					                        <input type="text" placeholder="Last name" id="lastname" value="{{ auth()->user()->lastname }}">
					                    </div>

										<div class="input-box mb--20">
										    <input type="number" placeholder="Mobile*" id="phone"  value="{{ $phone }}">
										    <div class="error-msg" id="phonemsg"></div>
										</div>

					                    <div class="cart-summary-button-group">
					                    	<a class="edu-btn w-100 text-center cancel" href="{{ url('/pricing') }}">Cancel</a>
					                        <a class="edu-btn w-100 text-center paynow" id="paynowbtn" onclick="validatePayment()">Pay Now</a>
					                    </div>
					            </div> -->

					            <div class="checkout-page-style nameform">
					                    <div class="cart-summary-button-group">
					                    	<a class="edu-btn w-100 text-center cancel" href="{{ url('/pricing') }}">Cancel</a>
					                    </div>
					            </div>

					            @if(!empty($rpay['razorpay_enable']))
					            	@if(empty($trans->razorpay_plan_id))
										<script src = "https://checkout.razorpay.com/v1/checkout.js"></script>
							            <script type="text/javascript">
							            	function validatePayment() {

												var options = {
													"key": "{{ empty($rpay['razorpay_mode'])?$rpay['razorpay_test_key']:$rpay['razorpay_live_key'] }}",
													"order_id": "{{ $trans->razorpay_order_id }}",
													"name": "{{ empty($rpay['razorpay_company_name'])?env('APP_NAME'):$rpay['razorpay_company_name'] }}",
													"description": "{{ $trans->title }}",
													"image": "{{ $logo }}",
													"handler": function(response) {
														console.log(response);
														if(response.razorpay_order_id){
															var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
													        const url="{{ url('/updatetransaction') }}";
													        var options='';
						                                    options = {
														                method: 'POST',
														                headers: {
														                    'X-CSRF-TOKEN': csrfToken,
														                    'Accept': 'application/json',
														                    'Content-Type': 'application/json'
														                }
															        };
						                                    var addnewdata ='';
						                                    addnewdata = {	
						                                    	razorpay_payment_id: response.razorpay_payment_id, 
						                                    	razorpay_order_id: response.razorpay_order_id,
						                                    	razorpay_signature: response.razorpay_signature 
						                                    };
						                                    options.body = JSON.stringify(addnewdata);
						                                    fetchDataWithRetry(url, options,1).then(response => response.json())
						                                    .then(data => {
					                                			window.location.href="{{ url('/payment-status') }}?oid={{ $trans->razorpay_order_id }}";
						                                    }).catch(error => { console.log('Error:'+error);});
					                                	}else{
					                                		window.location.href="{{ url('/payment-status') }}?oid={{ $trans->razorpay_order_id }}";
					                                	}
													},
													//"callback_url": "{{ url('/buyplanstatus') }}",
													"prefill": {
														"name": "<?php echo $name;?>",
														"contact": "+91<?php echo $phone;?>"
													},
													"notes": {
														"tansaction_id": "{{ $trans->id }}"
													},
													"theme": {
														"color": "{{ empty($rpay['razorpay_colorcode'])?'#000':$rpay['razorpay_colorcode'] }}"
													},
													"modal": {
														"ondismiss": function(){
												            window.location.href="{{ url('/pricing') }}";
												        }
												    }
												};

												var rzp1 = new Razorpay(options);
												rzp1.open();
												//e.preventDefault();

										    }

										    setTimeout(function(){
							            		validatePayment();
										    },1000);
							            </script>
					            	@else
										<script src = "https://checkout.razorpay.com/v1/checkout.js"></script>
							            <script type="text/javascript">
							            	function validatePayment() {

									            // var paynowbtn_status = document.getElementById('paynowbtn').getAttribute('disabled');
												// if(paynowbtn_status=='disabled'){
												// 	return false;
												// }

												/*
										        var firstname = document.getElementById('firstname').value.trim();
										        var lastname = document.getElementById('lastname').value.trim();
										        var phone = document.getElementById('phone').value.trim();

										        var error=0;
										        if (firstname === '') {
										            document.getElementById('namemsg').innerHTML='Firstname is required.';
										            error=1;
										        }
										        if (phone === '') {
										            document.getElementById('phonemsg').innerHTML='Mobile number is required.';
										            error=1;
										        }else{
											        if (phone.length !== 10 || isNaN(phone)) {
											            document.getElementById('phonemsg').innerHTML='Please enter 10-digit mobile number.';
											            error=1;
											        }
										    	}
										        if(error){	return false;}

										        document.getElementById('paynowbtn').setAttribute('disabled', 'disabled');
										        document.getElementById('paynowbtn').innerHTML='<div class="spinner"></div>';
									            document.getElementById('phonemsg').innerHTML='';
									            document.getElementById('namemsg').innerHTML='';


										        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
										        const url="{{ url('/updateuser') }}";
										        var options='';
			                                    options = {
											                method: 'POST',
											                headers: {
											                    'X-CSRF-TOKEN': csrfToken,
											                    'Accept': 'application/json',
											                    'Content-Type': 'application/json'
											                }
												        };
			                                    var addnewdata ='';
			                                    addnewdata = {	firstname: firstname, lastname: lastname,phone: phone };
			                                    options.body = JSON.stringify(addnewdata);
			                                    fetchDataWithRetry(url, options,1).then(response => response.json())
			                                    .then(data => {


			                                    	if(data.data.id){
			                                    		var data=data.data;
			                                    */
														var options = {
															"key": "{{ empty($rpay['razorpay_mode'])?$rpay['razorpay_test_key']:$rpay['razorpay_live_key'] }}",
															"subscription_id": "{{ $trans->razorpay_subscription_id }}",
															"name": "{{ empty($rpay['razorpay_company_name'])?env('APP_NAME'):$rpay['razorpay_company_name'] }}",
															"description": "{{ $trans->title }}",
															"image": "{{ $logo }}",
															"handler": function(response) {
																if(response.razorpay_subscription_id){
																	var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
															        const url="{{ url('/updatetransaction') }}";
															        var options='';
								                                    options = {
																                method: 'POST',
																                headers: {
																                    'X-CSRF-TOKEN': csrfToken,
																                    'Accept': 'application/json',
																                    'Content-Type': 'application/json'
																                }
																	        };
								                                    var addnewdata ='';
								                                    addnewdata = {	
								                                    	razorpay_payment_id: response.razorpay_payment_id, 
								                                    	razorpay_subscription_id: response.razorpay_subscription_id,
								                                    	razorpay_signature: response.razorpay_signature 
								                                    };
								                                    options.body = JSON.stringify(addnewdata);
								                                    fetchDataWithRetry(url, options,1).then(response => response.json())
								                                    .then(data => {
							                                			window.location.href="{{ url('/payment-status') }}?sid={{ $trans->razorpay_subscription_id }}";
								                                    }).catch(error => { console.log('Error:'+error);});
							                                	}else{
							                                		window.location.href="{{ url('/payment-status') }}?sid={{ $trans->razorpay_subscription_id }}";
							                                	}
															},
															//"callback_url": "{{ url('/buyplanstatus') }}",
															"prefill": {
																"name": "<?php echo $name;?>",
																"email": "<?php echo $email;?>",
																"contact": "+91<?php echo $phone;?>"
															},
															// "prefill": {
															// 	"name": data.name,
															// 	"email": data.email,
															// 	"contact": "+91"+data.phone
															// },
															"notes": {
																"tansaction_id": "{{ $trans->id }}"
															},
															"theme": {
																"color": "{{ empty($rpay['razorpay_colorcode'])?'#000':$rpay['razorpay_colorcode'] }}"
															},
															"modal": {
																"ondismiss": function(){
														            window.location.href="{{ url('/pricing') }}";
														        }
														    }
														};

														var rzp1 = new Razorpay(options);
														rzp1.open();
														//e.preventDefault();

			                                    /*
			                                    	}else{
			                                    		document.getElementById('paynowbtn').innerHTML='Pay Now';
				                                    	document.getElementById('paynowbtn').setAttribute('disabled', '');
			                                    	}
			                                    }).catch(error => { console.log('Error:'+error);});
										    */

										    }

										    setTimeout(function(){
							            		validatePayment();
										    },1000);
							            </script>
							            {!! Lib::loadcore(); !!}
						            @endif
					            @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- 4718 6091 0820 4366 -->

@endsection
