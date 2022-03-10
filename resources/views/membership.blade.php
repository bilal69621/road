@extends('layouts.app')

@section('title') MEMBERSHIP | DRIVE | Roadside Assistance Plans @endsection

@section('head')
@endsection


@section('header')
@endsection

@section('content')

    <!-- Membership Billing -->
    <section class="billing-options" id="membership">
        <div class="container">
            <div class="billing-content">
                <h2>Membership bILLING options</h2>
                <div class="flex">
                   <div class="billing-option option1">
                        <!-- <div class="billing-images flex">
                            <img src="{{ config('app.asset_template') }}images/stars.png" alt="">
                            <img src="{{ config('app.asset_template') }}images/ride-option.png" alt="" class="logos-imgs">
                        </div> -->
                        <h5 class="membership-title">Monthly membership</h5>
                            <h2 class="membership-price"><span>$</span>9.99</h2>
                            <span class="autopay-text">AUTOPAY EVERY MONTH</span>
                            <p class="membership-para">Monthly single membership $29 for the first month and $9.99 for every month after that.</p>
                            <h6 class="roadside-event">4 Roadside Events</h6>
                        <h6 class="service-title">Services</h6>
                        <ul class="serives-list">
                            <li>Jumpstart</li>
                            <li>Locksmith</li>
                            <li>Tire Change</li>
                            <li>Fuel Delivery</li>
                            <li>Towing</li>
                        </ul>
                        @if(Auth::check())
                            <a href="{{ route('user.addSubscription').'?plan=10MilesMonthlyPlan' }}">Join now</a>
                        @else
                            @if($ref)
                            <a href="{{ route('register_p').$ref.'&plan=10MilesMonthlyPlan' }}">Join now</a>
                            @else
                            <a href="{{ route('register_p').'?plan=10MilesMonthlyPlan' }}">Join now</a>
                            @endif
                        @endif
                    </div>
                  <!-- option2 -->
                    <div class="billing-option option2">
                         <!--<div class="billing-images flex">
                            <img src="{{ config('app.asset_template') }}images/stars.png" alt="">
                            <img src="{{ config('app.asset_template') }}images/ride-option.png" alt="" class="logos-imgs">
                        </div> -->
                        <h5 class="membership-title">6 MONTHS MEMBERSHIP</h5>
                        <h2 class="membership-price"><span>$</span>59.99</h2>
                        <span class="autopay-text">AUTOPAY EVERY 6 MONTHS</span>
                        <p class="membership-para"></p>
                        <h6 class="roadside-event">2 Roadside Events</h6>
                        <h6 class="service-title">Services</h6>
                        <ul class="serives-list">
                            <li>Jumpstart</li>
                            <li>Locksmith</li>
                            <li>Tire Change</li>
                            <li>Fuel Delivery</li>
                            <li>Towing</li>
                        </ul>
                        @if(Auth::check())
                            <a href="{{ route('user.addSubscription').'?plan=10MilesYear' }}">Join now</a>
                        @else
                            @if($ref)
                                <a href="{{ route('register_p').$ref.'&plan=10MilesYear' }}">Join now</a>
                            @else
                                <a href="{{ route('register_p').'?plan=10MilesYear' }}">Join now</a>
                            @endif

                        @endif

                    </div>
                    <!-- option3 -->
                    <div class="billing-option option3">
                        <!-- <div class="billing-images flex">
                            <img src="{{ config('app.asset_template') }}images/stars.png" alt="">
                            <img src="{{ config('app.asset_template') }}images/ride-option.png" alt="" class="logos-imgs">
                        </div> -->
                        <h5 class="membership-title">1 YEAR MEMBERSHIP</h5>
                        <h2 class="membership-price"><span>$</span>99.99</h2>
                        <span class="autopay-text">AUTOPAY EVERY YEAR</span>
                            <p class="membership-para"></p>
                            <h6 class="roadside-event">4 Roadside Events</h6>
                        <h6 class="service-title">Services</h6>
                        <ul class="serives-list">
                            <li>Jumpstart</li>
                            <li>Locksmith</li>
                            <li>Tire Change</li>
                            <li>Fuel Delivery</li>
                            <li>Towing</li>
                        </ul>
                        @if(Auth::check())
                            <a href="{{ route('user.addSubscription').'?plan=10MilesPlusYear' }}">Join now</a>
                        @else
                            @if($ref)
                                <a href="{{ route('register_p').$ref.'&plan=10MilesPlusYear' }}">Join now</a>
                            @else
                            <a href="{{ route('register_p').'?plan=10MilesPlusYear' }}">Join now</a>
                            @endif
                        @endif
                    </div>
                    <!-- option4 -->
                    <div class="billing-option option4">
                        <!-- <div class="billing-images flex">
                            <img src="{{ config('app.asset_template') }}images/stars.png" alt="">
                            <img src="{{ config('app.asset_template') }}images/ride-option.png" alt="" class="logos-imgs">
                        </div> -->
                        <h5 class="membership-title">FAMILY PLAN</h5>
                        <h2 class="membership-price"><span>$</span>149 .99</h2>
                        <span class="autopay-text">AUTOPAY EVERY YEAR</span>
                        <p class="membership-para"></p>
                        <h6 class="roadside-event">4 Roadside Events</h6>
                        <h6 class="service-title">Services</h6>
                        <ul class="serives-list">
                            <li>Jumpstart</li>
                            <li>Locksmith</li>
                            <li>Tire Change</li>
                            <li>Fuel Delivery</li>
                            <li>Towing</li>
                        </ul>
                        @if(Auth::check())
                            <a href="{{ route('user.addSubscription').'?plan=10MilesPlusYearFamilyPlan' }}">Join now</a>
                        @else
                            @if($ref)
                                <a href="{{ route('register_p').$ref.'&plan=10MilesPlusYearFamilyPlan' }}">Join now</a>
                            @else
                            <a href="{{ route('register_p').'?plan=10MilesPlusYearFamilyPlan' }}">Join now</a>
                            @endif
                        @endif
                    </div>
                    <!-- option ends -->
                    <!-- option5 -->
                    <!-- <div class="billing-option option5 dropshadow">
                        <div class="billing-images flex">
                            <img src="{{ config('app.asset_template') }}images/stars.png" alt="">
                            <img src="{{ config('app.asset_template') }}images/ride-option.png" alt="" class="logos-imgs">
                        </div>
                        <h3>$9 <span>.99 aUTOPAY every month</span> <span>$29.99 at Sign Up</span></h3>
                        <h5>monthly membership</h5>
                        @if(Auth::check())
                            <a href="{{ route('user.addSubscription').'?plan=10MilesMonthlyPlan' }}">Join now</a>
                        @else
                            @if($ref)
                                <a href="{{ route('register_p').$ref.'&plan=10MilesMonthlyPlan' }}">Join now</a>
                            @else
                                <a href="{{ route('register_p').'?plan=10MilesMonthlyPlan' }}">Join now</a>
                            @endif
                        @endif
                    </div> -->
                    <!-- option ends -->
                </div>
                <br>
                <h4 style="text-align: center;">Need immediate roadside assistance? </h4>
                 <a href="{{route('help')}}" class="call-now-btn">Get help</a>
            </div>
        </div>
    </section>
    <!-- Membership Essentials -->
    <!-- <section class="membership-essentials">
        <div class="membership-boxes dropshadow">
            <div class="membership-box box1">
                <div class="container">
                    <img src="{{ config('app.asset_template') }}images/umbrella.png" alt="">
                    <h4>Membership Coverage</h4>
                    <p>Use our app to quickly and easily request help 24/7 </p>
                    <p>Membership Covers you in any car you're in</p>
                    <p>Up to $150 per event</p>
                    <p>Up to 4 events per year </p>
                    <p>Towing service </p>
                    <p>Tire changes</p>
                    <p>fuel delivery service</p>
                    <p>Battery jumping service</p>
                    <p>Locksmith services</p>
                    <p>Usage won't increase car insurance rates</p>
                </div>
            </div>
            <div class="membership-box box2">
                <div class="container">
                    <img src="{{ config('app.asset_template') }}images/plus.png" alt="">
                    <h4>Additional Features​</h4>
                    <p>Set car & insurance payment reminders</p>
                    <p>Mark where you park on live map</p>
                    <p>Order ​a ride</p>
                    <p>Compare local mechanics</p>
                    <p>Rent a car</p>
                    <p>Compare local gas prices</p>
                    <p>Share location and safety status </p>
                </div>
            </div>
        </div>
    </section> -->
    <section class="coverage-comparison">
        <div class="container">
            <div class="comparison-row">
                <div class="comparison-title">
                    <div class="title-img">
                        <img src="{{ config('app.asset_template') }}images/umbrella1.png" alt="">
                    </div>
                    <h3>Coverage Comparison</h3>
                </div>
            </div>
            <div class="covarage-repeater">
                <div class="covarage-head">
                    <div class="covarage-column1">
                    </div>
                    <div class="covarage-column2">
                        <div class="covarage-inner-row">
                            <div class="covarage-inner-column">
                                <h3><span>DRIVE</span>| ROADSIDE</h3>
                            </div>
                            <div class="covarage-inner-column blue-column">
                                <h3>INSURANCE COMPANY</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="covarage-body">
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>4 Service Calls Per Year</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>10 Miles of Towing </p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>Fuel Delivery</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>Tire, Jumpstart & Lockout Services</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>2 Gallons of Free Fuel</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/close_icons.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>Lockout Service- 100% Covered</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/close_icons.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>App for Faster Help & Rescue Truck Tracking</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/close_icons.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>Instantly Request Help & Share Your Location </p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/close_icons.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>Usage Won’t Affect Insurance Ratesd</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/close_icons.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">
                            <p>Usage Won’t Affect Insurance Rates</p>
                        </div>
                        <div class="covarage-column2">
                            <div class="covarage-inner-row">
                                <div class="covarage-inner-column">
                                    <img src="{{ config('app.asset_template') }}images/check_icon.png" alt="">
                                </div>
                                <div class="covarage-inner-column blue-column">
                                    <img src="{{ config('app.asset_template') }}images/close_icons.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="covarage-head">
                        <div class="covarage-column1">

                        </div>
                        <div class="covarage-column2">
                            <h6>Coverage takes 48 hours to go into effect</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="road-network" style="background-image:url('{{ config('app.asset_template') }}images/network_bg.png')">
        <div class="container">
            <div class="network-title text-center">
                <h3>Network</h3>
                <h5><span>Over 35,000</span> Roadside Providers ready to help</h5>
            </div>
            <div class="network_img">
                <img src="{{ config('app.asset_template') }}images/map.png"/>
            </div>
        </div>
    </section>
    <section class="request_help" style="background-image:url('{{ config('app.asset_template') }}images/request_bg.png')">
        <div class="container">
            <div class="requests-title text-center">
                <h3>GET HELP FAST</h3>
            </div>
            <div class="request_rows">
                <div class="request_column">
                    <div class="request_repeater_row">
                        <div class="request_imag">
                            <img src="{{ config('app.asset_template') }}images/request1.png"/>
                        </div>
                        <div class="request_content">
                            <h5>
                                Request Help Faster Track Rescue Truck Using APP
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
    
    <!-- How it works -->
    <!-- <section class="works-section">
        <div class="container">
            <div class="works-content">
                <h2>how it works</h2>
                <a data-fancybox="" href="{{ config('app.asset_template') }}video/banner-video.mp4" class="video_icon_bg">
                    <img src="{{ config('app.asset_template') }}images/works.PNG" alt="">
                </a>
            </div>
        </div>
    </section> -->
    <!-- Network Section -->

@endsection

@section('footer')
@endsection

@section('js')
@endsection
