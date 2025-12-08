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
  <div id="custom-quiz-popup" style="display:none;">
    <div id="quiz-timer" style="color:red; font-weight:bold; margin-bottom:10px;">Time left: 10s</div>

    <div id="quiz-question-text" style="font-size:18px; margin-bottom:15px;"></div>
    
    <div id="quiz-options" style="margin-bottom:15px;"></div>
    
    <button id="next-question-btn" style="display:none;" class="btn btn-primary submit-button">Next</button>
    <!-- <button type="button" class="next-button next-button">Next</button> -->
</div>
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
                    let question_available = {{ $question_available }};
                    let getCurrentTime=parseInt(player.getCurrentTime(),10);
                      var isPaused=0;
                      
                      player.addEventListener("mediaPause", function(data){
                          isPaused=1;
                      });
                      player.addEventListener("mediaPlay", function(data){
                        isPaused=0;
                        // Wait until 5 seconds to show quiz prompt (only once)
                        const checkInterval = setInterval(() => {
                            const currentTime = parseInt(player.getCurrentTime() || 0);
                            if (
                                currentTime >= 0 &&
                                currentTime <= 5 &&
                                !quizPromptShownOnce &&
                                {{ $question_available }} == 1
                            ) {
                                const cookieValue = getCookie("quiz_popup_{{ $movie->id }}");

                                // Only show popup if cookie not already set
                                if (cookieValue === '' || cookieValue === '0') {
                                    quizPromptShownOnce = true;
                                    quizShown = true;
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
                            //startQuiz(movieId); // This will then handle later intervals (30, 45, etc.)
                            initQuiz(movieId);
                        }
                      });

                      player.addEventListener("mediaEnd", function() {
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
</script>
<script type="text/javascript">
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

        // Optional: verify immediately
        /*if (document.cookie.split('; ').find(row => row.startsWith(name + '='))) {
            console.warn(`Cookie "${name}" might not be fully cleared!`);
        } else {
            console.log(`Cookie "${name}" cleared successfully.`);
        }*/
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

    function showQuizPrompt(player){
        // Detect fullscreen container or fallback to wrapper
        const fullscreenContainer = document.fullscreenElement || document.getElementById('wrapper');
        const videoElement = fullscreenContainer.querySelector('video') || document.querySelector('#wrapper video');

        $('.vpl-lightbox-wrap').css('display','contents');
        //let videoElement = document.querySelector('#wrapper video');
        if (videoElement) videoElement.pause(); // Pause video manually
        Swal.fire({
            html: `
                <div class="quiz-box">

                    <h1 class="quiz-heading">QUIZ</h1>

                    <div class="divider"></div>

                    <h2 class="play-title">PLAY TO WIN</h2>

                    <div class="terms-row">
                        <input type="checkbox" id="terms_ok">
                        <span> Accept the <a href="#" class="terms-link">Terms & Condition</a></span>
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


        // ✔ PLAY button action
        $(document).on("click", "#playBtn", function () {

            if (!$("#terms_ok").is(":checked")) {
                Swal.showValidationMessage("Please accept Terms & Condition");
                return;
            }

            Swal.close();

            // LOGIC INSERTED HERE
            setCookie("quiz_popup_{{ $movie->id }}", 1, 3); // store for 3 hours
            disclaimerAccepted = true;
            quizActive = true;

            if (videoElement) videoElement.play(); // Resume video

            $('.vpl-lightbox-wrap').css('display', 'block');  // Show player again

            // Optional: call your quiz start function
            // startQuiz(movieId);
        });


        // SKIP button action
        $(document).on("click", "#skipBtn", function () {
            Swal.close();
            if (videoElement) videoElement.play(); // Resume
            $('.vpl-lightbox-wrap').css('display','block');
            // skip logic
            setCookie("quiz_popup_{{ $movie->id }}", 0, 3);
        });


    }

    $(document).on("click", "#playBtn", function() {
        if (!$("#terms_ok").is(":checked")) {
            Swal.showValidationMessage("Please accept Terms & Condition");
            return;
        }
        Swal.close();
        //startQuizNow();
    });

    $(document).on("click", "#skipBtn", function() {
        Swal.close();
    });

    
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

        resetQuizState(movieId);
        videoElement = document.querySelector("#wrapper video");

        fetch(`/api/quiz`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                movie_id: movieId,
                user_id: {{ auth()->id() ?? 'null' }},
            })
        })
        .then(res => res.json())
        .then(enc => {
            const data = decryptResponse(enc);
            //console.log("DECRYPTED QUIZ:", data);
            quizSchedule = data.questions;

            quizSchedule.forEach((q, i) => {
                console.log(`${i+1}. Question Id: ${q.id}, Show Time: ${q.show_question_time}, Popup Time: ${q.popup_time} mins`);
            });

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
                    triggerQuiz(q);
                });
            }

        }, 1000);
    }
    
    function triggerQuiz(question) {
        let timeLeft = 20;
        let chosenOption = null; // store selected option

        const fullscreenContainer = document.fullscreenElement || document.getElementById('wrapper');
        const videoElement = fullscreenContainer.querySelector('video') || document.querySelector('#wrapper video');

        $('.vpl-lightbox-wrap').css('display','contents');
        //let videoElement = document.querySelector('#wrapper video');
        if (videoElement) videoElement.pause(); // Pause video manually

        Swal.fire({
            title: question.question,
            html: `
                <div id="timer" style="font-size:20px;margin-bottom:10px;color:red;font-weight:bold;">
                    ⏳ ${timeLeft}s
                </div>
                <div id="options-container">
                    ${question.options.map((op) => `
                        <button 
                            class="quiz-option-btn" 
                            data-qid="${question.id}" 
                            data-opid="${op.id}"
                            style="
                                width:100%;
                                margin:5px 0;
                                padding:10px;
                                border-radius:8px;
                                background:#f2f2f2;
                                border:2px solid #ccc;
                                cursor:pointer;
                            "
                        >
                            ${op.name}
                        </button>
                    `).join('')}
                </div>

                <button id="saveAnswerBtn"
                    class="swal2-confirm swal2-styled"
                    style="margin-top:15px; width:100%; opacity:0.5; pointer-events:none;">
                    Save Answer
                </button>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            target: fullscreenContainer, // Works in both fullscreen and normal
            allowEscapeKey: false,
            didOpen: () => {

                // ---------- OPTION CLICK HANDLER ----------
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
                        const saveBtn = document.getElementById("saveAnswerBtn");
                        saveBtn.style.opacity = "1";
                        saveBtn.style.pointerEvents = "auto";
                    });
                });

                // ---------- SAVE BUTTON ----------
                document.getElementById("saveAnswerBtn").addEventListener("click", function () {
                    clearInterval(quizTimer);
                    Swal.close();
                    submitAnswer(question.id, chosenOption); // save selected answer
                });

                // ---------- TIMER ----------
                quizTimer = setInterval(() => {
                    timeLeft--;
                    document.getElementById("timer").innerHTML = `⏳ ${timeLeft}s`;

                    if (timeLeft <= 0) {
                        clearInterval(quizTimer);
                        Swal.close();

                        // auto-submit
                        submitAnswer(question.id, chosenOption || "");
                    }
                }, 1000);
            },

            willClose: () => {
                clearInterval(quizTimer);
                if (videoElement) videoElement.play();
                $('.vpl-lightbox-wrap').css('display','block');
            }
        });
    }

    function selectAnswer(questionId, optionId) {
        //console.log("User selected:", questionId, optionId);

        // Save answer locally (array for full quiz session)
        quizAnswers.push({
            question_id: questionId,
            option_id: optionId
        });

        Swal.close(); // Close popup immediately
        submitAnswer(questionId, optionId);
    }

    function submitAnswer(questionId, optionId) {
        let attemptId = getCookie("attempt_id");

        // Prepare current answer
        const currentAnswer = {
            question_id: questionId,
            option_id: optionId || null
        };

        // Store locally (for review or final submit)
        quizAnswers.push(currentAnswer);

        //console.log("Submitting SINGLE answer:", currentAnswer, "Attempt ID:", attemptId);

        fetch('/api/submit-quiz', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                movie_id: {{ $movie->id }},
                user_id: {{ auth()->id() ?? 'null' }},
                answer: currentAnswer,   // ONLY send 1, not full quizAnswers
                attempt_id: attemptId,
            })
        })
        .then(async (res) => {
            const raw = await res.text();
            //console.log("RAW:", raw);

            //let data;
            let decrypted = null;

            /*try { data = JSON.parse(raw); } catch(e) { return; }

            if (!attemptId && data.quizAttemptId) {
                setCookie("attempt_id", data.quizAttemptId, 3);
                attemptId = data.quizAttemptId;
            }*/

            try {
                const encryptedJson = JSON.parse(raw);

                // Decrypt using global helper
                decrypted = decryptResponse(encryptedJson);

                console.log("DECRYPTED:", decrypted);

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
                triggerQuiz(selectedQuestions[currentQIndex]);
            } /*else {
                console.log("Quiz finished.");
            }*/
        });
    }

    function showQuizPopup() {
        document.getElementById("custom-quiz-popup").style.display = "block";
    }

    function hideQuizPopup() {
        document.getElementById("custom-quiz-popup").style.display = "none";
    }


    function showFinalQuizResult(player) {
        //console.log("Movie ended — fetching final quiz result...");
        const attemptId = getCookie('attempt_id');
        fetch('/api/quiz-result', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ attemptId })
        })
        //.then(res => res.json())
        //.then(data => {
        .then(async (res) => {
            const raw = await res.text();
            // console.log("RAW ENCRYPTED RESULT:", raw);

            let decrypted = null;
            try {
                const encryptedJson = JSON.parse(raw);

                // Decrypt using helper
                decrypted = decryptResponse(encryptedJson);
                console.log("DECRYPTED RESULT:", decrypted);

            } catch (e) {
                console.error("QUIZ-RESULT DECRYPT ERROR:", e);
                return;
            }

            const { correctAnswerCount, totalQuestions, attemptId } = decrypted;
            if (typeof correctAnswerCount === 'undefined') {
                /*Swal.fire({
                  title: `
                    <img src="{{ asset('img/icon/quiz_application/alert.png') }}" width="28" alt="Error Icon" 
                         style="vertical-align:middle; margin-right:6px; margin-top:-4px;">
                    Error
                  `,
                  text: 'Could not fetch your quiz score.',
                  icon: 'error'
                });*/

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
                    confirmButtonText: `<img src="{{ asset('img/icon/quiz_application/home.png') }}" width="24" alt="goback"> Go Back`,
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
                }).then(() => {
                    window.location.href = "{{ url('/browse') }}";
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