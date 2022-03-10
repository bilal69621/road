<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    @isset($description)
    <meta charset="UTF-8" name="description" content={{$description}}>
    @endisset
    <link rel="icon" href="{{ config('app.asset_template') }}images/Fav.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    <link rel="stylesheet" href="{{ config('app.asset_template') }}css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('app.asset_template') }}css/all.css">
    @yield('head')

<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-155840371-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-155840371-1');
        gtag('config', 'AW-676743917');
    </script>
    <script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"137021812"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>

<!-- Start of ChatBot (www.chatbot.com) code -->
<script type="text/javascript">
    window.__be = window.__be || {};
    window.__be.id = "6042e313ae8f390007d5985c";
    (function() {
        var be = document.createElement('script'); be.type = 'text/javascript'; be.async = true;
        be.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.chatbot.com/widget/plugin.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(be, s);
    })();
</script>
<!-- End of ChatBot code -->
</head>
<body>
<header class="header">
    <nav class="manubar-nav">
        <ul class="site-nav">
            <li class="{{ (Route::currentRouteName() == 'home') ? 'active' : '' }}"><a href="{{ route('home'). $ref }}">HOME</a></li>
            <li class="{{ (Route::currentRouteName() == 'about') ? 'active' : '' }}"><a href="{{ route('about'). $ref }}">ABOUT US</a></li>
            <li class="{{ (Route::currentRouteName() == 'membership') ? 'active' : '' }}"><a href="{{ route('membership'). $ref.'#membership' }}" >MEMBERSHIP</a></li>
            <li class="{{ (Route::currentRouteName() == 'partnership') ? 'active' : '' }}"><a target="_blank" href="https://www.roadsidemembership.com/signup">PARTNERSHIP</a></li>
            <li class="sub-manus">
                <a href="javascript:void(0)">Services <i class="fa fa-caret-down ml-2" aria-hidden="true"></i></a>
                <ul class="subdropdown">
                    <li>
                        <a href="{{url('service')}}/tire">Tire change</a>
                    </li>
                    <li>
                        <a href="{{url('service')}}/battery">Battery jump</a>
                    </li>
                    <li>
                        <a href="{{url('service')}}/tow">Tow truck</a>
                    </li>
                    <li>
                        <a href="{{url('service')}}/lockout">Locksmith</a>
                    </li>
                    <li>
                        <a href="{{url('service')}}/fuel">Fuel delivery</a>
                    </li>
                    <li>
                        <a href="{{url('service')}}/winch">Winch</a>
                    </li>
                    <li>
                        <a href="{{url('service')}}/emergency-roadside-assistance">Emergency roadside assistance</a>
                    </li>
                </ul>
             <!--    <select onchange="serviceRedirct(this.value)">
                    <option>Select Service</option>
                    <option  value="{{url('service')}}/tire">Flat Tire</option>
                    <option value="{{url('service')}}/battery">Car Battery</option>
                    <option value="{{url('service')}}/tow">Tow trucking</option>
                    <option value="{{url('service')}}/lockout">Car Unlock</option>
                    <option value="{{url('service')}}/fuel">Out of Gas</option>
                    <option value="{{url('service')}}/winch">Winch</option>
                </select> -->
            </li>
