@extends('layouts.frontend')

@section('header-script')
    <div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>
    <style>
        .vpl-lightbox-wrap .vpl-lightbox-close{display: none !important;}
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Confirm -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <!-- Popup animation -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>


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
    <!-- <div id="quiz-container" style="display:none; margin-top: 20px;"></div> -->

    <div id="custom-quiz-popup" style="display:none;">
        <div id="quiz-timer" style="color:red; font-weight:bold; margin-bottom:10px;">Time left: 10s</div>

        <div id="quiz-question-text" style="font-size:18px; margin-bottom:15px;"></div>
        
        <div id="quiz-options" style="margin-bottom:15px;"></div>
        
        <button id="next-question-btn" style="display:none;" class="btn btn-primary submit-button">Next</button>
        <!-- <button type="button" class="next-button next-button">Next</button> -->
    </div>

    <div class="modal confirm-popup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="movieId" value="{{ $movie->id }}">
    <style type="text/css">
        .vpl-settings-menu .vpl-quality-menu .vpl-menu-item.vpl-btn-reset{display: none;}
        .vpl-settings-menu .vpl-quality-menu .vpl-menu-item.vpl-btn-reset.vpl-menu-active{display: block;}
    </style>

<?php
    $referurl=empty($_GET['refer'])?url('/browse'):$_GET['refer'];
    $usermovie=(empty($movie->usermovies[0]))?'':$movie->usermovies[0];
    //dd($usermovie); user_id
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
    //dd($plan);
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
                //console.log("Movie currentTime in seconds:", getCurrentTime);
                const cookieValue = getCookie("quiz_popup_{{ $movie->id }}");
                var isPaused=0;
                player.addEventListener("mediaPause", function(data){
                    isPaused=1;
                });
                //let quizPromptShown = false;
                player.addEventListener("mediaPlay", function() {
                    isPaused = 0;

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
                        //console.log("Movie currentTime in seconds:", getCurrentTime);
                        const currentTime = Math.floor(player.currentTime);
                         // If disclaimer already accepted earlier
                        if(cookieValue === '1' && disclaimerAccepted === false){
                            disclaimerAccepted = true;
                            quizActive = true;
                        }
                        // Trigger first quiz ONLY when 15 min reached (and disclaimer accepted)
                        if(disclaimerAccepted && !firstQuizTriggered && getCurrentTime >= 900){ 
                            firstQuizTriggered = true; // make sure it's only once
                            startQuiz(movieId); // This will then handle later intervals (30, 45, etc.)
                        }

                        let movieDuration = parseInt(player.getDuration(), 10);
                        //console.log("Movie duration in seconds:",movieDuration);
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
                }, (intervalSecond*1000)); //5000 is 5seconds only and when exit clearInterval(moviePlayInterval);
                $('.vpl-back-refer').on('click',function(){
                    clearInterval(moviePlayInterval);

                    if (player && typeof player.cleanMedia === 'function') {
                        player.cleanMedia();
                    }
                    /*player.cleanMedia();*/
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

    var quizAnswers = [];
    let currentQIndex = 0;
    let quizTimer;
    let timeLeft = 10;
    let selectedQuestions = [];
    let quizShown = false;
    let disclaimerAccepted = false;
    let quizActive = false;
    let questions = [];
    let quizPopupCookie = `quiz_popup_{{ $movie->id }}`;
    let quizLastTriggeredAt = 0;
    let quizInterval = null;
    let firstQuizTriggered = false;
    let questionLock = false; // prevent double-next on same question

    let quizPromptShownOnce = false;

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
        return '';
    }

    function setCookie(name, value, hours) {
        const d = new Date();
        d.setTime(d.getTime() + (hours * 60 * 60 * 1000));
        document.cookie = name + "=" + value + "; expires=" + d.toUTCString() + "; path=/";
    }

    function clearCookie(name) {
        // Clear without path
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC;`;
        // Clear with path
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;

        // Optional: verify immediately
        if (document.cookie.split('; ').find(row => row.startsWith(name + '='))) {
            console.warn(`Cookie "${name}" might not be fully cleared!`);
        } else {
            console.log(`Cookie "${name}" cleared successfully.`);
        }
    }

    function showFullscreenSafePopup() {
        const quizPopup = document.getElementById("custom-quiz-popup");

        // Prevent duplicate popups
        if (quizPopup.style.display === "flex") return;

        const fullscreenElement = document.fullscreenElement;

        // Move popup container safely
        if (fullscreenElement) {
            fullscreenElement.appendChild(quizPopup);
        } else {
            document.body.appendChild(quizPopup);
        }

        // Style only the popup, not the entire screen
        Object.assign(quizPopup.style, {
            display: "flex",
            position: "fixed",
            top: "50%",
            left: "50%",
            transform: "translate(-50%, -50%)",
            zIndex: "999999999",
            background: "rgba(0, 0, 0, 0.7)", // semi-transparent smaller overlay
            color: "#fff",
            padding: "20px 30px",
            borderRadius: "16px",
            textAlign: "center",
            width: "auto",
            maxWidth: "400px",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            backdropFilter: "blur(3px)",
        });

        // Pause video instead of darkening background
        const video = document.querySelector("video");
        if (video && !video.paused) {
            video.pause();
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
                <div class="quiz-question-header">
                    <div class="quiz-icon">
                        <img src="{{ asset('img/icon/quiz_application/question-mark.png') }}" width="40" alt="Question Icon">
                    </div>
                    <h2 class="quiz-title">
                        <img src="{{ asset('img/icon/quiz_application/target.png') }}" width="30" alt="Target Icon">
                        Quiz Time!
                    </h2>
                  
                  <p class="quiz-subtitle">You Want to play the quiz?</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: `<img src="{{ asset('img/icon/quiz_application/joystick.png') }}" width="24" alt="Play"> Yes, Let's Play!`,
            cancelButtonText: `<img src="{{ asset('img/icon/quiz_application/close.png') }}" width="22" alt="notnow"> Not Now`,
            allowOutsideClick: false,
            target: fullscreenContainer, // Works in both fullscreen and normal
            customClass: {
                popup: 'custom-swal-popup',
                confirmButton: 'custom-swal-confirm',
                cancelButton: 'custom-swal-cancel'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                // User clicked "Yes"
                Swal.fire({
                    icon: 'info',
                    title: `<img src="{{ asset('img/icon/quiz_application/alert.png') }}" width="24" alt="disclaimer"> Disclaimer`,
                    html: `Please don't click <strong>forward</strong> or <strong>rewind</strong> during the movie.<br><br><img src="{{ asset('img/icon/quiz_application/brain.png') }}" width="24" alt="Brain" style="vertical-align:middle; margin-right:4px;">
                          Quiz questions may appear <strong>anytime</strong>.`,
                    confirmButtonText: "OK, I'm Ready!",
                    confirmButtonColor: "#6a0dad", // violet color
                    allowOutsideClick: false,
                    target: fullscreenContainer, // Works in both fullscreen and normal
                    customClass: {
                        popup: 'custom-swal-popup disclaimer-popup',
                        confirmButton: 'custom-swal-confirm'
                    }
                }).then((result) => {
                    /*checkForQuiz(movieId);*/
                    if (result.isConfirmed) {
                      setCookie("quiz_popup_{{ $movie->id }}", 1, 24); // store for 24 hours
                        disclaimerAccepted = true;
                        quizActive = true;
                        /*startQuiz(movieId);*/
                        if (videoElement) videoElement.play(); // Resume
                        $('.vpl-lightbox-wrap').css('display','block');
                    }else {
                        setCookie("quiz_popup_{{ $movie->id }}", 0, 24);
                    }
                });
            }else{
                if (videoElement) videoElement.play(); // Resume
                $('.vpl-lightbox-wrap').css('display','block');
            }
        });
    }

    function getCurrentTime() {
        const player = document.querySelector('video'); // your video element
        return player ? Math.floor(player.currentTime) : 0;
    }

    function timeToSeconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(':').map(Number);
        return (hours * 3600) + (minutes * 60) + seconds;
    }
    
    function startQuiz(movieId) {
        quizAnswers = [];
        //console.log(quizAnswers);
        // Set state only — do not fetch or show questions now
        //console.log("Quiz Mode Started");
        disclaimerAccepted = true;
        quizActive = true;
        quizLastTriggeredAt = 0; // Reset to trigger first quiz at 15 mins

        quizInterval = setInterval(() => {
            const video = document.querySelector('#wrapper video');
            if (!video || video.paused) return;

            const currentMinutes = Math.floor(video.currentTime / 60);

            // Only trigger if we're at a new 15-minute window
            if (currentMinutes % 15 === 0 && currentMinutes !== quizLastTriggeredAt) {
                quizLastTriggeredAt = currentMinutes;
            quizAnswers = []; //Reset answers for this round
            let from_time = incrementFromTime();
            //document.cookie = `from_time=0; path=/; max-age=3600;`;
            //console.log(document.cookie);
            $('.vpl-lightbox-wrap').css('display','contents');
            let videoElement = document.querySelector('#wrapper video');
            if (videoElement) videoElement.pause(); // Pause video manually
            fetch(`/api/quiz/${movieId}`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json', // sending JSON data
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    from_time: from_time, 
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.questions && data.questions.length) {
                        $('.vpl-lightbox-wrap').css('opacity','0');
                        document.getElementById('custom-quiz-popup').style.display = 'block';
                        startQuizFlow(data.questions);
                    }else if (data.skip) {
                        //console.log("⏭ No questions in this interval, skipping ahead");
                        //incrementFromTime();
                        if (videoElement) videoElement.play(); // Resume
                        $('.vpl-lightbox-wrap').css('display','block');
                        startQuizFlow(data.questions);
                    } else {
                        window.location.href = data.redirect;
                        console.log("No more questions available");
                    }
                })
                .catch(err => console.error("API Error:", err));
            }
        }, 10000); // check every 10 seconds
        
    }


    function incrementFromTime() {
        let cookies = Object.fromEntries(
            document.cookie.split('; ').map(c => c.split('='))
        );

        if (!cookies.from_time) {
            // Cookie doesn't exist → create with default 0
            document.cookie = `from_time=0; path=/; max-age=86400;`;
            //console.log("from_time created with value 0");
            return 0;
        } else {
            // Cookie exists → increment by 15
            let current = parseInt(cookies.from_time, 10) || 0;
            let updated = current + 15;
            document.cookie = `from_time=${updated}; path=/; max-age=86400;`;
            //console.log(`from_time incremented to ${updated}`);
            return updated;
        }
    }

    function startQuizFlow(questions) {
        quizAnswers = []; //Reset answers for this interval
        selectedQuestions = questions;
        currentQIndex = 0;
        renderCurrentQuestion(selectedQuestions[currentQIndex]);
    }
    function hideQuizPopup() {
        const quizPopup = document.getElementById("custom-quiz-popup");
        quizPopup.style.display = "none";

        // Move popup back to body
        if (!document.body.contains(quizPopup)) {
            document.body.appendChild(quizPopup);
        }

        // Restore video layer
        const wrapper = document.getElementById("wrapper");
        wrapper.style.filter = "none";

        $('.vpl-lightbox-wrap').css({
            'display': 'block',
            'opacity': '1'
        });
    }

    function renderCurrentQuestion(questionObj) {
        if (!questionObj) {
            console.warn("No question to render.");
            return; // Do NOT hide popup anymore
        }

        //console.log("Rendering question:", questionObj);

        if (!questionObj.options || !Array.isArray(questionObj.options)) {
            //console.error("Options missing or not an array:", questionObj);
            return;
        }

        const qText = document.getElementById('quiz-question-text');
        const optionsDiv = document.getElementById('quiz-options');
        const nextBtn = document.getElementById('next-question-btn');

        // Reset lock for this question
        questionLock = false;

        qText.innerHTML = `<strong>Q${currentQIndex + 1}:</strong> ${questionObj.question}`;
        optionsDiv.innerHTML = '';
        nextBtn.innerText = "Save";
        nextBtn.style.display = 'none';
        //nextBtn.innerText = (currentQIndex === selectedQuestions.length - 1) ? "Submit" : "Next";

        questionObj.options.forEach((opt, i) => {
            const optId = `q${questionObj.id}_opt${i}_${opt.id}`;

            const label = document.createElement('label');
            label.setAttribute("for", optId);
            label.className = 'quiz-option-label';

            const input = document.createElement('input');
            input.type = 'radio';
            input.name = `q${questionObj.id}`;
            input.value = opt.id;
            input.id = optId;

            input.addEventListener('change', () => {
                nextBtn.style.display = 'inline-block';
            });

            label.appendChild(input);
            label.appendChild(document.createTextNode(opt.name));
            optionsDiv.appendChild(label);
        });

        // Start the 10-second timer
        timeLeft = 10;
        document.getElementById('quiz-timer').innerHTML = `Time left: ${timeLeft}s`;

        clearInterval(quizTimer);
        quizTimer = setInterval(() => {
            timeLeft--;
            document.getElementById('quiz-timer').innerHTML = `Time left: ${timeLeft}s`;
            if (timeLeft <= 0) {
                clearInterval(quizTimer);
                saveAnswerAndNext(); // auto-next
            }
        }, 1000);

        /*document.getElementById("custom-quiz-popup").style.display = "block";*/
        showFullscreenSafePopup();
    }

    function saveAnswerAndNext() {
        if (questionLock) return; // stop double calls
        questionLock = true;

        clearInterval(quizTimer);

        const questionObj = selectedQuestions[currentQIndex];

        if (!questionObj) {
            console.warn("Tried to save answer, but no question exists for index:", currentQIndex);
            return;
        }
        const selected = document.querySelector(`input[name="q${questionObj.id}"]:checked`);
        const selectedOptionValue = selected ? selected.value : null;

        // Save answer
        quizAnswers.push({
            question_id: questionObj.id,
            selectedOptionValue: selectedOptionValue,
            selected_option_id: selected ? selected.id : null
        });

        currentQIndex++;

        if (currentQIndex < selectedQuestions.length) {
            renderCurrentQuestion(selectedQuestions[currentQIndex]);
        } else {
            //document.getElementById("custom-quiz-popup").style.display = "none";
            hideQuizPopup();
            submitQuiz();
        }
    }
    document.getElementById('next-question-btn').addEventListener('click', saveAnswerAndNext);
    
    function submitQuiz() {
        const attemptId = getCookie("attempt_id");

        let videoElement = document.querySelector('#wrapper video');
        if (videoElement) videoElement.play(); //Pause video manually
        $('.vpl-lightbox-wrap').css({
            'display': 'block',
            'opacity': '1'
        });
        fetch('/api/submit-quiz', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ 
                movie_id: {{ $movie->id }},
                user_id: {{ auth()->id() ?? 'null' }},
                answers: quizAnswers,
                attempt_id: attemptId,
            })
        })
        .then(async response => {
            const text = await response.text(); // get raw text
            //console.log("Raw response:", text);
            try {
                const data = JSON.parse(text);
                const totalQuestions = data.totalQuestions;
                const correctAnswerCount = data.correctAnswerCount;
                const passingScore = totalQuestions - 1;
                /*console.log("passingScore:", passingScore);
                console.log("correctAnswerCount:", correctAnswerCount);*/

                if (data.quizAttemptId) {
                    if (!getCookie("attempt_id")) {
                        //console.log("No attempt_id cookie found. Creating one...");
                        setCookie("attempt_id", data.quizAttemptId, 1); // expires in 1 day
                    } else {
                        //console.log("Attempt ID cookie already exists:", attemptId);
                    }
                }
            } catch (error) {
                console.error("JSON parse error:", error);
            }
        })
        .catch(() => {
            alert('Something went wrong submitting your quiz.');
        });
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
        .then(res => res.json())
        .then(data => {
            const { correctAnswerCount, totalQuestions, attemptId } = data;
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
            clearCookie("from_time");
            clearCookie("attempt_id");
        })
        .catch(err => {
            console.error('Result fetch error:', err);
        });
    }
    document.addEventListener("fullscreenchange", () => {
        const quizPopup = document.getElementById("custom-quiz-popup");
        if (quizPopup.style.display === "flex") {
            // move the popup without showing it again
            const fullscreenElement = document.fullscreenElement;
            if (fullscreenElement) {
                fullscreenElement.appendChild(quizPopup);
            } else {
                document.body.appendChild(quizPopup);
            }
        }
    });

</script>
@endsection