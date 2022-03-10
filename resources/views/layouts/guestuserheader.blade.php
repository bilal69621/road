<header >
<div class="header-content ">

        @if($user != NULL)
            <div class="login-column1">
                <div class="site-logo" >
                    <a href="{{route('rescue')}}"><img src="{{asset('public/assets/images/main-logo.png')}}" alt="logo"></a>
                </div>
            </div>
    <!-- comment -->
                <div class="login-column2">
                <div class="user-menu guest-user">
                    <div class="user-name">
                        <h5>{{$user->name}}</h5>
                        <img src="{{asset('public/assets/images/down-arrow.png')}}"/>
                    </div>
                    <ul class="sub-dropdwon-manu">

                        <li>
                            <a href="{{route('userlogout')}}">
                                <img src="{{asset('public/assets/images/logout_icon.svg')}}" class="mr-2"/>Logout
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        @else
        <div class="site-logo" >
            <a href="{{route('rescue')}}"><img src="{{asset('public/assets/images/main-logo.png')}}" alt="logo"></a>
        </div>
    @endif

    </div>
</header>
<div class="empty-header"></div>