<!--- {{ route('partnership'). $ref }} --->
        </ul>

        @if(Auth::check())
            <div class="user-profile flex">
                {{--<div class="user-dp"><img src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80" alt=""></div>--}}
                <?php

                $img = Auth::user()->avatar;
                if($img != null){
                    $img = asset('public/svg/'.Auth::user()->avatar );
                }else{
                    $img = asset('public/images/default/user_icon.png');
                }
                ?>
                <div class="user-dp"><img src="{{$img}}" alt=""></div>
                <div class="user-name"><p>{{Auth::user()->name}}</p></div>
                <div class="profile-menu">
                    <ul>
                        <li><a href="{{route('user.dashboard')}}">Dashboard</a></li>
                        <li><a href="{{route('user.logout')}}">Logout</a></li>
                    </ul>
                </div>
            </div>
        @else
            <div class="site-logout">
                <a href="{{route('user.login').$ref}}">Login</a>

{{--                <a href="https://www.roadsidemembership.com/login">Partner Login</a>--}}
                <a href="{{ url('/news/all') }}">Blog</a>
            </div>
        @endif
        <div class="mobile-toggler">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>

<!-- Banner Section -->
<section class="banner-section">
    <video width="100%" height="100%" autoplay="" muted="" loop="" playsinline>
        <source src="{{ config('app.asset_template') }}video/banner-video.mp4" type="video/mp4">
    </video>
    <div class="banner-overlay">
        <div class="container">
            <div class="banner-content">
                <img src="{{ config('app.asset_template') }}images/drive.png" alt="" height="120" width="120">
                <h2>DRIVE </h2>
                <h3>Keeping You Moving Forward.</h3>
                <div class="store-images">
                    <!--https://apps.apple.com/us/app/drive-roadside-assistance/id1487510381-->

                    <a href="https://apps.apple.com/us/app/roadside-assistance/id1483339220?ls=1"><img src="{{ config('app.asset_template') }}images/app-store.png" alt=""></a>
                    <?php
                    if(!(isset($_GET['device']) && $_GET['device'] == 'ios')){
                    ?>
                    <a href="https://play.google.com/store/apps/details?id=com.drive.roadside"><img src="{{ config('app.asset_template') }}images/play-store.png" alt=""></a>
                    <?php
                    }
                    ?>
                </div>
                <div class="join-now">
                    <a href="{{route('help')}}">Get Help</a>
{{--                    <a href="{{route('membership')}}#membership">Join Now</a>--}}
                </div>
            </div>
        </div>
    </div>
</section>

@yield('header')

@yield('content')

<!-- Stores -->
<!-- <section class="stores-section">
    <div class="container">
        <div class="store-content flex">
            <a href="https://apps.apple.com/us/app/roadside-assistance/id1483339220?ls=1"><img src="{{ config('app.asset_template') }}images/app-store.png" alt=""></a>
            <a href="https://play.google.com/store/apps/details?id=com.drive.roadside"><img src="{{ config('app.asset_template') }}images/play-store.png" alt=""></a>
        </div>
    </div>
</section> -->

<footer>
    <div class="container">
        <div class="works-content">
                 <h2 style="margin-bottom: 20px; margin-top: 20px; text-align: center;color: #606060;
    font-weight: bold;">COMPANY SERVICES</h2>
            </div>
        <div class="footer-content flex sb">
            <div class="footer-col comp">
                <ul>
                    <li><a href="{{ route('about'). $ref }}">About Us</a></li>
                    <li><a href="{{route('careers')}}">Careers</a></li>
                    <li><a href="{{route('contactus')}}">  Contact Us</a></li>
                    <li><a href="{{ route('membership'). $ref }}">Membership Plans</a></li>
                    <li><a href="{{ url('news/all'). $ref }}">Blog</a></li>
                </ul>
            </div>
            <div class="footer-col serv">
                <ul>
                    <li><a href="{{ route('help'). $ref }}">Towing</a></li>
                    <li><a href="{{ route('help'). $ref }}">Tire Change</a></li>
                    <li><a href="{{ route('help'). $ref }}">Jump Start</a></li>
                    <li><a href="{{ route('help'). $ref }}">Lockout</a></li>
                    <li><a href="{{ route('help'). $ref }}">Out of Fuel</a></li>
                </ul>
            </div>
<!--             <div class="footer-col drive">
                <img src="{{ config('app.asset_template') }}images/drive.png" alt="">
                <h5>DRIVE</h5>
                <a href="#">info@driveroadside.com</a>
            </div> -->
        </div>
        <div class="footer-bottom flex">
            <p>© {{ now()->year }} DRIVE | Roadside, All rights reserved.</p><a href="{{route('terms')}}">Terms & Conditions</a><span>|</span><a href="{{route('privacy')}}">Privacy Policy</a>
        </div>
    </div>
</footer>

@yield('footer')

</body>
<script src="{{ config('app.asset_template') }}js/jquery-3.1.1.js"></script>
<script src="{{ config('app.asset_template') }}js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="{{ config('app.asset_template') }}js/custom.js"></script>
<script>
    $('.sub-manus').click(function(){
        $('.subdropdown').slideToggle();
    });
    var mobilevideo = document.getElementsByTagName("video")[0];
    mobilevideo.setAttribute("playsinline", "");
    mobilevideo.setAttribute("muted", "");

    function serviceRedirct($value)
    {
        window.location.href = $value;
    }
</script>
@yield('js')

</html>
