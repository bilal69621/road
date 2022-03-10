
@include('layouts/head')
<body>
    @if($user->name == 'Guest User')
    @include('layouts/guestuserheader')
    @else
    @include('layouts/main_header')
    @endif
<main class="page-wrap parent-main">
    <section class="account-area payment-section step1-section step2-payments viewport-height">
        <div class="container">
            <div class="account-area-content">
                <h3>Where is your vehicle located?</h3>
                <div class="find-me">
                    <button class="btn btn-white" onclick="getLocationfind()">
                        Find Me
                    </button>
                    <form action="{{route('step_4')}}" method="post">
                        @csrf
                        <div class="or-img">
                            <img src="{{asset('public/assets/images/or.svg')}}"/>
                        </div>
                        <input type="text" class="address-field" placeholder="Enter Address" id="location"/>
                        <input type="hidden" name="type" value="{{$type}}">
                        <input type="hidden" name="price" value="{{$price}}">
                        <input type="hidden" class="lat" name="lat" value=""/>
                        <input type="hidden" class="lng" name="lng" value=""/>
                        <input type="submit" class="submit_step3" style="display: none;"/>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- @include('main.services') -->
</main>
@include('layouts/main_sidebar')

<script
    src="https://maps.googleapis.com/maps/api/js?key={{env('GOOLE_MAP_KEY')}}&libraries=places&callback=initAutocomplete" async defer></script>
<script>


    var element = document.getElementById('location');
    element.addEventListener('click', function() {
        element.setAttribute('placeholder', '')

    })


    var address1;
    function initAutocomplete() {
        var options = {
            // types: ['(address)'],
//            componentRestrictions: {country: "us"}
        };

        address1 = new google.maps.places.Autocomplete(document.getElementById('location'), options);
        address1.setFields(['address_component', 'geometry']);
        address1.addListener('place_changed', fillInAddress1);
    }
    console.log(address1)
    function fillInAddress1() {
        var place = address1.getPlace();
        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();
        var add =  $('#location').val();
        // var a = add.substring(0, add.indexOf(','));
        // $('#location').val(a);
        $(".lat").val(lat);
        $(".lng").val(lng);
        $('.submit_step3').trigger('click');


    }
</script>

<script>
    function getLocationfind() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition,errorCallback);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {

        $('.lat').val(position.coords.latitude);
        $('.lng').val(position.coords.longitude);

        $('.submit_step3').trigger('click');

    }

    function errorCallback(error)
    {
        if(error.message == "User denied Geolocation")
        {
            var message = "it appears your location settings are disabled. In order to find your location we will need you to change your local location setting. Follow the following steps to enable your location. \n 1. Go to settings \n 2. Select Privacy \n 3. Select Location services \n 4. Scroll down to the Internet browser you are using select and allow location tracking.";
            Swal.fire({
                title: 'warning',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay',
                cancleButtonText:""
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.location.href="https://www.uber.com/jp/en/ride/ubertaxi/";
                }
            })
        }
        console.log(error.message);
    }


</script>
@include('layouts/footer')
</body>
</html>

