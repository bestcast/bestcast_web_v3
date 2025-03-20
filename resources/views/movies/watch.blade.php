@extends('layouts.frontend')

@section('header-script')
<div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>
<style>
   .vpl-lightbox-wrap .vpl-lightbox-close{display: none !important;}
</style>
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/movies-new.js') }}?v=1" defer></script>
@endsection

@section('content') 
  <div class="ajxProfile"></div>
  <div id="wrapper" class="vpl-skin-aviva vpl-customized"></div>

  <style type="text/css">
    .vpl-settings-menu .vpl-quality-menu .vpl-menu-item.vpl-btn-reset{display: none;}
    .vpl-settings-menu .vpl-quality-menu .vpl-menu-item.vpl-btn-reset.vpl-menu-active{display: block;}
  </style>

  <?php
  $referurl=empty($_GET['refer'])?url('/browse'):$_GET['refer'];
  $usermovie=(empty($movie->usermovies[0]))?'':$movie->usermovies[0];

  $subtitles=[];$issetActive=0;
  if(!empty($movie->subtitle)){
    foreach($movie->subtitle as $item){
      $list=array();
      $list['label']=$item->label;
      $list['src']=$item->url;

      if(!empty($item->is_active) && empty($issetActive)){
        $issetActive=1;
        $list['active']='true';
      }
      $subtitles[]=$list;
    }
  }

  $plan=App\Models\Subscription::getPlan();

  $video_url='';
  if(empty($plan->video_quality)){
      $video_url=$movie->video_url_480p;
      $video_url=empty($video_url)?$movie->video_url_720p:$video_url;
      $video_url=empty($video_url)?$movie->video_url_1080p:$video_url;
      $video_url=empty($video_url)?$movie->video_url:$video_url;
  }else{
      if($plan->video_quality==1){
          $video_url=$movie->video_url_720p;
          $video_url=empty($video_url)?$movie->video_url_1080p:$video_url;
          $video_url=empty($video_url)?$movie->video_url:$video_url;
      }elseif($plan->video_quality==2){
          $video_url=$movie->video_url_1080p;
          $video_url=empty($video_url)?$movie->video_url:$video_url;
      }else{
          $video_url=empty($video_url)?$movie->video_url:$video_url;
      }
  }
  $video_url=empty($video_url)?$movie->video_url:$video_url;
  ?>


  <script type="text/javascript">
         var player;  
         document.addEventListener("DOMContentLoaded", function(event) { 

          function apiFetchOptions(method,storedToken,csrfToken){
            return {
                method: method,
                headers: {
                    'Authorization': 'Bearer ' + storedToken,
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };
          }

          var profileToken=getWithExpiry('profileToken');
          var profileTokenWeb="{{ $profileToken }}";
          @if(empty($usermovie))
          window.location.href="{{ url('/') }}/lost?message=profileidempty"
          @endif

            // console.log(profileToken);
            // console.log(profileTokenWeb);
            // return false;

          if(profileToken!=profileTokenWeb){
            localStorage.removeItem('profileToken');
            window.location.href='{{ url('/') }}/browse';
            return false;
          }

          var storedToken = localStorage.getItem("tokenEncrypted");
          var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          if(!storedToken || !csrfToken){
            window.location.href='{{ url('/') }}/browse';
            return false;
          }

          document.querySelectorAll('.bigPlayerOuter').forEach(function(element) {
              element.classList.remove('dnn');
          });
          document.documentElement.classList.add('noscroll');

              let playbacktime='{{ empty($usermovie)?0:$usermovie->watch_time }}';
              var settings = {
                 useShare:false,
                 instanceName:"player1",
                 playerRatio: "1.777777",
                 activeItem:0,
                 volume:0.7,
                 autoPlay:true,
                 preload:'auto',
                 skipPoster: true,
                 showPosterOnPause: false,
                 displayPosterOnMobile:false,
                 mediaEndAction:"rewind",
                 seekTime: "10",
                 useResumeScreen: false,
                 playbackPositionTime:playbacktime,
                 aspectRatio:1, //1 is fit based on inside screen, 2 is fit full width with cropped.
                 wrapperMaxWidth:"100%",
                 randomPlay:false,
                 rightClickContextMenu: "browser",
                 useKeyboardNavigationForPlayback:true,
                 playerType:'lightbox',

                      media:[
                          
                          {
                              type:'hls',
                              path:"{!! $video_url !!}",
                              <?php 
                              if(count($subtitles)){
                                echo "subtitles:[";
                                foreach ($subtitles as $key => $subtitle) {
                                    echo "{";
                                    echo "label: '{$subtitle['label']}',";
                                    echo "src: '{$subtitle['src']}'";
                                    if (isset($subtitle['active']) && $subtitle['active']) {
                                        echo ", active:true";
                                    }
                                    echo "}";
                                    if ($key < count($subtitles) - 1) {
                                        echo ",";
                                    }
                                }
                                echo "]";
                              }
                              ?>

                          },

                      ]
                              
              };

              fetch("{{ url('/') }}/vlite/skin/aviva.txt")
              .then(response => response.text())
              .then(content => {
                  var wrapper = document.getElementById("wrapper");
                  var content=content.replace('<div class="vpl-player-controls-bottom">','<div class="vpl-back-refer ICineLeft">{{ $movie->title }}</div> <div class="vpl-player-controls-bottom">');
                  wrapper.innerHTML = content; 
                  player = new vpl(wrapper, settings);


                  if(player){
                      var isPaused=0;
                      player.addEventListener("mediaPause", function(data){
                          isPaused=1;
                      });
                      player.addEventListener("mediaPlay", function(data){
                          isPaused=0;
                      });

                      player.setVolume(0.7);

                      let secondsWatched=0;
                      let percentageWatched='{{ empty($usermovie)?0:$usermovie->watched_percent }}';

                      let intervalSecond=15;
                      let moviePlayInterval = setInterval(function(){
                          if(!isPaused){
                              let getCurrentTime=parseInt(player.getCurrentTime(),10);


                              //render percentage watched
                              if(getCurrentTime && playbacktime){
                                  percentageWatched = parseInt(((getCurrentTime / '{{ empty($movie->duration)?7000:$movie->duration }}') * 100),10);
                              }
                              if(getCurrentTime==0 && secondsWatched>1){
                                  percentageWatched=100;
                              }


                              let setusermoviedata ='';
                              setusermoviedata = {
                                  watch_time: getCurrentTime,
                                  watching: 1,
                                  watched_percent: percentageWatched
                              };

                              //render watched seconds total
                              secondsWatched=secondsWatched+1;
                              if(secondsWatched>1 && percentageWatched!=100){
                                  let watchedFinal=parseInt({{ empty($usermovie)?0:$usermovie->watched }})+((parseInt(secondsWatched)-1)*intervalSecond);
                                  if(watchedFinal!=0){
                                      setusermoviedata.watched=watchedFinal;
                                  }
                              }else{
                                  setusermoviedata.watching=0;
                              }

                              //console.log(setusermoviedata);
                              const url='{{ url("/") }}/api/setusermovie/'+{{ $movie->id }}+'?profile_id='+getWithExpiry('profileToken');
                              const options = apiFetchOptions('POST',storedToken,csrfToken);
                              options.body = JSON.stringify(setusermoviedata);
                              fetchDataWithRetry(url, options,1).then(response => response.json());
                          }
                          //console.log(isPaused);
                      },(intervalSecond*1000)); //5000 is 5seconds only and when exit clearInterval(moviePlayInterval);




                      $('.vpl-back-refer').on('click',function(){
                          clearInterval(moviePlayInterval);
                          player.cleanMedia();


                          //     let getCurrentTime=parseInt(player.getCurrentTime(),10);
                          //     //render percentage watched
                          //     let setusermoviedata ='';
                          //     setusermoviedata = {
                          //         watch_time: getCurrentTime
                          //     };
                          //     if(getCurrentTime && playbacktime){
                          //         percentageWatched = parseInt(((getCurrentTime / '{{ empty($movie->duration)?7000:$movie->duration }}') * 100),10);
                          //         setusermoviedata.watched_percent=percentageWatched;
                          //     }

                          // if(setusermoviedata){
                          //   console.log(setusermoviedata);
                          //   let url='{{ url("/") }}/api/setusermovie/'+{{ $movie->id }}+'?profile_id='+getWithExpiry('profileToken');
                          //   let options = apiFetchOptions('POST',storedToken,csrfToken);
                          //   options.body = JSON.stringify(setusermoviedata);
                          //   fetchDataWithRetry(url, options,1).then(response => response.json());
                          // }
                          window.location.href=decodeURI("{{ $referurl }}");
                      });
                  }//player

              });


            });
</script>

@endsection
