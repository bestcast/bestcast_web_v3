
@if(!empty($meta['enable_pricing']))
<div class="pricingBx">
	<div class="container">

		@if(!empty($meta['pricing_subtitle']) || !empty($meta['pricing_title']))
		<div class="row g-5">
		    <div class="col-lg-12 col-md-12">
		        <div class="section-title text-start">
		            @if(!empty($meta['pricing_subtitle']))	<span class="pre-title">{{ $meta['pricing_subtitle'] }}</span> @endif
		            @if(!empty($meta['pricing_title']))<p>{{ $meta['pricing_title'] }}</p> @endif
		        </div>
		    </div>
		</div>
		@endif

		@php($pricemodel=App\Models\Subscription::getApiList())
		@if(!empty($pricemodel->total()))
		<div class="row g-5 mt--20 mb--20">
			@foreach($pricemodel->items() as $item)
	        <!-- Start Pricing Table  -->
	        <div class="col-xl-3 col-lg-6 col-md-6 col-12" >
	            <div class="pricing-table @if($item->tagtext) active @endif">
	                <div class="pricing-header">
	                	@if($item->tagtext)
	                    	<div class="edu-badge"><span>{{ $item->tagtext }}</span></div>
	                    @endif
	                    <h3 class="title">{{ $item->title }}</h3>
	                    <div class="price-wrap">
	                        <div class="yearly-pricing">

			                    @if(!empty($item->before_price) && $item->before_price>$item->price)
			                        <span class="amount strike">₹{{ $item->before_price }}</span>
			                    @endif
	                            <span class="amount">₹{{ $item->price }}</span>
	                            <span class="duration">/{{ App\Models\Subscription::getDurationText($item) }}</span>
	                        </div>
	                    </div>
	                </div>
	                <div class="pricing-body">
	                    @if(!empty($item->content))
	                        <div class="content">
	                            {!! $item->content !!}
	                        </div>
	                    @endif
	                </div>
	                <div class="pricing-btn">
	                    <a class="edu-btn @if(!$item->tagtext) btn-dark @endif" href="{{ route('buyplan',$item->id) }}">Buy This Plan<i class="icon-arrow-right-line-right"></i></a>
	                </div>
	            </div>
	        </div>
	        <!-- End Pricing Table  -->
	        @endforeach
	    </div>
	    @endif


    </div>

	@if(!empty($meta['pricing_content']))
		<div class="container">
			<div class="row g-5">
			    <div class="col-lg-12 col-md-12">
			        <div class="notetext">
			            {!! $meta['pricing_content'] !!}
			        </div>
			    </div>
			</div>
	    </div>
	@endif
</div>

<?php
$user=auth()->user();
//check payment and update to user plan start
if(!empty($user) && $user->plan==0){
	$trans=App\Models\Transaction::getActive($user);
	if(!empty($trans->razorpay_subscription_id)){
	    $razorResponse=App\Models\Transaction::updatePlanToUser($user,$trans->razorpay_subscription_id);
	}
}
//check payment and update to user plan end
?>
@endif
