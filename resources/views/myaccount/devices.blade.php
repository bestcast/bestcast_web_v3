@extends('myaccount.app')

@section('contentarea')
	<div class="title">
		<h3>Manage Access and Devices</h3>
	</div>


	<div class="content">
	        <div class="gridset">
				<div class="subtitle">Active Devices</div>
				<p>These signed-in devices have recently been active on this account. You can sign out any unfamiliar devices or change your password for added security.</p>

				@if(!empty($message)) <div class="error-box">{{ $message }}</div> @endif

				<div class="row g-5 device">
					@foreach($device as $item)

					<?php
					$device=array('pc','Unknown Device');
					if(!empty($item->device)){
						$device=Lib::browser($item->device);
					}
					?>
					<div class="col-lg-6 col-md-6 col-12">
		                <div class="edu-blog blog-type-2 variation-2 radius-small bg-color-gray">
		                    <div class="inner">
		                        <div class="content">
		                            <div class="blog-date-status {{ $device[0] }}"> </div>
                                    <button class="whitebtn signoutbtn-{{ $item->token_id }}" onclick="signout('{{ $item->token_id }}')">Sign Out</button>
                                    <div class="tag tag-{{ $item->token_id }} dnn">Current Device</div>
		                            <div class="status-group status-style-5">
		                            	@php($logindata=empty($item->last_login)?$item->updated_at:$item->last_login)
		                                @if(!empty($logindata))
		                                	<span class="eduvibe-status status-05">{{ Lib::dateFormat($logindata,'','d/m/Y h:i a') }}</span>
		                                @endif

		                                @if(!empty($item->profile))
		                                	<span class="eduvibe-status status-05">{{ $item->profile->name }} (Last watched)</span>
		                                @else
		                                	<span class="eduvibe-status status-05">No Profile to show</span>
		                                @endif

		                            </div>
	                            	<h5 class="title">{{ $device[1] }}</h5>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            @endforeach


		            <script type="text/javascript">

						var valueParts = localStorage.getItem("tokenEncrypted").split('|');
						var firstPart = valueParts[0];
						document.querySelector('.signoutbtn-'+firstPart).classList.add('dnn');
						document.querySelector('.tag-'+firstPart).classList.remove('dnn');

		            	function signout(id) {
		            		var btnelem=document.querySelector('.signoutbtn-'+id);
				            var paynowbtn_status = btnelem.getAttribute('disabled');
							if(paynowbtn_status=='disabled'){
								return false;
							}							

					        btnelem.setAttribute('disabled', 'disabled');
					        btnelem.innerHTML='<div class="spinner"></div>';


					        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
					        const url="{{ url('/account/logoutuser') }}"+"/"+id;
					        var options='';
                            options = {
						                method: 'POST',
						                headers: {
						                    'X-CSRF-TOKEN': csrfToken,
						                    'Accept': 'application/json',
						                    'Content-Type': 'application/json'
						                }
							        };
                            fetchDataWithRetry(url, options,4).then(response => response.json())
                            .then(data => {
                            	console.log(data);
                            	if(data.status=='success'){
                            		var btnelem=document.querySelector('.signoutbtn-'+data.results);
                        			btnelem.innerHTML='Succeed';
                            	}else{
                            		var btnelem=document.querySelector('.signoutbtn-'+data.results);
                        			btnelem.innerHTML='Failed';
                            	}
                            }).catch(error => { console.log('Error:'+error);});
					    }
		            </script>

	            </div>

	        </div>
	</div>
@endsection
