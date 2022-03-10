<div class="sidebar-header dashboard-sidebars">
    <div class="side-manu-header">
        <div class="side-logo">
            <a href="#">
                <img src="{{asset('public/assets/images/full-logo.png')}}"/>
            </a>
        </div>
        <div class="close-sidemanu">
            <img src="{{asset('public/assets/images/close-manu.svg')}}"/>
        </div>
    </div>
    @if(isset($user) && $user->name != 'Guest User')
        <div class="user-menu mobile-users">
            <div class="user-name">
                <div class="user-imgae" id="userHeadImage" style="background-image: url({{asset('public/svg/'.$user->avatar)}});"></div>
                <h5>{{$user->name}}</h5>
                <!-- <img src="{{asset('public/assets/images/down-arrow.png')}}"/> -->
            </div>

        </div>
    @endif
    <ul class="roadside-manu">
        <li>
            <a href="{{route('rescue')}}">
                <img src="{{asset('public/assets/images/Get assistance.svg')}}" class="mr-2"/>
                Get Roadside Assistance
            </a>
        </li>
        <li>
            <a href="https://www.uber.com/jp/en/ride/ubertaxi/" target="blank">
                <img src="{{asset('public/assets/images/get ride.svg')}}" class="mr-2"/>
                Order Ride
            </a>
        </li>
        <li>
            <a href="#" onclick="getLocationlat()">
                <img src="{{asset('public/assets/images/GAS.svg')}}" class="mr-2"/>
                Gas Prices
            </a>
        </li>
        <li>
            <a href="{{route('subsecriptions')}}">
                <img src="{{asset('public/assets/images/PRICING.svg')}}" class="mr-2"/>
                Subscriptions
            </a>
        </li>
        @if(isset($user) && $user->name != 'Guest User')
            <div class="mobile-manu">
                <li>
                    <a href="{{route('editprofile',$user->id)}}">
                        <img src="{{asset('public/assets/images/USER.svg')}}" class="mr-2"/>Edit Profile
                    </a>
                </li>
                <li>
                    <a href="{{route('changepassword')}}">
                        <img src="{{asset('public/assets/images/PASSWORD.svg')}}" class="mr-2"/>Change Password
                    </a>
                </li>
                <li>
                    <a href="{{route('paymentinfo')}}">
                        <img src="{{asset('public/assets/images/Pyment.svg')}}" class="mr-2"/>Payment Info
                    </a>
                </li>
                <li>
                    <a href="{{route('udashboard')}}">
                        <img src="{{asset('public/assets/images/cars.svg')}}" class="mr-2"/>My Cars
                    </a>
                </li>
                <li>
                    <a href="{{route('userlogout')}}">
                        <img src="{{asset('public/assets/images/logout.svg')}}" class="mr-2"/>Logout
                    </a>
                </li>
            </div>
        @endif
    </ul>

</div>
<script>
    function getLocationlat() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {

        window.location = '{{ URL::asset('gasprices') }}'+"/"+position.coords.latitude+"/"+position.coords.longitude

    }

</script>
