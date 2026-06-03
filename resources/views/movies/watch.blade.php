@extends('layouts.frontend')

@section('header-script')
<div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>
<style>
   .vpl-lightbox-wrap .vpl-lightbox-close{display: none !important;}
</style>

<!-- Include SweetAlert2 CSS in your <head> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Include SweetAlert2 JS (before closing </body>) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="{{ asset('js/crypto-js.min-new.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>


<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/movies-new.js') }}?v=1" defer></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content') 
  <div class="ajxProfile"></div>
  <div id="wrapper" class="vpl-skin-aviva vpl-customized"></div>
  <input type="hidden" id="movieId" value="{{ $movie->id }}">
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
    let movieId = document.getElementById('movieId').value;
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
                    let quizShown = false; // NEW FLAG
                    let movie_quiz_status = {{ $movie_quiz_status }};
                    let question_available = {{ $question_available }};
                    let quizStatus = {{ $quiz_status ? 'true' : 'false' }};
                    let getCurrentTime=parseInt(player.getCurrentTime(),10);

                    var isPaused=0;
                    /* space bar using play,pause,mute,unmute,fullscreen */
                    function getVideo() {
                        return document.querySelector('#wrapper video') ||
                               (document.fullscreenElement
                                    ? document.fullscreenElement.querySelector('video')
                                    : null);
                    }
                    /*End*/
                      player.addEventListener("mediaPause", function(data){
                          isPaused=1;
                      });
                      player.addEventListener("mediaPlay", function(data){
                        isPaused=0;
                        if (!quizPollingStarted) {
                            startQuizPolling(movieId);
                        }
                        // Wait until 5 seconds to show quiz prompt (only once)
                        const checkInterval = setInterval(() => {
                            const currentTime = parseInt(player.getCurrentTime() || 0);
                            if (
                                currentTime >= 0 &&
                                currentTime <= 5 &&
                                !quizPromptShownOnce &&
                                {{ $movie_quiz_status }} == 1 &&
                                {{ $question_available }} == 1
                            ) {
                                const cookieValue = getCookie("quiz_popup_{{ $movie->id }}");

                                // Only show popup if cookie not already set
                                if (cookieValue === '' || cookieValue === '0') {
                                    quizPromptShownOnce = true;
                                    //quizShown = true;
                                    showQuizPrompt(player);
                                    clearInterval(checkInterval);
                                } else {
                                    // User already accepted/declined earlier
                                    disclaimerAccepted = (cookieValue === '1');
                                    quizActive = (cookieValue === '1');
                                    clearInterval(checkInterval);
                                }
                            }

                            // Stop checking if video paused or ended
                            if (isPaused || currentTime >= player.getDuration()) {
                                clearInterval(checkInterval);
                            }
                        }, 1000); // check every 1 second
                         // If disclaimer already accepted earlier
                        if(cookieValue === '1' && disclaimerAccepted === false){
                            disclaimerAccepted = true;
                            quizActive = true;
                        }
                        // Trigger disclaimer accepted
                        if(disclaimerAccepted && !firstQuizTriggered){ 
                            firstQuizTriggered = true; // make sure it's only once
                            initQuiz(movieId);
                        }
                      });
                      /* space bar using play,pause,mute,unmute,fullscreen */
                      document.addEventListener("keydown", function (e) {
                        // Ignore typing inside inputs
                        if (
                            e.target.tagName === "INPUT" ||
                            e.target.tagName === "TEXTAREA" ||
                            e.target.isContentEditable
                        ) return;

                        // Block shortcuts during quiz popup
                        if (typeof Swal !== "undefined" && Swal.isVisible()) return;

                        const video = getVideo();
                        if (!video) return;

                        switch (e.code) {

                            case "ArrowUp": // Volume up
                                e.preventDefault();
                                video.volume = Math.min(video.volume + 0.1, 1);
                                break;

                            case "ArrowDown": // Volume down
                                e.preventDefault();
                                video.volume = Math.max(video.volume - 0.1, 0);
                                break;

                            case "Space": // Play / Pause
                                e.preventDefault(); // stop scroll
                                // Block play/pause when quiz popup is open
                                if (typeof Swal !== "undefined" && Swal.isVisible()) return;
                                if (video.paused) {
                                    video.play();
                                } else {
                                    video.pause();
                                }
                                break;

                            case "ArrowRight": // Forward 10s
                                e.preventDefault();
                                if (quizActive) return;
                                video.currentTime = Math.min(
                                    video.currentTime + 10,
                                    video.duration
                                );
                                break;

                            case "ArrowLeft": // Rewind 10s
                                e.preventDefault();
                                if (quizActive) return;
                                video.currentTime = Math.max(
                                    video.currentTime - 10,
                                    0
                                );
                                break;

                            case "KeyM": // Mute / Unmute
                                video.muted = !video.muted;
                                break;

                            case "KeyF": // Fullscreen
                                if (!document.fullscreenElement) {
                                    document.getElementById('wrapper').requestFullscreen();
                                } else {
                                    document.exitFullscreen();
                                }
                                break;
                        }
                        /*if (e.code !== "Space") return;

                        // prevent page scroll
                        e.preventDefault();

                        // block when quiz popup open
                        if (typeof Swal !== "undefined" && Swal.isVisible()) return;

                        const video = getVideo();
                        if (!video) return;

                        if (video.paused) {
                            video.play();
                        } else {
                            video.pause();
                        }*/
                      }, true);
                      /* End */
                      player.addEventListener("mediaEnd", function() {
                        const cookieName = "quiz_popup_{{ $movie->id }}";
                        const popupValue = getCookie(cookieName);

                        // If user SKIPPED quiz clear cookie
                        if (popupValue === '0') {
                            clearCookie(cookieName);
                        }
                        const fullscreenContainer = document.fullscreenElement || document.getElementById('wrapper');
                        window.fullscreenContainer = fullscreenContainer;
                        const videoElement = fullscreenContainer.querySelector('video') || document.querySelector('#wrapper video');
                        $('.vpl-lightbox-wrap').css('display','contents');
                        if (videoElement) videoElement.pause(); // Pause video manually
                        showFinalQuizResult(player);
                    });
                      player.setVolume(0.7);

                      let secondsWatched=0;
                      let percentageWatched='{{ empty($usermovie)?0:$usermovie->watched_percent }}';

                      let intervalSecond=15;
                      let moviePlayInterval = setInterval(function(){
                          if(!isPaused){
                              let getCurrentTime=parseInt(player.getCurrentTime(),10);

                              let movieDuration = parseInt(player.getDuration(), 10);

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
                                  watched_percent: percentageWatched,
                                  movieDuration: movieDuration
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
            window.addEventListener('beforeunload', function () {
                const cookieValue = getCookie(`quiz_popup_${movieId}`);

                if ((quizPromptShownOnce === true && firstQuizTriggered === false) || quizActive === true) {
                    clearCookie("quiz_popup_" + movieId);
                    navigator.sendBeacon(
                        '/api/quiz-prompt-skipped',
                        JSON.stringify({
                            movie_id: movieId,
                            tokenEncrypted: tokenEncrypted
                        })
                    );
                }
            });


</script>
<script type="text/javascript">
    let quizActive = false;
    let disclaimerAccepted = false;
    let quizPromptShownOnce = false;
    let firstQuizTriggered = false;
    let quizSchedule = [];       // All questions returned by backend (with popup_time)
    let quizTimer = null;
    let videoElement = null;
    let selectedQuestions = [];
    let quizAnswers = [];
    let currentQIndex = 0;
    const APP_AES_KEY = CryptoJS.enc.Base64.parse("{{ env('QUIZ_SECRET_KEY') }}");
    let tokenEncrypted = localStorage.getItem("tokenEncrypted");
    let quizStatus = {{ $quiz_status ? 'true' : 'false' }}; // server initial state
    let lastQuizStatus = quizStatus;
    let quizPollingStarted = false;
    let quizPollingStopped = false;
    let quizPromptAlreadyShown = {{ $quiz_prompt_shown ? 'true' : 'false' }};
    let questionCounter = 0;

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
        return '';
    }

    function setCookie(name, value, hours) {
        const d = new Date();
        d.setTime(d.getTime() + (hours * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = `${name}=${value}; ${expires}; path=/`;
    }

    function clearCookie(name) {
        // Clear without path
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC;`;
        // Clear with path
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }
    function encryptRequest(payload) {
        try {
            // Convert payload to string
            const text = JSON.stringify(payload);
            // Generate random IV (16 bytes)
            const iv = CryptoJS.lib.WordArray.random(16);
            // Encrypt
            const encrypted = CryptoJS.AES.encrypt(text, APP_AES_KEY, {
                iv: iv,
                mode: CryptoJS.mode.CBC,
                padding: CryptoJS.pad.Pkcs7
            });
            return {
                iv: CryptoJS.enc.Base64.stringify(iv),
                data: CryptoJS.enc.Base64.stringify(encrypted.ciphertext)
            };

        } catch (err) {
            console.error("ENCRYPT ERROR:", err);
            return null;
        }
    }

    function decryptResponse(encrypted) {
        try {
            const iv  = CryptoJS.enc.Base64.parse(encrypted.iv);
            const cipher = CryptoJS.lib.CipherParams.create({
                ciphertext: CryptoJS.enc.Base64.parse(encrypted.data)
            });
            const decrypted = CryptoJS.AES.decrypt(cipher, APP_AES_KEY, {
                iv: iv,
                mode: CryptoJS.mode.CBC,
                padding: CryptoJS.pad.Pkcs7
            });
            return JSON.parse(decrypted.toString(CryptoJS.enc.Utf8));
        } catch (err) {
            console.error("DECRYPT ERROR:", err);
            return null;
        }
    }
    window.disableSeekControls = function () {
        $('.vpl-skip-backward-toggle, .vpl-skip-forward-toggle')
            .addClass('vpl-disabled')
            .prop('disabled', true)
            .css({
                pointerEvents: 'none',
                opacity: 0.35
            });
        $('.vpl-seekbar').css('display', 'none');
    };

    window.enableSeekControls = function () {
        $('.vpl-skip-backward-toggle, .vpl-skip-forward-toggle')
            .removeClass('vpl-disabled')
            .prop('disabled', false)
            .css({
                pointerEvents: '',
                opacity: ''
            });
        $('.vpl-seekbar').css('display', 'block');
    };
    function startQuizPolling(movieId) {
        if (quizPollingStarted || quizPollingStopped) return;
        quizPollingStarted = true;
        const encryptedPayload = encryptRequest({
            movie_id: movieId,
            user_id: {{ auth()->id() ?? 'null' }},
            tokenEncrypted: tokenEncrypted,
        });

        const pollInterval = setInterval(() => {
            if (quizPollingStopped || quizPromptShownOnce) {
                clearInterval(pollInterval);
                return;
            }
            fetch(`/api/quiz-status`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                     payload: encryptedPayload
                })
            })
            .then(res => res.json())
            .then(enc => {
                const data = decryptResponse(enc);
                // Server says quiz is NOT allowed (active elsewhere)
                if (data.quiz_allowed === false) {
                    quizStatus = true;
                } else {
                    quizStatus = false;
                }
                if (lastQuizStatus === true && quizStatus === false) {
                    //console.log("Quiz released by other device");
                    quizPromptShownOnce = false;   
                    showQuizPrompt(player); // SHOW WITHOUT REFRESH
                }

                lastQuizStatus = quizStatus; // update snapshot
            })
            .catch(err => console.error("QUIZ LOAD ERROR:", err));
        }, 5000); // every 5 seconds
    }
    function markQuizPromptAsShown() {
        return fetch('/api/quiz-prompt-shown', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                movie_id: movieId
            })
        })
        .then(res => res.json());
    }
    function showQuizPrompt(player){
        // Detect fullscreen container or fallback to wrapper
        const fullscreenContainer = document.fullscreenElement || document.getElementById('wrapper');
        const videoElement = fullscreenContainer.querySelector('video') || document.querySelector('#wrapper video');

        $('.vpl-lightbox-wrap').css('display','contents');
        //let videoElement = document.querySelector('#wrapper video');
        if (videoElement) videoElement.pause(); // Pause video manually
        if (quizStatus === true) {

            Swal.fire({
                icon: 'info',
                title: 'Quiz Already Active',
                html: `
                      <p style="font-size:16px; margin-top:10px; color:#222020;">
                        You already participated in the quiz on another device. You can only watch the movie here.
                      </p>
                    `,
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                target: fullscreenContainer,
                customClass: {
                    popup: 'custom-swal-popup disclaimer-popup'
                }
                }).then(() => {
                    if (videoElement) videoElement.play();
                    $('.vpl-lightbox-wrap').css('display','block');
                });
                return; // stop here
        }
        //console.log(quizPromptAlreadyShown);
        markQuizPromptAsShown().then(data => {
            //console.log(data);
            // Already shown somewhere else
            if (data.already_shown === true) {
                //console.log('Popup blocked');
                if (videoElement) videoElement.play();
                $('.vpl-lightbox-wrap').css('display','block');
                return;
            }
            //console.log('Popup allowed');
            // Only FIRST browser reaches here
            Swal.fire({
                html: `
                    <div class="quiz-box">

                        <h1 class="quiz-heading">QUIZ</h1>

                        <div class="divider"></div>

                        <h2 class="play-title">PLAY TO WIN</h2>

                        <div class="language-row" style="margin:15px 0;">
                            <label style="font-weight:bold; font-size:14px;">Select Language</label>

                            <select id="quiz_language" class="swal2-input" style="color:black">
                                <option value="">Choose Language</option>
                                <option value="english">English</option>
                                <option value="tamil">Tamil</option>
                            </select>
                            <div id="language_error"
                                 style="display:none;color:red;font-size:15px;margin-top:5px;margin-left:5px;text-align:left;">
                            </div>
                        </div>

                        <div class="terms-row">
                            <input type="checkbox" id="terms_ok">
                            <span>
                                Accept the
                                <a href="#" class="terms-link">Terms & Condition</a>
                            </span>
                        </div>

                        <div class="btn-row">
                            <button id="playBtn" class="play-btn">PLAY</button>
                            <button id="skipBtn" class="skip-btn">SKIP</button>
                        </div>

                        <div class="note-box">
                            <b>NOTE:</b><br>
                            Once Start to play do not <b>Forward</b> or <b>Rewind</b> the movie.<br>
                            Your quiz appears anytime.
                        </div>

                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: false,
                allowOutsideClick: false,
                target: fullscreenContainer, // Works in both fullscreen and normal
                customClass: {
                    popup: 'quiz-popup-container'
                }
            });
        });
        // PLAY button action
        $(document).on("click", "#playBtn", function () {
            const selectedLanguage = $("#quiz_language").val();
            // Clear old errors
            $("#language_error").hide().text("");
            if (selectedLanguage === '') {
                $("#language_error")
                    .html(`
                        <span style="display:flex;align-items:center;gap:5px;">
                            <img src="{{ asset('img/icon/quiz_application/alert.png') }}" width="18" height="18">
                            Please select language
                        </span>
                    `)
                    .show();
                return;
            }
            if (!$("#terms_ok").is(":checked")) {
                Swal.showValidationMessage("Please accept Terms & Condition");
                return;
            }
            // SAVE LANGUAGE
            localStorage.setItem("quiz_language", selectedLanguage);
            //console.log("Saved Language:", selectedLanguage);
            Swal.close();
            // LOGIC INSERTED HERE
            setCookie("quiz_popup_{{ $movie->id }}", 1, 3); // store for 3 hours
            disclaimerAccepted = true;
            quizActive = true;
            disableSeekControls();
            if (videoElement) videoElement.play(); // Resume video
            $('.vpl-lightbox-wrap').css('display', 'block');  // Show player again
            // startQuiz(movieId);
        });
        // SKIP button action
        $(document).on("click", "#skipBtn", function () {
            Swal.close();
            if (videoElement) videoElement.play(); // Resume
            $('.vpl-lightbox-wrap').css('display','block');
            // skip logic
            setCookie("quiz_popup_{{ $movie->id }}", 0, 3);
            updateQuizPromptSkipped({{ $movie->id }});
        });
    }
    
    function resetQuizState(movieId) {
        //console.log("Resetting quiz cookies...");
        clearCookie("attempt_id");
        clearCookie("quiz_popup_" + movieId);
        quizAnswers = [];
        selectedQuestions = [];
        currentQIndex = 0;
    }

    function initQuiz(movieId) {
        //console.log("initQuiz Started. movieId =", movieId);
        const quizLanguage = localStorage.getItem("quiz_language");
        //console.log(quizLanguage);
        resetQuizState(movieId);
        videoElement = document.querySelector("#wrapper video");
        const encryptedPayload = encryptRequest({
            movie_id: movieId,
            user_id: {{ auth()->id() ?? 'null' }},
            language: quizLanguage,
            tokenEncrypted: tokenEncrypted,
        });
        fetch(`/api/quiz`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                 payload: encryptedPayload
            })
        })
        .then(res => res.json())
        .then(enc => {
            const data = decryptResponse(enc);
            //console.log("DECRYPTED QUIZ:", data);
            if (data.attempt_id) {
                setCookie("attempt_id", data.attempt_id, 3);
                //console.log("Attempt ID saved:", data.attempt_id);
            }
            quizSchedule = data.questions;

            /*quizSchedule.forEach((q, i) => {
                console.log(`${i+1}. Question Id: ${q.id}, Show Time: ${q.show_question_time}, Popup Time: ${q.popup_time} mins, Language: ${q.language}`);
            });*/

            startQuizWatcher(quizSchedule);
        })
        .catch(err => console.error("QUIZ LOAD ERROR:", err));
    }

    function startQuizWatcher() {
        setInterval(() => {
            if (!videoElement) return;
            const currentTime = Math.floor(videoElement.currentTime);
            const currentMinute = Math.floor(currentTime / 60);
            const due = quizSchedule.filter(q => q.popup_time === currentMinute && !q.shown);
            if (due.length > 0) {
                //console.log("POPUP TIME HIT:", currentMinute, due);
                due.forEach(q => {
                    q.shown = true;
                    questionCounter++;
                    triggerQuiz(q, questionCounter);
                });
            }

        }, 1000);
    }
    
    function triggerQuiz(question, qNumber) {
        let timeLeft = 20;
        let chosenOption = null; // store selected option
        const fullscreenContainer = document.fullscreenElement || document.getElementById('wrapper');
        const videoElement = fullscreenContainer.querySelector('video') || document.querySelector('#wrapper video');
        const QUESTION_TOTAL_TIME = 20;
        const questionStartTime = Date.now(); // milliseconds

        $('.vpl-lightbox-wrap').css('display','contents');
        //let videoElement = document.querySelector('#wrapper video');
        if (videoElement) videoElement.pause(); // Pause video manually
        Swal.fire({
            title: '',
            customClass: {
                popup: 'quiz-swal-wide'   // unique class ONLY for this swal
            },
            html: `
                <!-- QUESTION BOX -->
                <div class="quiz-inner-wrapper">
                    <div class="quiz-question-box">
                        <span class="quiz-qnumber">Q${qNumber}.</span>${question.question}
                    </div>
                    <div id="options-container" class="quiz-options-grid" style="display:none;">
                        ${question.options.map(op => `
                            <button 
                                class="quiz-option-btn"
                                data-qid="${question.id}"
                                data-opid="${op.id}" style="pointer-events:none; opacity:0.5;">
                                ${op.name}
                            </button>
                        `).join('')}

                        
                    </div>
                    <!-- CENTER TIMER -->
                    <div id="timer" class="quiz-timer-above">
                        <div class="circle-timer">
                            <svg width="72" height="72">
                                <circle cx="36" cy="36" r="32" class="circle-bg"/>
                                <circle cx="36" cy="36" r="32" class="circle-progress" id="circleProgress"/>
                            </svg>
                            <div class="timer-text">
                                <span id="timeText">${timeLeft}s</span>
                            </div>
                        </div>
                    </div>
                    <button id="saveAnswerBtn" class="quiz-save-btn" style="opacity:0; pointer-events:none;">
                        Submit
                    </button>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            target: fullscreenContainer, // Works in both fullscreen and normal
            allowEscapeKey: false,
            didOpen: () => {
                const blockSpace = function(e) {
                    if (e.code === "Space" || e.key === " ") {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                    }
                };
                document.addEventListener("keydown", blockSpace, true);
                window._quizSpaceBlocker = blockSpace;
                // Show options after 5 seconds
                setTimeout(() => {
                    document.getElementById("options-container").style.display = "grid";

                    // enable buttons only after showing
                    document.querySelectorAll(".quiz-option-btn").forEach(btn => {
                        btn.style.pointerEvents = "auto";
                        btn.style.opacity = "1";
                    });
                    // show submit button AFTER 10 sec
                    const saveBtn = document.getElementById("saveAnswerBtn");
                    saveBtn.style.opacity = "1";
                    saveBtn.style.pointerEvents = "auto";

                }, 5000);
                document.querySelectorAll(".quiz-option-btn").forEach(btn => {
                    btn.addEventListener("click", function () {
                        // remove highlight from all
                        document.querySelectorAll(".quiz-option-btn").forEach(b => {
                            b.style.background = "#f2f2f2";
                            b.style.border = "2px solid #ccc";
                            b.style.color = "#000";
                            b.style.fontWeight = "normal";
                        });

                        // highlight selected
                        this.style.background = "#4CAF50";
                        this.style.border = "2px solid #2e7d32";
                        this.style.color = "#fff";
                        this.style.fontWeight = "bold";

                        chosenOption = this.getAttribute("data-opid");

                        // enable SAVE button
                        /*const saveBtn = document.getElementById("saveAnswerBtn");
                        saveBtn.style.opacity = "1";
                        saveBtn.style.pointerEvents = "auto";*/
                    });
                });
                document.getElementById("saveAnswerBtn").addEventListener("click", function () {
                    clearInterval(quizTimer);
                    const timeTakenSeconds = Math.min(
                        QUESTION_TOTAL_TIME,
                        Math.floor((Date.now() - questionStartTime) / 1000)
                    );
                    //console.log(timeTakenSeconds);
                    Swal.close();
                    submitAnswer(question.id, chosenOption, timeTakenSeconds); // save selected answer
                });
                const totalTime = timeLeft;
                const circle = document.getElementById("circleProgress");
                const timeText = document.getElementById("timeText");
                const circumference = 2 * Math.PI * 32;

                circle.style.strokeDasharray = circumference;
                circle.style.strokeDashoffset = 0;

                quizTimer = setInterval(() => {
                    timeLeft--;
                    timeText.innerText = `${timeLeft}s`;
                    const offset = circumference - (timeLeft / totalTime) * circumference;
                    circle.style.strokeDashoffset = offset;

                    if (timeLeft <= 0) {
                        clearInterval(quizTimer);
                        Swal.close();
                        //console.log(QUESTION_TOTAL_TIME);
                        // auto-submit
                        submitAnswer(question.id, chosenOption || "", QUESTION_TOTAL_TIME);
                    }
                }, 1000);
            },

            willClose: () => {
                clearInterval(quizTimer);
                if (window._quizSpaceBlocker) {
                    document.removeEventListener("keydown", window._quizSpaceBlocker, true);
                    window._quizSpaceBlocker = null;
                }
                if (videoElement) videoElement.play();
                $('.vpl-lightbox-wrap').css('display','block');
            }
        });
    }

    function selectAnswer(questionId, optionId) {
        //console.log("User selected:", questionId, optionId);
        quizAnswers.push({
            question_id: questionId,
            option_id: optionId
        });
        Swal.close(); // Close popup immediately
        submitAnswer(questionId, optionId);
    }

    function submitAnswer(questionId, optionId, timeTakenSeconds) {
        let attemptId = getCookie("attempt_id") || null;
        // Prepare current answer
        const currentAnswer = {
            question_id: questionId,
            option_id: optionId || null,
            answered_seconds: timeTakenSeconds
        };

        // Store locally (for review or final submit)
        quizAnswers.push(currentAnswer);
        const encryptedPayload = encryptRequest({
            movie_id: {{ $movie->id }},
            user_id: {{ auth()->id() ?? 'null' }},
            answer: currentAnswer,   // single answer
            attempt_id: attemptId
        });
        //console.log("Submitting SINGLE answer:", currentAnswer, "Attempt ID:", attemptId);

        fetch('/api/submit-quiz', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                payload: encryptedPayload
            })
        })
        .then(async (res) => {
            const raw = await res.text();
            //console.log("RAW:", raw);
            //let data;
            let decrypted = null;
            try {
                const encryptedJson = JSON.parse(raw);
                // Decrypt using global helper
                decrypted = decryptResponse(encryptedJson);

            } catch (e) {
                console.error("DECRYPT ERROR (submit-quiz):", e);
                return;
            }

            // Now use decrypted JSON safely
            if (!attemptId && decrypted.quizAttemptId) {
                setCookie("attempt_id", decrypted.quizAttemptId, 3);
                attemptId = decrypted.quizAttemptId;
            }
            // Next question
            currentQIndex++;
            if (currentQIndex < selectedQuestions.length) {
                questionCounter++;
                triggerQuiz(selectedQuestions[currentQIndex], questionCounter);
            } /*else {
                console.log("Quiz finished.");
            }*/
        });
    }

    /*function showQuizPopup() {
        document.getElementById("custom-quiz-popup").style.display = "block";
    }

    function hideQuizPopup() {
        document.getElementById("custom-quiz-popup").style.display = "none";
    }*/

    function updateQuizPromptSkipped(movieId) {
        fetch('/api/quiz-prompt-skipped', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ movie_id: movieId })
        });
    }

    function showFinalQuizResult(player) {
        //console.log("Movie ended — fetching final quiz result...");
        const attemptId = getCookie('attempt_id');
        /*if (!attemptId) {
            console.log('No quiz attempt found. Skipping result fetch.');
            window.location.href = "{{ url('/browse') }}";
            return;
        }*/
        if (!attemptId) {
            //console.log('No quiz attempt found. Pausing video and staying on page.');

            const fullscreenContainer =
                document.fullscreenElement || document.getElementById('wrapper');

            const videoElement =
                fullscreenContainer.querySelector('video') ||
                document.querySelector('#wrapper video');

            $('.vpl-lightbox-wrap').css('display', 'contents');

            if (videoElement) {
                videoElement.pause(); // pause video
            }

            return; // stop further quiz logic
        }

        const encryptedPayload = encryptRequest({
            movieId: movieId,
            attemptId: attemptId,
            user_id: {{ auth()->id() ?? 'null' }},
            tokenEncrypted: tokenEncrypted,
        });
        fetch('/api/quiz-result', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                payload: encryptedPayload
            })
        })
        //.then(res => res.json())
        //.then(data => {
        .then(async (res) => {
            const raw = await res.text();
            // console.log("RAW ENCRYPTED RESULT:", raw);

            let decrypted = null;
            try {
                const encryptedJson = JSON.parse(raw);
                if (encryptedJson.error) {
                    console.warn('Quiz result error:', encryptedJson.error);
                    return;
                }
                // Decrypt using helper
                decrypted = decryptResponse(encryptedJson);
                if (!decrypted) {
                    console.warn('Decryption failed or empty response');
                    return;
                }

            } catch (e) {
                console.error("QUIZ-RESULT DECRYPT ERROR:", e);
                return;
            }

            const { correctAnswerCount, totalQuestions, attemptId } = decrypted;
            if (typeof correctAnswerCount === 'undefined') {
                return;
            }

            const passingScore = totalQuestions > 1 ? totalQuestions - 1 : 1;
            //console.log(`Passing Score: ${passingScore} | Correct: ${correctAnswerCount}`);
            if(passingScore > 0 && correctAnswerCount >= passingScore){
                //Here check if cookie is created for attempt_id. If not then create cookie value here. For value use the attempt_id from response.
                Swal.fire({
                    title: `<img src="{{ asset('img/icon/quiz_application/party.png') }}" width="24" alt="Party"> You Win!`,
                    
                    html: `<div style="font-size: 18px; margin-top: 10px; text-align: center;">
                            Congratulations! You've completed the quiz successfully.<br><br>
                                <a href="/rewards/{{ $usermovie->user_id }}" id="reward-btn" style="text-decoration: none; font-weight: bold;">
                                    <img src="{{ asset('img/icon/quiz_application/gift.png') }}" 
                                     width="24" alt="Gift">
                                    Claim Your Reward Points
                                </a>
                            </div>`,
                    background: '#f0f9ff url("https://www.transparenttextures.com/patterns/stardust.png")',
                    color: '#333',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    target: window.fullscreenContainer || document.body, // Works in both fullscreen and normal
                    customClass: {
                        popup: 'custom-swal-popup reward-popup'
                    }
                });
                setTimeout(() => {
                  // trigger after popup is shown
                  confetti({
                    particleCount: 150,
                    spread: 90,
                    origin: { y: 0.6 }
                  });
                }, 100);

            }
            else{
                Swal.fire({
                    icon: 'info',
                    title: `<img src="{{ asset('img/icon/quiz_application/sad-face.png') }}" width="24" alt="betterluck"> Better Luck Next Time!`,
                    html: `
                          <p style="font-size:16px; margin-top:10px; color:#222020;">
                            You did well! But unfortunately, you didn't pass this time.<br>
                            Keep trying — success is just around the corner!
                            <img src="{{ asset('img/icon/quiz_application/strong.png') }}" 
                                 width="24" alt="Motivation">
                          </p>
                        `,
                    confirmButtonText: `<a href="/browse" id="reward-btn" style="text-decoration: none; font-weight: bold;"><img src="{{ asset('img/icon/quiz_application/home.png') }}" width="24" alt="goback">Go Back</a>`,
                    confirmButtonColor: '#6a0dad',
                    allowOutsideClick: false,
                    target: fullscreenContainer, // Works in both fullscreen and normal
                    showCancelButton: false,
                    background: '#fff',
                    color: '#333',
                    customClass: {
                      popup: 'custom-swal-popup disclaimer-popup',
                      confirmButton: 'custom-swal-confirm'
                    }
                })/*.then(() => {
                    window.location.href = "{{ url('/browse') }}";
                })*/;
                setTimeout(() => {
                  // trigger after popup is shown
                  confetti({
                    particleCount: 150,
                    spread: 90,
                    origin: { y: 0.6 }
                  });
                }, 100);
            }

            // Cleanup cookies
            clearCookie(`quiz_popup_{{ $movie->id }}`);
            clearCookie("attempt_id");
        })
        .catch(err => {
            console.error('Result fetch error:', err);
        });
    }
</script>
@endsection