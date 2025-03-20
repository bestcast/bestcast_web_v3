@if(!empty($urlkey) && ($urlkey=='movies' || $urlkey=='new' || $urlkey=='popular'))
<?php
	$urlGuest='';
	$user=Auth::user();
	if(empty($user)){$urlGuest='/guest';}
?>
		<div class="genreHeader">
			<div class="container-fluid">
				<div class="row">
		         <div class="col-8 col-lg-8">
		         	<div class="label">{{ empty($post->title)?'Movies':$post->title }}</div>
		         	@if($urlkey=='movies')
					@php($genrelist=App\Models\Genres::getApiList())
					@if(!empty($genrelist) && count($genrelist))
			         	<div class="dvDropDw">
			         		<div class="bxLabel ICdown">{{ empty($genre->id)?'Genres':$genre->title }}</div>
			         		<div class="bxList cfix">
			         			<?php
			         				$totalRecords = $genrelist->count();
									$recordsPerRow = ceil($totalRecords / 3);
		         					$chunks=$genrelist->chunk($recordsPerRow);
		         				?>
			         			@foreach ($chunks as $chunk)
		         					<ul>
				         				@foreach ($chunk as $item)
				         					<li><a class="{{ (!empty($genre->id) && ($genre->id==$item->id))?'active':'' }}" href="{{ url($urlGuest.'/movies/genre/'.$item->id) }}">{{ $item->title }}</a></li>
									    @endforeach
		         					</ul>
			         			@endforeach
			         		</div>
			         	</div>
		         	@endif
		         	@endif
		         </div>
		         <div class="col-4 col-lg-4">
		         	@php($languageslist=App\Models\Languages::getApiList())
					@if(!empty($languageslist) && count($languageslist))
			         	<div class="dvDropDw languagelist">
			         		<div class="bxLabel ICdown">{{ empty($language->id)?'Languages':$language->title }}</div>
			         		<div class="bxList cfix">
	         					<ul>
		         					<li><a class="{{ empty($language->id)?'active':'' }}" href="{{ url($urlGuest.'/movies/language/0') }}">All</a></li>
			         				@foreach ($languageslist as $item)
			         					<li><a class="{{ (!empty($language->id) && ($language->id==$item->id))?'active':'' }}" href="{{ url($urlGuest.'/movies/language/'.$item->id) }}">{{ $item->title }}</a></li>
								    @endforeach
	         					</ul>
			         		</div>
			         	</div>
					@endif
		         </div>
		        </div>
	        </div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.blkCtr').addClass("haveFilter");
			});
		</script>	
@endif
