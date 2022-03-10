@include('layouts/head')
<body>
@if($user->name == 'Guest User')
    @include('layouts/guestuserheader')
@else
    @include('layouts/main_header')
@endif


<main class="page-wrap parent-main">
    <section class="account-area step1-section mobile-step1section">
        <div class="container">
            <div class="account-area-content">
                <span><img src="{{asset('public/assets/images/clock.svg')}}" alt="icon"></span>
                <span>GET ROADSIDE ASSISTANCE FAST</span>
                <span>{{$location}} ROADSIDE ASSISTANCE</span>
                <h2>Select Service to</h2>
                <h3>Get Started</h3>
                <div class="down-arrows">
                    <img src="{{asset('public/assets/images/down-arrow.svg')}}"/>
                </div>
            </div>
        </div>
    </section>
    <section class="service-section">
        <div class="container">
            <div class="service-row">
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'battery'])}}" class="wait"><div class="service-repeater">
                            <div class="service-img">
                                <img src="{{asset('public/assets/images/icon1.svg')}}"/>
                            </div>
                            <h4>Battery</h4>
                        </div></a>
                </div>
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'tire'])}}" class="wait"> <div class="service-repeater">
                            <div class="service-img">
                                <img src="{{asset('public/assets/images/icon2.svg')}}"/>
                            </div>
                            <h4>Tire</h4>
                        </div></a>
                </div>
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'tow'])}}" class="wait"> <div class="service-repeater">
                            <div class="service-img">
                                <img src="{{asset('public/assets/images/icon3.svg')}}"/>
                            </div>
                            <h4>Tow</h4>
                        </div></a>
                </div>
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'lockout'])}}" class="wait"> <div class="service-repeater">
                            <div class="service-img">
                                <img src="{{asset('public/assets/images/icon4.svg')}}"/>
                            </div>
                            <h4>Lockout</h4>
                        </div></a>
                </div>
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'fuel'])}}"class="wait" ><div class="service-repeater">
                            <div class="service-img">
                                <img src="{{asset('public/assets/images/icon5.svg')}}"/>
                            </div>
                            <h4>Fuel</h4>
                        </div></a>
                </div>
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'winch'])}}" class="wait">   <div class="service-repeater">
                            <div class="service-img">
                                <img src="{{asset('public/assets/images/icon6.svg')}}"/>
                            </div>
                            <h4>Winch</h4>
                        </div></a>
                </div>
            </div>
            <div class="rodaside-content">
                <p>
                    Request <b>{{$location}} Roadside assistance</b> in 30 seconds or less.
                    Our <b>24/7 roadside assistance in {{$location}}</b> can help you with car towing, battery
                    replacement, flat tires, vehicle lockout, fuel issues and winching.
                    Have you ever been stuck in a {{$location}} street at a busy time? Or did your vehicle
                    ever stopped running on the side of the freeway? Issues can happen and you
                    might need help with a <b>flat tire repair</b>, a friendly jump start, or refueling due to the
                    fact that you are out of gas.
                    Or you might need a <b>vehicle tow</b> to get your repairs taken care of. Our{{$location}}
                    <b>roadside assistance</b> can be requested in 30 seconds or less and at a fraction of
                    the price.
                </p>
                <p>
                    DriveRoadside.com has helped thousands of drivers in {{$location}}. We have years of
                    experience helping distressed drivers and our team is available 24/7 to assist you. Our
                    drivers are certified and insured and can deal with any sort of issues, from a simple
                    battery issue to more complex, computerized, diagnostics.
                    Our <b>{{$location}} roadside assistance</b> services include but are not limited to:
                </p>
                <ul>
                    <li> Change tire / flat tire assistance</li>
                    <li> Run out of gas help</li>
                    <li> Tow trucking</li>
                    <li> Emergency roadside assistance</li>
                    <li> Car unlock / car locksmith</li>
                    <li> Battery replacement / battery jumpstart</li>
                    <li> Ignition replacement</li>
                </ul>
                <p>
                    Our {{$location}} roadside assistance services are available 7 days a week, 24 hours
                    a day. Getting help requires very little effort and approximately 30 seconds. Just
                    select the type of service you need, share your location with us and help will be on
                    the way.
                    You will be able to track our dispatch in real time and contact us with any update
                    you might have.
                    Take full advantage of our network of over 35,000 roadside providers. Using our
                    service won’t have any impact on your insurance rates, giving you one more
                    reason to choose driveroadside.com
                </p>
            </div>
         </div>
    </section>
    <div class="preloader preloader-imgs" id="preloader" style="display:none; z-index: 99999;">
        <img src="{{asset('/public/assets/images/preloader.gif')}}" style="width:10%;"/>
        <p>Searching for the closest roadside provider...</p>
    </div>
</main>

@include('layouts/main_sidebar')
@include('layouts/footer')
<script>
    $(".wait").click(function(e){
        e.preventDefault();
        document.getElementById("preloader").style.display = "flex";

        if (this.href) {
            var target = this.href;
            setTimeout(function(){
                window.location = target;
            }, 3000);
        }
    });
</script>
</body>
</html>
