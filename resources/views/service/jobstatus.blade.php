@include('layouts/head')
<style>
    #myMap{
        width: 100%;
        height: 400px;
    }
</style>
<body>

    @if($user->name == 'Guest User')
    @include('layouts/guestuserheader')
    @else
    @include('layouts/main_header')
    @endif

<main class="page-wrap parent-main" id="singleViewPage">
       <section class="map-address">
    <div id="map" style="height:500px"></div>
    <div class="dispatch text-center">
        <div class="dispatch-tolltip" id="newdetailnote">
            <img src="{{asset('public/assets/images/close-manu.svg')}}" onclick="$('#newdetailnote').hide()"/>
            <p id="statusMessage" >Your rescue is in progress and we are dispatching the closest available rescue truck. We appreciate your patience, as this process can take several minutes. You can also call <a href="tel:1 (800) 513-1745" style="text-decoration:underline;">1 (800) 513-1745</a> for status updates.</p>
        </div>
        <h5 id="jobstatuslive">DISPATCHING</h5>
    </div>
    </section>
     <section class="driver-lists">
            <div class="container">
{{--                @dd($lat)--}}
                <input type="hidden" name="lat" id="userlat" value="{{$lat}}">
                <input type="hidden" name="lng" id="userlng" value="{{$lng}}">
                <input type="hidden" name="job_id" id="job_id" value="{{$job_id}}">
                <div class="driver-rows">
                    <div class="driver-repeater">
                        <div class="driver-body">
                            <div class="driver-column">
                                @if($type == '1')
                                <img src="{{asset('public/assets/images/icon1.svg')}}"/>
                                <h5>Battery</h5>
                                @elseif($type == '2')
                                    <img src="{{asset('public/assets/images/icon2.svg')}}"/>
                                    <h5>Tire</h5>
                                @elseif($type== '3')
                                    <img src="{{asset('public/assets/images/icon3.svg')}}"/>
                                    <h5>Tow</h5>
                                @elseif($type== '4')
                                    <img src="{{asset('public/assets/images/icon4.svg')}}"/>
                                    <h5>Lockout</h5>
                                @elseif($type== '5')
                                    <img src="{{asset('public/assets/images/icon6.svg')}}"/>
                                    <h5>Winch</h5>
                                @else
                                    <img src="{{asset('public/assets/images/icon5.svg')}}"/>
                                    <h5>Fuel</h5>
                                    @endif
                            </div>
                            <div class="driver-column1">
                                <div class="driver-header">
                                    <h4>Driver</h4>
                                    <span id="estimatedTime">ETA:45 Min</span>
                                </div>
                                <div class="driver-detail">
                                    <div class="driver-detail-column">
                                        <div class="driver-image" style="background-image: url({{asset('public/assets/images/profile1.png')}});"></div>
                                        <div class="drive-content">
                                            @if($driver->name == NULL)
                                            <!-- <h3>Not assign yet</h3> -->
                                            @else
                                            <h3>{{$driver->name}}</h3>
                                            <!-- <p>Not assign yet</p> -->
                                            @endif
                                        </div>
                                    </div>

                                    <div class="driver-detail-column">
                                        @if($driver->phone != NULL)
                                        <a href="tel:+{{$driver->phone}}" class="btn btn-red"><img src="{{asset('public/assets/images/phone.png')}}" class="mr-2"/>{{$driver->phone}}</a>
                                        @else
                                        <a href="tel:+18005131745" class="btn btn-red"><img src="{{asset('public/assets/images/phone.png')}}" class="mr-2"/>Call</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="driver-footer">
                            <button id='cancelJobbtn' onclick="cancelServeice('{{$jobId}}')" class="btn btn-cancel">Cancel Service</button>
                            <img src="{{asset('public/assets/images/arrow.png')}}"/>
                        </div>
                    </div>
                </div>
            </div>
         <div class="modal" tabindex="-1" role="dialog" id="cancelService">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="step-form-title text-center">
            <h4>Report Service</h4>
        </div>
        <form action="{{route('cancelJob')}}" method="POST" id="cancelJobForm">
            @csrf
            <input type="hidden" name="jobId" id="jobId">
        <div class="service-dropdown">
            <select name="reason" required="true">
                <option>
                    The service was taking too long.
                </option>
                <option>
                    Someone helped me out.
                </option>
                <option>
                    I used another service.
                </option>
                <option>
                    I no longer need help.
                </option>
                <option>
                    I didn’t mean to request help.
                </option>
                <option>
                    Other
                </option>
            </select>
        </div>
        <div class="other-reasons"  >
            <textarea placeholder="Type Something here…" name="notes" class="special-notes" required="true"></textarea>
        </div>
        <div class="other-reasons custom-radio">
            <button type="button" id="cancelReason" class="btn btn-next next_steps">Submit</button>
        </div>
        </form>
    </div>
  </div>
