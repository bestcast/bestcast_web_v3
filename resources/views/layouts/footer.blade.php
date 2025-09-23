@if(!empty($footer))
    <footer class="eduvibe-footer-one edu-footer footer-style-default">
      <div class="footer-top">
          <div class="container eduvibe-animated-shape">
              <div class="row g-5">
                  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="edu-footer-widget">
                         <?php $id='general_footer_section1';?>
                         @if($core[$id.'title'])
                            <h5 class="widget-title">{{ $core[$id.'title'] }}</h5>
                         @endif
                         @if($core['general_footer_logo'])
                             <div class="logo">
                                 <!-- <a href="index.html"> -->
                                     <a href="{{ route('browse') }}">
                                     <img class="logo-light" src="{{ Lib::publicUrl($core['general_footer_logo']) }}" alt="Logo">
                                 </a>
                             </div>
                         @endif
                         @if($core[$id])
                            <p class="description">{!! Lib::sh($core[$id]) !!}</p>
                         @endif
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="edu-footer-widget explore-widget">
                          <?php $id='general_footer_section2';?>
                          @if($core[$id.'title'])
                               <h5 class="widget-title">{{ $core[$id.'title'] }}</h5>
                          @endif
                          @if($core[$id])
                               <div class="inner footer-link">{!! Lib::sh($core[$id]) !!}</div>
                               <div class="inner footer-link">
                                   @if($core['general_footer_links'])
                                       {!! Lib::sh($core['general_footer_links']) !!}
                                   @endif
                               </div>
                          @endif
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="edu-footer-widget quick-link-widget">
                          <?php $id='general_footer_section3';?>
                          @if($core[$id.'title'])
                               <h5 class="widget-title">{{ $core[$id.'title'] }}</h5>
                          @endif
                          @if($core[$id])
                               <div class="inner footer-link">{!! Lib::sh($core[$id]) !!}</div>
                          @endif
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="edu-footer-widget">
                          <?php $id='general_footer_section4';?>
                          @if($core[$id.'title'])
                               <h5 class="widget-title">{{ $core[$id.'title'] }}</h5>
                          @endif
                          @if($core[$id])
                               <div class="inner information-list">{!! Lib::codeReplace($core[$id]) !!}</div>
                          @endif
                             <?php /*{!! Lib::code('[form type="newsletter"]') !!}*/?>

                         @if($core['socialmedia_footer_enable'])
                            <ul class="social-share">
                               @if(!empty($core['socialmedia_facebook_link']))
                                  <li><a href="{{ $core['socialmedia_facebook_link'] }}" target="_blank"><i class="ri-facebook-box-fill"></i></a></li>
                               @endif
                               @if(!empty($core['socialmedia_instagram_link']))
                                  <li><a href="{{ $core['socialmedia_instagram_link'] }}" target="_blank"><i class="ri-instagram-fill"></i></a></li>
                               @endif
                               @if(!empty($core['socialmedia_twitter_link']))
                                  <li><a href="{{ $core['socialmedia_twitter_link'] }}" target="_blank"><i class="ri-twitter-fill"></i></a></li>
                               @endif
                               @if(!empty($core['socialmedia_linked_link']))
                                  <li><a href="{{ $core['socialmedia_linked_link'] }}" target="_blank"><i class="ri-linkedin-box-fill"></i></a></li>
                               @endif
                               @if(!empty($core['socialmedia_youtube_link']))
                                  <li><a href="{{ $core['socialmedia_youtube_link'] }}" target="_blank"><i class="ri-youtube-fill"></i></a></li>
                               @endif
                               @if(!empty($core['socialmedia_email_link']))
                                  <li><a href="{{ $core['socialmedia_email_link'] }}" target="_blank"><i class="ri-mail-fill"></i></a></li>
                               @endif
                               @if(!empty($core['socialmedia_pininterest_link']))
                                  <li><a href="{{ $core['socialmedia_pininterest_link'] }}" target="_blank"><i class="ri-pinterest-fill"></i></a></li>
                               @endif
                            </ul>
                         @endif
                      </div>
                  </div>
              </div>
            <div class="row py-3">
                <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-start">
                    @if($core['general_app_experience'])
                    <div class="col-md-6 col-12 mb-3 mb-md-0">
                        <h5 class="experience-content">{!! $core['general_app_experience'] !!}</h5>
                    </div>
                    @endif
                    <div class="d-flex justify-content-center justify-content-md-start">
                        <div class="px-2 mb-2 mb-md-0">
                            <a href="https://play.google.com/store/apps/details?id=com.bestcast.bestcaststudios">
                                <img class="img-fluid logo-light" src="{{ Lib::publicUrl($core['android_logo']) }}" alt="Android Logo" style="max-height: 80px;">
                            </a>
                        </div>
                        <div class="px-2">
                            <a href="https://apps.apple.com/in/app/bestcast-ott/id6502750053">
                                <img class="img-fluid logo-light" src="{{ Lib::publicUrl($core['ios_logo']) }}" alt="iOS Logo" style="max-height: 80px;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
      <div class="copyright-area copyright-default">
          <div class="container">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="inner text-center">
                          @if($core['general_footer_copywrite'])
                              <p>{!! $core['general_footer_copywrite'] !!} </p>
                          @endif
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </footer>
    <!-- End Footer Area  -->
@endif
{!! Lib::cachegenerate(); !!}
@if(!empty($core['cookie_consent_enable']))
<div class="cookie_consent">
    <div class="container">
        {!! Lib::sh($core['cookie_consent_content']) !!}
        <button class="btn">{{ empty($core['cookie_consent_button'])?OK:$core['cookie_consent_button'] }}</button>
    </div>
</div>
@endif
