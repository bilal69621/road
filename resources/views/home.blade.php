@extends('layouts.app')

@section('title') DRIVE | Roadside Assistance Plans @endsection

@section('head')
@endsection


@section('header')
@endsection

@section('content')
    <!-- Video Message -->
    <section class="video-message" style="padding: 15px 0;">
        <div class="container">
            <div class="videosms-content">
                <a data-fancybox="" href="https://youtu.be/1HqzyqY1V8c" class="video_icon_bg">
                    <img src="{{ config('app.asset_template') }}images/video-thumb.PNG" alt="">
                    <div class="youtube-video-img">
                        <img src="{{ config('app.asset_template') }}images/play-icon.svg" alt="">
                    </div>
                </a>
                <!-- <img src="{{ config('app.asset_template') }}images/help.png" alt=""> -->
            </div>
        </div>
    </section>
    <!-- Map -->
{{--    <section class="map-image">--}}
{{--        <div class="container">--}}
{{--            <img src="{{ config('app.asset_template') }}images/map.png" alt="">--}}
{{--        </div>--}}
{{--    </section>--}}
   <!--  <section class="road-network" style="background-image:url('{{ config('app.asset_template') }}images/network_bg.png')">
        <div class="container">
            <div class="network-title text-center">
                <h3>Network</h3>
                <h5><span>Over 35,000</span> Roadside Providers ready to help</h5>
            </div>
            <div class="network_img">
                <img src="{{ config('app.asset_template') }}images/map.png"/>
            </div>
        </div>
    </section> -->
    <section class="request_help" style="background-image:url('{{ config('app.asset_template') }}images/request_bg.png')">
        <div class="container">
            <div class="requests-title text-center">
                <h3>REQUEST HELP IN SECONDS</h3>
            </div>
            <div class="request_rows">
                <div class="request_column">
                    <div class="request_repeater_row">
                        <div class="request_imag">
                            <img src="{{ config('app.asset_template') }}images/request1.png"/>
                        </div>
                        <div class="request_content">
                            <h5>
                                REQUEST HELP FASTER & TRACK RESCUE TRUCK USING OUR APP
                            </h5>
                        </div>
                    </div>
                    <div class="request_repeater_row">
                        <div class="request_imag">
                            <img src="{{ config('app.asset_template') }}images/request2.png"/>
                        </div>
                        <div class="request_content">
                            <h5>
                                WE COVER YOU IN ANY CAR
                               
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="request_column1">
                    <div class="request_mobile">
                        <img src="{{ config('app.asset_template') }}images/request_mobile.png"/>
                    </div>
                </div>
                <div class="request_column">
                    <div class="request_repeater_row">
                        <div class="request_imag">
                            <img src="{{ config('app.asset_template') }}images/request3.png"/>
                        </div>
                        <div class="request_content">
                            <h5>
                                24/7 ROADSIDE ASSISTANCE
PHONE SUPPORT
                            </h5>
                        </div>
                    </div>
                    <div class="request_repeater_row">
                        <div class="request_imag">
                            <img src="{{ config('app.asset_template') }}images/request4.png"/>
                        </div>
                        <div class="request_content">
                            <h5>
                                USAGE WON'T INCREASE CAR
