@extends('layouts.frontend')

@section('header-script')
<?php

echo "remove die";die();

?>
<link rel='stylesheet' href="{{ url('/') }}/css/custom.css?time=1710952075" media='all' />

        <script type="text/javascript" src="{{ url('/') }}/vlite/new.js"></script>
        
        <link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
      <link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
@endsection

@section('content') 

<script type="text/javascript">

              ////path:"https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Testing/Encoded/1080/trailer%201080p.m3u8",
              //path:"https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Anbulla_Ghilli_Encoded/Anbulla_Ghilli.m3u8",
              //https://d2vkl8k9v6nxyr.cloudfront.net/Movies/BatteryEncoded/Audio+ans+Subtitles/SRT/Battery_Reel01.vtt

         var player;  
         document.addEventListener("DOMContentLoaded", function(event) { 
            
            var settings = {
               instanceName:"player1",
               volume:0.5,
               autoPlay:true,
                    activeItem:0,
               mediaEndAction:"next",
               aspectRatio:1,
                    preload:'metadata',
                    randomPlay:false,
                    loopingOn:true,
                    rightClickContextMenu:'custom',
                    facebookAppId:"644413448983338",
                    elementsVisibilityArr:[
                        {width:600, elements:['play', 'next', 'seekbar', 'fullscreen', 'settings', 'share', 'info', 'volume', 'pip']},
                    ],
                    youtubePlayerType:'chromeless',
                    vimeoPlayerType:'chromeless',
                    forceYoutubeChromeless:true,
                    displayPosterOnMobile:false,
                    useKeyboardNavigationForPlayback:false,
                    playerType:'lightbox',

                    media:[
                        
                        {
                            type:'hls',
                            path:"https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Anbulla_Ghilli_Encoded/Anbulla_Ghilli.m3u8",
                            previewSeek: 'https://d2vkl8k9v6nxyr.cloudfront.net/Movies/BatteryEncoded/Audio+ans+Subtitles/SRT/Battery_Reel01.vtt',
                            poster:"{{ url('/') }}/img/sample/movies/pichuvakathi/image.png",
                            description:'<span class="info-title">This is video title</span><br><span class="info-description">Duis egestas, quam faucibus interdum <a href="http://codecanyon.net/user/Tean" target="_blank">this is a link</a>, enim sem tincidunt tellus, sed condimentum tellus. Praesent molestie. Nunc Venenatis Sapien Ultrices Dui. Vivamus dolor. Integer vel ante.</span>',
                            subtitles:[
                                {   
                                    label: 'English',
                                    src: 'https://d2vkl8k9v6nxyr.cloudfront.net/Movies/BatteryEncoded/Audio+ans+Subtitles/SRT/Battery_Reel01.vtt',
                                    active:true
                                },
                                {
                                    label: 'German',
                                    src: 'https://d2vkl8k9v6nxyr.cloudfront.net/Movies/BatteryEncoded/Audio+ans+Subtitles/SRT/Battery_Reel01.vtt'
                                },
                                {
                                    label: 'Spanish',
                                    src: 'https://d2vkl8k9v6nxyr.cloudfront.net/Movies/BatteryEncoded/Audio+ans+Subtitles/SRT/Battery_Reel01.vtt'
                                }
                            ],

                        },

                    ]
                            
            };

            document.querySelector('.some-image').addEventListener('click', function(){
                    if(!player){//init player first time
                        fetch("{{ url('/') }}/vlite/skin/aviva.txt")
                        .then(response => response.text())
                        .then(content => {
                            var wrapper = document.getElementById("wrapper")
                            wrapper.innerHTML = content; 
                            player = new vpl(wrapper, settings);
                        })
                    }else{
                        player.resume();//reopen lightbox 
                    }
            });

            });

</script>


        <style type="text/css">

             *{
                margin:0;
                padding:0;
                border:0;
            }

            a{ 
                text-decoration: none; 
            }

            .vpl-player{
                margin: 50px auto;
                max-width: 1000px;
                width:100%;
                font-family: Arial, Helvetica, sans-serif;
            }
 
         .info{
            max-width: 500px;
            margin: 50px auto;

        }
        .info p{
            margin: 10px;
        }
        .some-image{
            max-width: 300px;
            cursor: pointer;
            -webkit-filter: grayscale(1); 
            filter: grayscale(1); 
            -webkit-transition : -webkit-filter 500ms linear;
            -transition : filter 500ms linear;
            margin: 0 auto;
        }
        .some-image:hover{
            -webkit-filter: grayscale(0);
            filter: none;
        }
        .some-image img{
            width:100%;
            display: block;

        }
        
        </style>

        <div class="info">
        <p>Clicking the image will open player in ligthbox mode. Any DOM element can trigger player to open in lightbox. If you have multiple videos in the player, everytime you close ligthbox and reopen it again, player can continue playing at last watched video.
        </p>
        <div class="some-image">Test</div><!-- element which will trigger lightbox to open -->
        </div>

        <div id="wrapper" class="vpl-skin-aviva"></div>

      </body>


@endsection