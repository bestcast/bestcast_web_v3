@extends('myaccount.app')

@section('contentarea')
	<div class="title">
		<h3>Refer a Friend</h3>
	</div>


	<div class="content referBx">
	        <div class="gridset">
				@if(!empty($refer['refer_title'])) <div class="subtitle">{{ $refer['refer_title'] }}</div> @endif

				@if(!empty($user->referal_code))
					<div class="refercode fix">
						<div class="url" id="copyText">{{ App\User::getReferralUrl($user) }}</div>
						<button class="edu-btn" id="copyTextBtn" onclick="copyTextToClipboard()">Copy Url</button>
					</div>
				@endif

				@if(!empty($refer['refer_banner_urlkey'])) <div class="banner"><img src="{{ Lib::img($refer['refer_banner_urlkey']) }}" /></div> @endif

				@if(!empty($refer['refer_content'])) <div class="description">{!! $refer['refer_content'] !!}</div> @endif

				<div class="credits"><div class="in fix">
					<div class="points fix">
						<div class="available">Available Credits: <span>{{ App\User::getReferralCreditsTotal($user) }}</span></div>
						<div class="used">Credits Used: <span>{{ App\User::getReferralCreditsUsed($user) }}</span></div>
					</div>

					@if(!empty($refer['refer_credits'])) 
						@php($list=App\User::getReferralData($user))
						@if(!empty($list) && count($list))
						<table>
						  <tr class="header">
						    <td>Email Address</td>
						    <td>Registered Date</td>
						    <td>Subscription</td>
						    <td>Credits</td>
						  </tr>
						  @foreach($list as $item)
						  <tr>
						    <td>{{ $item->email }}</td>
						    <td>{{ empty($item->created_at)?'':Lib::dateFormat($item->created_at,'','d M Y') }}</td>
						    <td>{{ empty($item->plan)?'Not Yet':"Yes" }}</td>
						    <td>{{ empty($item->plan)?'':$refer['refer_credits'] }}</td>
						  </tr>
						  @endforeach
						</table>
						@endif
					@endif

				</div></div>

				@if(!empty($refer['refer_instruction'])) <div class="instruction">{!! $refer['refer_instruction'] !!}</div> @endif

				<script type="text/javascript">
					function copyTextToClipboard() {
					    var textToCopy = document.getElementById('copyText').innerText;
					    var textarea = document.createElement("textarea");
					    textarea.value = textToCopy;
					    document.body.appendChild(textarea);
					    textarea.select();
					    textarea.setSelectionRange(0, 99999); 
					    document.execCommand('copy');
					    document.body.removeChild(textarea);
					    document.getElementById('copyTextBtn').innerText="Copied";
					    //alert("Text copied to clipboard: " + textToCopy);
					}
				</script>

	        </div>
	</div>
@endsection