</div>
        </section>
     <script
      src="https://maps.googleapis.com/maps/api/js?key={{config('app.GOOLE_MAP_KEY')}}&callback=initMap"
      async
    ></script>
    <script type="text/javascript">
window.__be = window.__be || {};
window.__be.id = "6068cdc454057b000768b1c8";
(function() {
var be = document.createElement('script'); be.type = 'text/javascript'; be.async = true;
be.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.chatbot.com/widget/plugin.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(be, s);
})();
</script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
     <script>
        function initMap() {
            // alert($('#userlng').val());
            // initAutocomplete();
            var a_lat = {{$lat}};
            var a_lng =  {{$lng}};
            var center = {lat: a_lat, lng: a_lng};

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 17,
                center: center
            });
            var infowindow = new google.maps.InfoWindow({});
            var  marker1;
                marker1 = new google.maps.Marker({
                position: new google.maps.LatLng(a_lat, a_lng),
                // size : new google.maps.Size(120, 110),
                map: map,
                icon: {
                    url: "{{asset('public/assets/images/pin1.png')}}",
                    scaledSize: new google.maps.Size(30, 40), // scaled size
                    // origin: new google.maps.Point(0,0), // origin
                    // anchor: new google.maps.Point(0, 0), // anchor
                    strokeColor: "blue",
                    scale: 3,

                },
            });

        }
  function cancelServeice($cardId) {
    Swal.fire({
        title: 'Are you sure?',
        // text: "Are you sure you want to cancel? If you would like an update on the status of your roadside service, you can call us at 1 (800) 513-1745. If you decide you still want to cancel your roadside service, you may be charged a $45 service cancellation fee as we may have already dispatch service. (terms and conditions)",
        html:
            'If you would like an update on the status of your roadside service, you can call us at <a style="color: #2778c4; font-weight: 800;" href="tel:1 (800) 513-1745">1 (800) 513-1745</a>. If you decide you still want to cancel your roadside service, you may be charged a $45 service cancellation fee.<a style="font-weight: 800;" href="https://driveroadside.com/terms-of-use" target="_blank">(terms and conditions)</a> ',

        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
//          Swal.fire(
//            'Deleted!',
//            'Your file has been deleted.',
//            'success'
//          )
            $('#jobId').val($cardId);
            $("#cancelService").modal('toggle')
        }
    })
}




// main function for checking status for help swoop
    $('#cancelReason').on('click',function(){
        $('#cancelJobForm').submit();
    });
        //function for getting status for helper swoop after every 5 secs
        var intervalId = window.setInterval(function(){
            var job_id = $('#job_id').val();
            $.ajax({
                type: 'get',
                url: '{{route('statusjobswoop')}}',
                data: {'jobId':job_id},
                success: function (data) {
                    $status = data.alldata.data.job.status;
                    $eta    = data.eta.current;
                   if($eta != null)
                   {
                       $html = 'ETA:'+$eta+'Min';
                    $('#estimatedTime').html($html);
                   }
                    if($status == 'Pending')
                    {
                        $status = 'DISPATCHING';
                        $('#statusMessage').html('Your rescue is in progress. We are dispatching the closest available rescue truck. Please be  patient, as this process can take several minute.You can also call <a href="tel:1 (800) 513-1745" style="text-decoration:underline;">1 (800) 513-1745</a> for status updates.');
                    }else if($status == 'Tow Destination')
                    {
                        $('#statusMessage').html('The rescue truck and your vehicle have arrived at drop off destination.');
                    }else if($status == 'Dropping Off')
                    {
                        $('#statusMessage').html('The rescue truck and your vehicle have arrived at drop off destination.')
                    }else if($status == 'En Route')
                    {
                        $('#statusMessage').html('Your rescue driver is on the way to help.')
                    }else if($status == 'Rescue Accepted')
                    {
                        $('#statusMessage').html('Your rescue request has been accepted by a rescue truck')
                    }else if($status == 'Dispatched')
                    {
                        $('#statusMessage').html(' A rescue truck has been assigned to help.')
                    }else if($status == 'Towing')
                    {
                        $('#statusMessage').html(' Your vehicle is currently being towed.')
                    }else if($status == 'Completed')
                    {
                        $('#statusMessage').html('Your rescue was successfully completed.')
                    }else if($status == 'Cancelled')
                    {
                        $('#statusMessage').html(' You have cancelled rescue request.')
                    }else if($status == 'GOA')
                    {
                        $('#statusMessage').html('You we’re gone when we arrived.')
                        $('#cancelJobbtn').hide();
                    }else if($status == ' OnSite')
                    {
                        $('#statusMessage').html('The rescue truck is onsite.')
                    }
                    $('#jobstatuslive').html($status);

                }
            });
        }, 10000);

    </script>
</main>
@include('layouts/main_sidebar')
@include('layouts/footer')
<script>
$('.dispatch-tolltip img').click(function(){
    $(this).parent('.dispatch-tolltip').hide();
});
</script>
</body>
</html>
