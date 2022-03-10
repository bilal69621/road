<header class="login-manus">
    <div class="header-content">
                    <div class="login-column">
                <div class="manu-btn1 main-mobile-btns1">
                    <span class="sr-onlys"></span>
                    <span class="sr-onlys"></span>
                    <span class="sr-onlys"></span>
                </div>
            </div>
        <div class="login-column1">
            <div class="site-logo">
                <a href="{{route('rescue')}}"><img src="{{asset('public/assets/images/main-logo.png')}}" alt="logo"></a>
            </div>
        </div>
        <div class="login-column2">
            @if(isset($user) && $user->name != 'Guest User')
            <div class="user-menu">
                <div class="user-name">
                    <div class="user-imgae" id="userHeadImage" style="background-image: url({{asset('public/svg/'.$user->avatar)}});"></div>
                    <h5>{{$user->name}}</h5>
                    <img src="{{asset('public/assets/images/down-arrow.png')}}"/>
                </div>
                <ul class="sub-dropdwon-manu">
                    <li>
                        <a href="{{route('editprofile',$user->id)}}">
                            <img src="{{asset('public/assets/images/profile_icons.svg')}}" class="mr-2"/>Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{route('changepassword')}}">
                            <img src="{{asset('public/assets/images/change_icons.svg')}}" class="mr-2"/>Change Password
                        </a>
                    </li>
                    <li>
                        <a href="{{route('paymentinfo')}}">
                            <img src="{{asset('public/assets/images/payment_icon.svg')}}" class="mr-2"/>Payment Info
                        </a>
                    </li>
                    <li>
                        <a href="{{route('udashboard')}}">
                            <img src="{{asset('public/assets/images/car-icons.svg')}}" class="mr-2"/>My Cars
                        </a>
                    </li>
                    <li>
                        <a href="{{route('userlogout')}}">
                            <img src="{{asset('public/assets/images/logout_icon.svg')}}" class="mr-2"/>Logout
                        </a>
                    </li>
                </ul>
            </div>
            @endif

        </div>
    </div>
    <div class="guest-empty-header"></div>
</header>
