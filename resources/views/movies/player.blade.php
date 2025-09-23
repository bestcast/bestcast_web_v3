
<div id="wrapper" class="vpl-skin-aviva vpl-customized"></div>
<div id="bigPlayerOuter" class="bigPlayerOuter dnn"></div>

<?php /*
<div id="bigPlayerOuter" class="bigPlayerOuter dnn"><div class="vpl-player-loader dnnshow"></div>
   <div id="bigPlayerText" class="dnn" style="opacity: 0;background: #000;">
      <div id="bigPlayer" class="vpl-skin-vega theatermode">
         <div class="vpl-player-holder">
            <div class="vpl-media-holder"></div>
            <div class="vpl-subtitle-holder">
               <div class="vpl-subtitle-holder-inner"></div>
            </div>
            <button type="button" class="vpl-big-play vpl-btn-reset">
               <svg viewBox="0 0 512 512">
                  <path d="M256 504c137 0 248-111 248-248S393 8 256 8 8 119 8 256s111 248 248 248zM40 256c0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216-118.7 0-216-96.1-216-216zm331.7-18l-176-107c-15.8-8.8-35.7 2.5-35.7 21v208c0 18.4 19.8 29.8 35.7 21l176-101c16.4-9.1 16.4-32.8 0-42zM192 335.8V176.9c0-4.7 5.1-7.6 9.1-5.1l134.5 81.7c3.9 2.4 3.8 8.1-.1 10.3L201 341c-4 2.3-9-.6-9-5.2z"></path>
               </svg>
            </button>
            <div class="vpl-player-loader"></div>
            <div class="vpl-player-controls vpl-player-controls-top">
               <button type="button" class="vpl-info-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Info">
                  <svg viewBox="0 0 512 512">
                     <path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm0-338c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                  </svg>
               </button>
               <button type="button" class="vpl-theater-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Theater mode">
                  <svg viewBox="0 0 459 459">
                     <path d="M408,51H51C22.95,51,0,73.95,0,102v255c0,28.05,22.95,51,51,51h357c28.05,0,51-22.95,51-51V102 C459,73.95,436.05,51,408,51z M408,357H51V102h357V357z"></path>
                  </svg>
               </button>
               <button type="button" class="vpl-fullscreen-toggle vpl-contr-btn vpl-btn-reset">
                  <span class="vpl-btn vpl-btn-fullscreen" data-tooltip="Fullscreen">
                     <svg viewBox="0 0 448 512">
                        <path d="M212.686 315.314L120 408l32.922 31.029c15.12 15.12 4.412 40.971-16.97 40.971h-112C10.697 480 0 469.255 0 456V344c0-21.382 25.803-32.09 40.922-16.971L72 360l92.686-92.686c6.248-6.248 16.379-6.248 22.627 0l25.373 25.373c6.249 6.248 6.249 16.378 0 22.627zm22.628-118.628L328 104l-32.922-31.029C279.958 57.851 290.666 32 312.048 32h112C437.303 32 448 42.745 448 56v112c0 21.382-25.803 32.09-40.922 16.971L376 152l-92.686 92.686c-6.248 6.248-16.379 6.248-22.627 0l-25.373-25.373c-6.249-6.248-6.249-16.378 0-22.627z"></path>
                     </svg>
                  </span>
                  <span class="vpl-btn vpl-btn-normal" data-tooltip="Exit Fullscreen">
                     <svg viewBox="0 0 448 512">
                        <path d="M4.686 427.314L104 328l-32.922-31.029C55.958 281.851 66.666 256 88.048 256h112C213.303 256 224 266.745 224 280v112c0 21.382-25.803 32.09-40.922 16.971L152 376l-99.314 99.314c-6.248 6.248-16.379 6.248-22.627 0L4.686 449.941c-6.248-6.248-6.248-16.379 0-22.627zM443.314 84.686L344 184l32.922 31.029c15.12 15.12 4.412 40.971-16.97 40.971h-112C234.697 256 224 245.255 224 232V120c0-21.382 25.803-32.09 40.922-16.971L296 136l99.314-99.314c6.248-6.248 16.379-6.248 22.627 0l25.373 25.373c6.248 6.248 6.248 16.379 0 22.627z"></path>
                     </svg>
                  </span>
               </button>
            </div>
            <div class="vpl-player-controls vpl-player-controls-main">
               <div class="vpl-player-controls-bottom">
                  <div class="vpl-seekbar">
                     <div class="vpl-progress-bg">
                        <div class="vpl-load-level"></div>
                        <div class="vpl-progress-level"></div>
                     </div>
                  </div>
                  <div class="vpl-player-controls-bottom-left">
                     <div class="vpl-media-time-current"></div>
                     <div class="vpl-live-note">
                        <div class="vpl-live-note-inner">
                           <div class="vpl-live-note-icon"></div>
                           <div class="vpl-live-note-title">LIVE</div>
                        </div>
                     </div>
                  </div>
                  <div class="vpl-player-controls-bottom-middle">
                     <button type="button" class="vpl-rewind-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Rewind">
                        <svg viewBox="0 0 512 512">
                           <path d="M255.545 8c-66.269.119-126.438 26.233-170.86 68.685L48.971 40.971C33.851 25.851 8 36.559 8 57.941V192c0 13.255 10.745 24 24 24h134.059c21.382 0 32.09-25.851 16.971-40.971l-41.75-41.75c30.864-28.899 70.801-44.907 113.23-45.273 92.398-.798 170.283 73.977 169.484 169.442C423.236 348.009 349.816 424 256 424c-41.127 0-79.997-14.678-110.63-41.556-4.743-4.161-11.906-3.908-16.368.553L89.34 422.659c-4.872 4.872-4.631 12.815.482 17.433C133.798 479.813 192.074 504 256 504c136.966 0 247.999-111.033 248-247.998C504.001 119.193 392.354 7.755 255.545 8z"></path>
                        </svg>
                     </button>
                     <button type="button" class="vpl-skip-backward-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Skip backward">
                        <svg viewBox="0 0 512 512">
                           <path d="M11.5 280.6l192 160c20.6 17.2 52.5 2.8 52.5-24.6V96c0-27.4-31.9-41.8-52.5-24.6l-192 160c-15.3 12.8-15.3 36.4 0 49.2zm256 0l192 160c20.6 17.2 52.5 2.8 52.5-24.6V96c0-27.4-31.9-41.8-52.5-24.6l-192 160c-15.3 12.8-15.3 36.4 0 49.2z"></path>
                        </svg>
                     </button>
                     <button type="button" class="vpl-playback-toggle vpl-contr-btn vpl-btn-reset">
                        <span class="vpl-btn vpl-btn-play">
                           <svg viewBox="0 0 448 512">
                              <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path>
                           </svg>
                        </span>
                        <span class="vpl-btn vpl-btn-pause">
                           <svg viewBox="0 0 448 512">
                              <path d="M144 479H48c-26.5 0-48-21.5-48-48V79c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v352c0 26.5-21.5 48-48 48zm304-48V79c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v352c0 26.5 21.5 48 48 48h96c26.5 0 48-21.5 48-48z"></path>
                           </svg>
                        </span>
                     </button>
                     <button type="button" class="vpl-skip-forward-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Skip forward">
                        <svg viewBox="0 0 512 512">
                           <path d="M500.5 231.4l-192-160C287.9 54.3 256 68.6 256 96v320c0 27.4 31.9 41.8 52.5 24.6l192-160c15.3-12.8 15.3-36.4 0-49.2zm-256 0l-192-160C31.9 54.3 0 68.6 0 96v320c0 27.4 31.9 41.8 52.5 24.6l192-160c15.3-12.8 15.3-36.4 0-49.2z"></path>
                        </svg>
                     </button>
                  </div>
                  <div class="vpl-player-controls-bottom-right">
                     <div class="vpl-volume-wrapper vpl-contr-btn">
                        <button type="button" class="vpl-volume-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Volume">
                           <span class="vpl-btn vpl-btn-volume-up">
                              <svg viewBox="0 0 576 512">
                                 <path d="M215.03 71.05L126.06 160H24c-13.26 0-24 10.74-24 24v144c0 13.25 10.74 24 24 24h102.06l88.97 88.95c15.03 15.03 40.97 4.47 40.97-16.97V88.02c0-21.46-25.96-31.98-40.97-16.97zm233.32-51.08c-11.17-7.33-26.18-4.24-33.51 6.95-7.34 11.17-4.22 26.18 6.95 33.51 66.27 43.49 105.82 116.6 105.82 195.58 0 78.98-39.55 152.09-105.82 195.58-11.17 7.32-14.29 22.34-6.95 33.5 7.04 10.71 21.93 14.56 33.51 6.95C528.27 439.58 576 351.33 576 256S528.27 72.43 448.35 19.97zM480 256c0-63.53-32.06-121.94-85.77-156.24-11.19-7.14-26.03-3.82-33.12 7.46s-3.78 26.21 7.41 33.36C408.27 165.97 432 209.11 432 256s-23.73 90.03-63.48 115.42c-11.19 7.14-14.5 22.07-7.41 33.36 6.51 10.36 21.12 15.14 33.12 7.46C447.94 377.94 480 319.54 480 256zm-141.77-76.87c-11.58-6.33-26.19-2.16-32.61 9.45-6.39 11.61-2.16 26.2 9.45 32.61C327.98 228.28 336 241.63 336 256c0 14.38-8.02 27.72-20.92 34.81-11.61 6.41-15.84 21-9.45 32.61 6.43 11.66 21.05 15.8 32.61 9.45 28.23-15.55 45.77-45 45.77-76.88s-17.54-61.32-45.78-76.86z"></path>
                              </svg>
                           </span>
                           <span class="vpl-btn vpl-btn-volume-down">
                              <svg viewBox="0 0 384 512">
                                 <path d="M215.03 72.04L126.06 161H24c-13.26 0-24 10.74-24 24v144c0 13.25 10.74 24 24 24h102.06l88.97 88.95c15.03 15.03 40.97 4.47 40.97-16.97V89.02c0-21.47-25.96-31.98-40.97-16.98zm123.2 108.08c-11.58-6.33-26.19-2.16-32.61 9.45-6.39 11.61-2.16 26.2 9.45 32.61C327.98 229.28 336 242.62 336 257c0 14.38-8.02 27.72-20.92 34.81-11.61 6.41-15.84 21-9.45 32.61 6.43 11.66 21.05 15.8 32.61 9.45 28.23-15.55 45.77-45 45.77-76.88s-17.54-61.32-45.78-76.87z"></path>
                              </svg>
                           </span>
                           <span class="vpl-btn vpl-btn-volume-off">
                              <svg viewBox="0 0 640 512">
                                 <path d="M633.82 458.1l-69-53.33C592.42 360.8 608 309.68 608 256c0-95.33-47.73-183.58-127.65-236.03-11.17-7.33-26.18-4.24-33.51 6.95-7.34 11.17-4.22 26.18 6.95 33.51 66.27 43.49 105.82 116.6 105.82 195.58 0 42.78-11.96 83.59-33.22 119.06l-38.12-29.46C503.49 318.68 512 288.06 512 256c0-63.09-32.06-122.09-85.77-156.16-11.19-7.09-26.03-3.8-33.12 7.41-7.09 11.2-3.78 26.03 7.41 33.13C440.27 165.59 464 209.44 464 256c0 21.21-5.03 41.57-14.2 59.88l-39.56-30.58c3.38-9.35 5.76-19.07 5.76-29.3 0-31.88-17.53-61.33-45.77-76.88-11.58-6.33-26.19-2.16-32.61 9.45-6.39 11.61-2.16 26.2 9.45 32.61 11.76 6.46 19.12 18.18 20.4 31.06L288 190.82V88.02c0-21.46-25.96-31.98-40.97-16.97l-49.71 49.7L45.47 3.37C38.49-2.05 28.43-.8 23.01 6.18L3.37 31.45C-2.05 38.42-.8 48.47 6.18 53.9l588.36 454.73c6.98 5.43 17.03 4.17 22.46-2.81l19.64-25.27c5.41-6.97 4.16-17.02-2.82-22.45zM32 184v144c0 13.25 10.74 24 24 24h102.06l88.97 88.95c15.03 15.03 40.97 4.47 40.97-16.97V352.6L43.76 163.84C36.86 168.05 32 175.32 32 184z"></path>
                              </svg>
                           </span>
                        </button>
                        <div class="vpl-volume-seekbar vpl-volume-horizontal">
                           <div class="vpl-volume-bg">
                              <div class="vpl-volume-level"></div>
                           </div>
                        </div>
                     </div>
                     <button type="button" class="vpl-cc-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Subtitles (c)">
                        <svg role="img" viewBox="0 0 512 512">
                           <path d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM218.1 287.7c2.8-2.5 7.1-2.1 9.2.9l19.5 27.7c1.7 2.4 1.5 5.6-.5 7.7-53.6 56.8-172.8 32.1-172.8-67.9 0-97.3 121.7-119.5 172.5-70.1 2.1 2 2.5 3.2 1 5.7l-17.5 30.5c-1.9 3.1-6.2 4-9.1 1.7-40.8-32-94.6-14.9-94.6 31.2.1 48 51.1 70.5 92.3 32.6zm190.4 0c2.8-2.5 7.1-2.1 9.2.9l19.5 27.7c1.7 2.4 1.5 5.6-.5 7.7-53.5 56.9-172.7 32.1-172.7-67.9 0-97.3 121.7-119.5 172.5-70.1 2.1 2 2.5 3.2 1 5.7L420 222.2c-1.9 3.1-6.2 4-9.1 1.7-40.8-32-94.6-14.9-94.6 31.2 0 48 51 70.5 92.2 32.6z"></path>
                        </svg>
                     </button>
                     <div class="vpl-settings-wrap vpl-contr-btn">
                        <button type="button" class="vpl-settings-toggle vpl-contr-btn vpl-btn-reset" data-tooltip="Settings">
                           <svg viewBox="0 0 512 512">
                              <path d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"></path>
                           </svg>
                        </button>
                        <div class="vpl-settings-holder">
                           <div class="vpl-settings-home">
                              <div role="menu">
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" data-target="vpl-playback-rate-menu-holder"><span class="vpl-settings-menu-item-title">Speed</span><span class="vpl-settings-menu-item-value vpl-playback-rate-menu-value">1</span></button>
                                 <button type="button" class="vpl-menu-item vpl-subtitle-settings-menu vpl-force-hide vpl-btn-reset" data-target="vpl-subtitle-menu-holder"><span class="vpl-settings-menu-item-title">Subtitles</span><span class="vpl-settings-menu-item-value vpl-subtitle-menu-value"></span></button>
                              </div>
                           </div>
                           <div class="vpl-playback-rate-menu-holder vpl-settings-menu">
                              <button type="button" class="vpl-menu-header vpl-btn-reset">
                              <span>Speed</span>
                              </button>
                              <div role="menu" class="vpl-playback-rate-menu">
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" role="menuitemradio" aria-checked="false" tabindex="0" data-value="2">2x</button>
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" role="menuitemradio" aria-checked="false" tabindex="0" data-value="1.5">1.5x</button>
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" role="menuitemradio" aria-checked="false" tabindex="0" data-value="1.25">1.25x</button>
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" role="menuitemradio" aria-checked="false" tabindex="0" data-value="1">Normal</button>
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" role="menuitemradio" aria-checked="false" tabindex="0" data-value="0.5">0.5x</button>
                                 <button type="button" class="vpl-menu-item vpl-btn-reset" role="menuitemradio" aria-checked="false" tabindex="0" data-value="0.25">0.25x</button>
                              </div>
                           </div>
                           <div class="vpl-subtitle-menu-holder vpl-settings-menu">
                              <button type="button" class="vpl-menu-header vpl-btn-reset">
                              <span>Subtitles</span>
                              </button>
                              <div role="menu" class="vpl-subtitle-menu"></div>
                           </div>
                        </div>
                     </div>
                     <div class="vpl-media-time-total"></div>
                  </div>
               </div>
            </div>
            <div class="vpl-share-holder"></div>
            <div class="vpl-info-holder">
               <div class="vpl-info-holder-inner">
                  <div class="vpl-info-data">
                     <button type="button" class="vpl-info-close vpl-contr-btn vpl-btn-reset" data-tooltip="Close">
                        <svg viewBox="0 0 320 512">
                           <path d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z"></path>
                        </svg>
                     </button>
                     <div class="vpl-info-inner">
                        <div class="vpl-info-description"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="vpl-resume-holder">
               <div class="vpl-resume-holder-inner">
                  <div class="vpl-resume-data">
                     <div class="vpl-resume-inner">
                        <button type="button" class="vpl-resume-action-container vpl-resume-continue vpl-btn-reset">
                           <span class="vpl-contr-btn">
                              <svg role="img" viewBox="0 0 373.008 373.008">
                                 <path d="M61.792,2.588C64.771,0.864,68.105,0,71.444,0c3.33,0,6.663,0.864,9.655,2.588l230.116,167.2 c5.963,3.445,9.656,9.823,9.656,16.719c0,6.895-3.683,13.272-9.656,16.713L81.099,370.427c-5.972,3.441-13.334,3.441-19.302,0 c-5.973-3.453-9.66-9.833-9.66-16.724V19.305C52.137,12.413,55.818,6.036,61.792,2.588z"></path>
                              </svg>
                           </span>
                           <span class="vpl-resume-title">Continue watching</span>
                        </button>
                        <div class="vpl-resume-action-container-separator"></div>
                        <button type="button" class="vpl-resume-action-container vpl-resume-restart vpl-btn-reset">
                           <span class="vpl-contr-btn">
                              <svg role="img" viewBox="0 0 512 512">
                                 <path d="M255.545 8c-66.269.119-126.438 26.233-170.86 68.685L48.971 40.971C33.851 25.851 8 36.559 8 57.941V192c0 13.255 10.745 24 24 24h134.059c21.382 0 32.09-25.851 16.971-40.971l-41.75-41.75c30.864-28.899 70.801-44.907 113.23-45.273 92.398-.798 170.283 73.977 169.484 169.442C423.236 348.009 349.816 424 256 424c-41.127 0-79.997-14.678-110.63-41.556-4.743-4.161-11.906-3.908-16.368.553L89.34 422.659c-4.872 4.872-4.631 12.815.482 17.433C133.798 479.813 192.074 504 256 504c136.966 0 247.999-111.033 248-247.998C504.001 119.193 392.354 7.755 255.545 8z"></path>
                              </svg>
                           </span>
                           <span class="vpl-resume-title">Restart from beginning</span>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="vpl-context-menu">
               <div class="mvp-context-menu-inner">
                  <button type="button" class="vpl-context-btn vpl-context-copy-video-url vpl-btn-reset">
                  <span>Copy video url at current time</span>
                  </button>
               </div>
            </div>
            <div class="vpl-preview-seek-wrap">
               <div class="vpl-preview-seek-inner"></div>
               <div class="vpl-preview-seek-time">
                  <div class="vpl-preview-seek-time-current">0:00</div>
               </div>
            </div>
         </div>
         <div class="vpl-tooltip"></div>
         <div class="vpl-player-controls">
            <div class="vpl-back ICineLeft"></div>
         </div>
      </div>
   </div>
</div>

*/?>