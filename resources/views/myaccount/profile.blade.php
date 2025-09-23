@extends('myaccount.app')

@section('contentarea')
	<div class="title">
		<h2>Profile</h2>
	</div>



	@if (auth()->user()->isAdmin())
	<form method="POST" action="{{ route('admin.myaccount.profilesave') }}" class="wpcf7 wpcf7-form" autocomplete="off">  @csrf
	@else
	<form method="POST" action="{{ route('user.myaccount.profilesave') }}" class="wpcf7 wpcf7-form" autocomplete="off">  @csrf
	@endif

	<div class="content">
	        <div class="gridset">
				<div class="subtitle">Account Information</div>

				<div class="wbox">
					<div class="grid">
	                    <div class="hLTGrid">
	                        <div class="hLTColm hLTColm20 form-group">
	                            <label>Title</label>
	                            @php ($val=old('title',$user->title))
	                            <select class="selectbox-default" name="title">
	                              @php ($ls=array("Ms.","Mrs.","Miss.","Mr.","Dr.","Mx."))
	                              @foreach($ls as $item)
	                                <option value="{{ $item }}" @if($val==$item) selected="selected" @endif>{{ $item }}</option>   
	                              @endforeach 
	                            </select>
	                        </div>
	                        <div class="hLTColm hLTColm40 form-group">
	                            <label>First Name<em>*</em></label>
	                            <input type="text" required class="form-control" name="firstname" value="{{ old('firstname',$user->firstname) }}">
	                        </div>
	                        <div class="hLTColm hLTColm40 form-group">
	                            <label>Last Name</label>
	                            <input type="text" class="form-control" name="lastname" value="{{ old('lastname',$user->lastname) }}">
	                        </div>
	                    </div>
	                </div>

	                <div class="grid">
	                    <div class="hLTGrid dnn">
	                        <div class="hLTColm hLTColm70 form-group">
	                            <label>Email<em>*</em> 
	                            	@if($user->email)
		                            	@if($user->email_verified_at)
		                            		<span class="verified">&#10004; Verified</span>
		                            	@else
		                            		<span class="verified warning">&times; Not Verified</span>
		                            	@endif
	                            	@endif
	                            </label>
	                            <input type="text" required class="form-control" name="email" value="{{ old('email',(empty($user->email)?'':$user->email)) }}" placeholder="Enter email address" >
	                            <p class="comments">Note: To change your email address, OTP verification is <span class="warning">required during login.</span> <br> To verify your email, sign out and sign in again using your email address.</p>
	                        </div>
                        	@if($user->email && !$user->email_verified_at)
		                        <!-- <div class="hLTColm hLTColm30 form-group">
		                            <label>&nbsp;</label>
		                            <a href="{{ route('otp.verification') }}?send" class="edu-btn full  text-center" name="update" >Verify Email</a>
		                        </div> -->
                        	@endif
	                    </div>
	                    <div class="hLTGrid">
	                        <div class="hLTColm hLTColm70 form-group">
	                            <label>Mobile<em>*</em> 
	                            	@if($user->phone)
		                            	@if($user->phone_verified_at)
		                            		<span class="verified">&#10004; Verified</span>
		                            	@else
		                            		<span class="verified warning">&times; Not Verified</span>
		                            	@endif
	                            	@endif
	                            </label>
	                            <input type="text" style="background:#FFF;border: 0;padding-left: 0px;font-size: 30px;outline: 0;box-shadow: none;" readonly required class="form-control" name="phone" value="{{ old('phone',(empty($user->phone)?'':$user->phone)) }}" placeholder="Enter mobile number" >
	                            <!-- <p class="comments">Note: To change your mobile number, OTP verification is <span class="warning">required during login.</span> <br> To verify your mobile, sign out and sign in again using your mobile number.</p> -->
	                        </div>
                        	@if($user->phone && !$user->phone_verified_at)
		                       <!--  <div class="hLTColm hLTColm30 form-group">
		                            <label>&nbsp;</label>
		                            <a href="{{ route('otp.verification') }}?phone&send" class="edu-btn full text-center" name="update" >Verify Mobile</a>
		                        </div> -->
                        	@endif
	                    </div>
	                    <div class="hLTGrid">
	                        <div class="hLTColm hLTColm50 form-group">
	                            <label>Date of birth<em>*</em></label>
	                            <input type="text" required class="form-control" name="dob" value="{{ old('dob',(empty($user->dob)?'':Lib::dateFormat($user->dob,'Y-m-d','d/m/Y'))) }}" placeholder="dd/mm/yyyy" >
	                        </div>
	                    </div>
	                    <div class="hLTGrid">
	                        <div class="hLTColm hLTColm50 form-group">
	                            <label>Gender<em>*</em></label>
	                            @php ($val=old('gender',$user->gender))
	                            <select class="selectbox-default" name="gender">
	                              @php ($ls=array(""=>"Select","Female"=>"Female","Male"=>"Male","Other"=>"Other"))
	                              @foreach($ls as $key=>$item)
	                                <option value="{{ $key }}" @if($val==$key) selected="selected" @endif>{{ $item }}</option>   
	                              @endforeach 
	                            </select>
	                        </div>
	                    </div>
	                </div>
	                

				</div>

				<div class="subtitle" style="display: none;">Access Information</div>

				<div class="wbox" style="display: none;">
	                <div class="grid">
		               	<div class="hLTGrid">
		                    <div class="hLTColm hLTColm100 form-group">
		                        <label>{{ __('Old Password') }} <em>*</em></label>
		                        <input type="password" class="form-control" name="oldpassword" />
		                    </div>
		                    <div class="hLTColm hLTColm50 form-group">
		                        <label>{{ __('Password') }} <em>*</em></label>
		                        <input type="password" class="form-control" name="password" />
		                    </div>
		                    <div class="hLTColm hLTColm50 form-group">
		                        <label>{{ __('Confirm Password') }} <em>*</em></label>
		                        <input type="password" class="form-control" name="password_confirmation"  >
		                    </div>
		                </div>
	                </div>

				</div>


	            <button type="submit" class="edu-btn full" name="update" >Update</button>
	        </div>
	</div>

	</form>




	@if (auth()->user()->isAdmin())
	@else
	<form method="POST" action="{{ route('deleteuseraccount') }}" class="wpcf7 wpcf7-form" autocomplete="off">  @csrf

	<div class="content">
	        <div class="gridset">
				<div class="subtitle">Permanent Account Removal</div>

				<div class="wbox">
	                <div class="grid">
		               	<div class="hLTGrid">
		               		Once you delete your account, the action is permanent and cannot be reversed. All your data, subscription, settings, and personal information, will be permanently erased. Recovery will not be possible after deletion.<br><br>
		                </div>
	                </div>
	            	<button type="submit" class="edu-btn" name="delete" >Delete Account</button>
				</div>
	        </div>
	</div>

	</form>
	@endif


	        <?php 
	        /*@if (!auth()->user()->isAdmin())
	        <div class="gridset">
				<h4>Newsletter</h4>
	            <div class="in">
	                <div class="grid">
		               	<div class="hLTGrid">
		                    <div class="hLTColm hLTColm100 form-group">
                                <div class="comment-form-consent">
                                    <input id="checkbox-subscribe" type="checkbox" name="subscribe" @if(!empty($user->subscribe)) checked @endif /> 
                                    <label for="checkbox-subscribe">Subscribe</label>
                                </div>
		                    </div>
		                </div>
	                </div>
	            </div>
	        </div>
	        @endif
	        */ ?>
	        


@endsection
