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
                <span>GET HELP FAST</span>
                <h2>Select Service to</h2>
                <h3>Get Started
                    <!-- <div class="down-arrows mobile-down-arrows">
                        <img src="{{asset('public/assets/images/down-arrow.svg')}}"/>
                    </div> -->
                </h3>
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