INSURANCE RATES
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
     <section class="works-section reviews-section">
        <div class="container">
            <div class="works-content">
                 <h2>Customers Surveys</h2>
                <p>Read what our customers are saying about us</p>
            </div>
            <div class="reviews-slider">
                <div class="reviews-column">
                    <img src="{{ config('app.asset_template') }}images/quote1.png" class="quotation-images"/>
                    <div class="reviews-header">
                        <h3>CUSTOMER RATING</h3>
                        <div class="rating-imgs">
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                        </div>
                        <p>
                            The person that came and assisted myself and my
                            daughter in such an unsafe high traffic area was so
                            nice and helped us so much!! He got us back on the
                            road very quickly and kept us all safe!! We were so
                            scared to try to get out and change the front tire on
                            the driver side on the interstate just around a curve
                            of 3 lanes deep traffic moving 65+ miles per hour!!
                            This service was a lifesaver.
                        </p>
                    </div>
                    <div class="reviews-footer">
                        <div class="reviews-image">
                            <?xml version="1.0" encoding="UTF-8"?>
                            <svg  viewBox="0 0 447 240" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>user testi</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <path d="M157.39,22.6764693 C158.8,22.6064693 160.04,23.2864693 161.27,23.8964693 C154.03,37.9364693 154.07,55.2064693 160.19,69.6364693 C165.04,81.9664693 174.55,92.0464693 185.87,98.7264693 C198.64,105.596469 214.08,108.186469 228.14,104.106469 C249.56,99.0964693 267.14,80.6264693 271.12,58.9964693 C271.44,57.9564693 271.67,55.5964693 273.32,56.1664693 C286.21,58.3964693 298.97,61.3964693 311.9,63.4664693 C356.52,70.7764693 401.49,75.7064693 446.52,79.6864693 C446.54,133.036469 446.52,186.376469 446.53,239.716469 C297.68,239.716469 148.84,239.686469 0,239.736469 C0.16,181.326469 -0.16,122.926469 0.16,64.5264693 C38.74,43.0064693 81.18,27.3264693 125.36,23.0864693 C136.02,22.3664693 146.72,22.2564693 157.39,22.6764693 Z M195.39,41.3964693 C204.36,50.3064693 219.83,51.5864693 229.84,43.6864693 C230.25,43.4964693 230.644444,43.1298026 231.043333,42.7638767 L231.28336,42.5458293 C232.006,41.9004693 232.756,41.3724693 233.65,41.9964693 C244.26,47.9864693 251.92,59.1664693 253.33,71.3064693 C253.78,73.4064693 251.3,74.6564693 249.51,74.4164693 C225.79,74.4264693 202.06,74.4364693 178.34,74.4064693 C176.63,74.6664693 174.45,73.3464693 174.67,71.4464693 C176.16,58.8264693 184.34,47.5064693 195.39,41.3964693 Z M235.29,15.3664693 C238.88,24.9164693 234.44,36.4164693 225.78,41.5964693 C220.71,44.9064693 214.3,45.7964693 208.47,44.3064693 C198.89,41.7864693 191.2,32.3564693 191.68,22.2864693 C191.45,12.6364693 198.57,3.61646927 207.76,0.996469269 C218.86,-2.73353073 231.69,4.43646927 235.29,15.3664693 Z" id="user-testi" fill="#4BB4EB" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <div class="user-details text-center">
                                <h5>Christine</h5>
                                <h6>Colorado Springs, CO</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reviews-column reviews-second-column">
                    <img src="{{ config('app.asset_template') }}images/quote2.png" class="quotation-images"/>
                    <div class="reviews-header">
                        <h3>CUSTOMER RATING</h3>
                        <div class="rating-imgs">
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                        </div>
                        <p>
                            I was in the middle of nowhere and had no clue
                            where I was and I did a quick Google search for
                            help and signed up for services. Someone was to
                            me within 20 minutes and they were super friendly
                            and helpful. I cannot be more grateful for your
                            services!!! What would normally ruin my entire day,
                            was just a 20 minute break on my trip! Thank you!
                        </p>
                    </div>
                    <div class="reviews-footer">
                        <div class="reviews-image">
                            <?xml version="1.0" encoding="UTF-8"?>
                            <svg  viewBox="0 0 447 240" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>user testi</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <path d="M157.39,22.6764693 C158.8,22.6064693 160.04,23.2864693 161.27,23.8964693 C154.03,37.9364693 154.07,55.2064693 160.19,69.6364693 C165.04,81.9664693 174.55,92.0464693 185.87,98.7264693 C198.64,105.596469 214.08,108.186469 228.14,104.106469 C249.56,99.0964693 267.14,80.6264693 271.12,58.9964693 C271.44,57.9564693 271.67,55.5964693 273.32,56.1664693 C286.21,58.3964693 298.97,61.3964693 311.9,63.4664693 C356.52,70.7764693 401.49,75.7064693 446.52,79.6864693 C446.54,133.036469 446.52,186.376469 446.53,239.716469 C297.68,239.716469 148.84,239.686469 0,239.736469 C0.16,181.326469 -0.16,122.926469 0.16,64.5264693 C38.74,43.0064693 81.18,27.3264693 125.36,23.0864693 C136.02,22.3664693 146.72,22.2564693 157.39,22.6764693 Z M195.39,41.3964693 C204.36,50.3064693 219.83,51.5864693 229.84,43.6864693 C230.25,43.4964693 230.644444,43.1298026 231.043333,42.7638767 L231.28336,42.5458293 C232.006,41.9004693 232.756,41.3724693 233.65,41.9964693 C244.26,47.9864693 251.92,59.1664693 253.33,71.3064693 C253.78,73.4064693 251.3,74.6564693 249.51,74.4164693 C225.79,74.4264693 202.06,74.4364693 178.34,74.4064693 C176.63,74.6664693 174.45,73.3464693 174.67,71.4464693 C176.16,58.8264693 184.34,47.5064693 195.39,41.3964693 Z M235.29,15.3664693 C238.88,24.9164693 234.44,36.4164693 225.78,41.5964693 C220.71,44.9064693 214.3,45.7964693 208.47,44.3064693 C198.89,41.7864693 191.2,32.3564693 191.68,22.2864693 C191.45,12.6364693 198.57,3.61646927 207.76,0.996469269 C218.86,-2.73353073 231.69,4.43646927 235.29,15.3664693 Z" id="user-testi" fill="#4BB4EB" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <div class="user-details text-center">
                                <h5>Stefanie</h5>
                                <h6>Ann Arbor, MI</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reviews-column reviews-third-column">
                    <img src="{{ config('app.asset_template') }}images/quote3.png" class="quotation-images"/>
                    <div class="reviews-header">
                        <h3>CUSTOMER RATING</h3>
                        <div class="rating-imgs">
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                            <img src="{{ config('app.asset_template') }}images/fill-star.png"/>
                        </div>
                        <p>
                                It was awesome. Best service I ever had. The
                                driver was on top of the problem and said,
                                (no problem, I got this sir.) Had me back on the
                                road in no time. He checked the tire and put more
                                air in it to be safe. Wow. Awesome. Thank ya'll so
                                much.
                        </p>
                    </div>
                    <div class="reviews-footer">
                        <div class="reviews-image">
                            <?xml version="1.0" encoding="UTF-8"?>
                            <svg  viewBox="0 0 447 240" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>user testi</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <path d="M157.39,22.6764693 C158.8,22.6064693 160.04,23.2864693 161.27,23.8964693 C154.03,37.9364693 154.07,55.2064693 160.19,69.6364693 C165.04,81.9664693 174.55,92.0464693 185.87,98.7264693 C198.64,105.596469 214.08,108.186469 228.14,104.106469 C249.56,99.0964693 267.14,80.6264693 271.12,58.9964693 C271.44,57.9564693 271.67,55.5964693 273.32,56.1664693 C286.21,58.3964693 298.97,61.3964693 311.9,63.4664693 C356.52,70.7764693 401.49,75.7064693 446.52,79.6864693 C446.54,133.036469 446.52,186.376469 446.53,239.716469 C297.68,239.716469 148.84,239.686469 0,239.736469 C0.16,181.326469 -0.16,122.926469 0.16,64.5264693 C38.74,43.0064693 81.18,27.3264693 125.36,23.0864693 C136.02,22.3664693 146.72,22.2564693 157.39,22.6764693 Z M195.39,41.3964693 C204.36,50.3064693 219.83,51.5864693 229.84,43.6864693 C230.25,43.4964693 230.644444,43.1298026 231.043333,42.7638767 L231.28336,42.5458293 C232.006,41.9004693 232.756,41.3724693 233.65,41.9964693 C244.26,47.9864693 251.92,59.1664693 253.33,71.3064693 C253.78,73.4064693 251.3,74.6564693 249.51,74.4164693 C225.79,74.4264693 202.06,74.4364693 178.34,74.4064693 C176.63,74.6664693 174.45,73.3464693 174.67,71.4464693 C176.16,58.8264693 184.34,47.5064693 195.39,41.3964693 Z M235.29,15.3664693 C238.88,24.9164693 234.44,36.4164693 225.78,41.5964693 C220.71,44.9064693 214.3,45.7964693 208.47,44.3064693 C198.89,41.7864693 191.2,32.3564693 191.68,22.2864693 C191.45,12.6364693 198.57,3.61646927 207.76,0.996469269 C218.86,-2.73353073 231.69,4.43646927 235.29,15.3664693 Z" id="user-testi" fill="#4BB4EB" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <div class="user-details text-center">
                                <h5>James</h5>
                                <h6>Richmond Hill, GA</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Network Section -->
    <section class="network-section">
{{--        <div class="network-head">--}}
{{--            <h2>network</h2>--}}
{{--            <h3>11 Million+ <span>Drivers rescued each year</span></h3>--}}
{{--        </div>--}}
       <!--  <div class="network-row">
            <div class="container">
                <div class="flex">
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/network1.png" alt="">
                        <h5>request help faster & track <br> rescue truck using our app</h5>
                    </div>
                    <div class="network-mid">
                        <img src="{{ config('app.asset_template') }}images/network-mid.png" alt="">
                    </div>
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/network2.png" alt="">
                        <h5>24/7 Roadside assistance<br> phone support</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="network-row network-row2">
            <div class="container">
                <div class="flex">
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/car.jpg" alt="">
                        <h5 style="min-height: 44px">we cover you in any car</h5>
                    </div>
                    <div class="network-icon">
                        <img src="{{ config('app.asset_template') }}images/network4.png" alt="">
                        <h5>usage won't increase Car<br> insurance RATES</h5>
                    </div>
                </div>
            </div>
        </div>
        --> 
        <div class="join-now join-now2">
            <a href="#">Join now</a>
        </div>
    </section>

@endsection

@section('footer')
@endsection

@section('js')
@endsection
