@include('layouts/head')
<body>
@include('layouts/header')
<main>
    <div id="map" style="height:500px"></div>
</main>
 <script
      src="https://maps.googleapis.com/maps/api/js?key={{env('GOOLE_MAP_KEY')}}&callback=initMap"
      async
    ></script>
<script>
      function initMap() {
            // initAutocomplete();
            var a_lat = {{$lat}};
            var a_lng =  {{$lng}};
            var center = {lat: a_lat, lng: a_lng};
            console.log(center);
            var locations = [
                @foreach ($gassLocations as $key => $m)
                 [{{$m->lat}},{{$m->lng}}],
                @endforeach
            ];
            console.log(locations);

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: center
            });
            var infowindow = new google.maps.InfoWindow({});
            var marker, count, marker1;
                marker1 = new google.maps.Marker({
                position: new google.maps.LatLng(a_lat, a_lng),
                map: map,
                icon: {
                      url: "{{asset('public/assets/images/pin1.png')}}",
                    scaledSize: new google.maps.Size(30, 40),
                    strokeColor: "blue",
                    scale: 3
                },
            });
      
            for (count = 0; count < locations.length; count++) {
                var icon = {
                    url: "{{asset('public/assets/images/fule.svg')}}", // url
                     scaledSize: new google.maps.Size(30, 40),
                };
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[count][0], locations[count][1]),
                    map: map,
                    icon: icon
                });
                google.maps.event.addListener(marker, 'click', (function (marker, count) {
                    return function () {
                        infowindow.setContent(locations[count][0]);
//                        infowindow.open(map, marker);
                    }
                })(marker, count, marker1));
            }
        }
</script>
@include('layouts/sidebar')
@include('layouts/footer')
</body>
</html>

