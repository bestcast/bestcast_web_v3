@extends('myaccount.app')

@section('contentarea')
	<div class="title">
		<h3>Account</h3>
	</div>


	<?php
	$user=auth()->user();
	//check payment and update to user plan start
	if(!empty($user)){
		$trans=App\Models\Transaction::getActive($user);
		if(!empty($trans->razorpay_subscription_id)){
		    $razorResponse=App\Models\Transaction::updatePlanToUser($user,$trans->razorpay_subscription_id);
		}
		if(!empty($trans->razorpay_order_id)){
		    $razorResponse=App\Models\Transaction::updateOrderToUser($user,$trans->razorpay_order_id);
		}
	}
	//check payment and update to user plan end
	?>

	<div class="content">
	        <div class="gridset">
				<div class="subtitle">Membership details</div>

				<div class="wbox">
					<div class="tag-gradient">Member since {{ date("M Y",strtotime($user->created_at)) }}</div>
					<div class="lineinfo">
						@if(!empty($trans->title))
							<div class="big">{{ $trans->title }} Plan</div>
						@endif
						@if(!empty($razorResponse->charge_at) && !empty($trans->razorpay_subscription_id))
						<div class="medium">Next Payment: {{ date("d M Y ",$razorResponse->charge_at) }}</div>
						<div class="medium">Payment Type: {{ ucwords($razorResponse->payment_method) }}</div>
						<div class="large">Status: <div class="tag @if($razorResponse->status=='active') green @endif"> {{ ucwords($razorResponse->status) }}</div></div>
						@else
							<?php
							if(!empty($user->plan)):
								$plan=App\Models\Subscription::getById($user->plan);
							endif;
							?>

							@if(empty($trans->title))
								<div class="big">{{ empty($plan->title)?'No':$plan->title }} Plan</div>
							@endif
								@if(!empty(auth()->user()->plan_expiry) && (strtotime(auth()->user()->plan_expiry) > strtotime(date('Y-m-d H:i:s', strtotime('-12 hours')))))
									<div class="medium">Current Membership end at: {{ date("d M Y ",strtotime(auth()->user()->plan_expiry)) }}</div>
								@else
									<div class="medium">Current Membership expired.</div>
									<br>
									<a class="edu-btn" href="{{ url('/pricing') }}">Choose Plan<i class="icon-arrow-right-line-right"></i></a>
									<br>
								@endif
						@endif
					</div>
					<div class="linkinfo">
<!-- Enable001 --> <a href="{{ route('user.myaccount.membership') }}">Manage Membership</a>
					</div>
				</div>

				<div class="subtitle">Profile information</div>
				<div class="wbox">
					<div class="lineinfo">
						<div class="big">{{ $user->title }} {{ $user->firstname }}  {{ $user->lastname }}</div>
						@if(!empty($razorResponse->charge_at))
						<div class="medium">Email: {{ $user->email }}</div>
						<div class="medium">Mobile: {{ $user->phone }}</div>
						<div class="medium">Date of Birth: {{ $user->dob }}</div>
						<div class="medium">Gender: {{ $user->gender }}</div>
						@endif
					</div>
					<div class="linkinfo">
<!--  Enable001 --> <a href="{{ route('user.myaccount.profilesave') }}">Manage Account</a>
					</div>
				</div>

	        </div>
	        <?php /*
	        @if (!auth()->user()->isAdmin())
	        <div class="gridset">
				<h4>Newsletter</h4>
	            <div class="in">
	                <div class="grid">
		               	<div class="hLTGrid">
		                    <div class="hLTColm hLTColm100">
                                <div>
                                	@if(!empty($user->subscribe)) 
                                		Subscribed
                                	@else
                                		Un-Subscribed
                                	@endif
                                </div>
	               				<a href="{{ route('user.myaccount.profilesave') }}" class="hLTBtn tiny grey">Edit</a>
		                    </div>
		                </div>
	                </div>
	            </div>
	        </div>
	        @endif
	        */ ?>
	</div>
@endsection
