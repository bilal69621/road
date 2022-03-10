@include('layouts/head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.1/socket.io.min.js"></script>
<script>
    var socket = io('https://www.driveroadside.com:5005/');
    base_url = "<?php echo asset('/'); ?>";
</script>
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

<main class="page-wrap parent-main">
    <section class="map-address">
        <div class="map-imag " id="myMap">
{{--            <img src="{{asset('public/assets/images/map-image.png')}}"/>--}}
        </div>
    </section>
    @include('main.services')
    <div class="modal custom-modal" tabindex="-1" role="dialog" id="battery_model" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <form id="all_data">
                    @csrf
                    <input type="hidden" name='destinationLat' id="destinationLat">
                    <input type="hidden" name='destinationLng' id="destinationLng">
                    <input type="hidden" name='destinationaddress' id="destinationaddress">
                    <input type="hidden" name='destinationtplaceId' id="destinationtplaceId">
                    <input type="hidden" name="type" value="{{$type}}">
                    <input type="hidden" name="price" value="{{$price}}" id="priceonetime">
                    <input type="hidden" class="lat" name="lat" value="{{$lat}}"/>
                    <input  type="hidden" name="email_user" id="email_user">
                    <input type="hidden" class="lng" name="lng" value="{{$lng}}"/>
                    <input type='hidden' id="checkRepair" class="checkRepair" value="{{$check}}">
                    <input type="hidden" class="clearanceFeet1" name="lng123" value=""/>
                    <input type="hidden" id="checkUsersub" >
                    <div class="modal-body">
                        <div class="tab-lists">
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type == 'user')
                            <ul>
                                <li class="st1 active-tab-list"></li>
                                <li class="st2"></li>

                            </ul>
                            @else
                                <ul>
                                    <li class="st1 active-tab-list"></li>
                                    <li class="st2"></li>
                                    <li class="st3 "></li>
                                    <li class="st4"></li>
                                </ul>
                            @endif
                        </div>
                    <div class="modal-steps-form modal_step_1 step-active">
                        <div class="step-main-repeater">
                            <div class="modal-height">
                            <div class="step-form-title text-center">
                                @if($type == 'battery')
                                <h4>{{ucfirst($type)}} Jump Questions</h4>
                                @elseif($type == 'Tyre')
                                    <h4>Tire Questions</h4>
                                @elseif($type == 'fuel')
                                    <h4>Fuel Delivery Questions</h4>
                                @elseif($type == 'tow')
                                    <h4>Tow Questions</h4>
                                    @elseif($type == 'lockout')
                                    <h4>Lock Out Questions</h4>
                                    @endif
                            </div>
                            @if($type == 'battery')
                            <div class="custom-radio text-center">
                                <label>Is the key is with the vehicle?</label>
                                <div class="custom-box-radio">
                                    <div class="radio-repeater">
                                            <input type="radio" name="q1" value="Yes" id="key_yes" >
                                        <label for="key_yes">Yes</label>
                                    </div>
                                    <div class="radio-repeater">
                                        <input type="radio" name="q1" value="No" id="key_no" >
                                        <label for="key_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-radio  text-center">
                                <label>Are you with the vehicle?</label>
                                <div class="custom-box-radio">
                                    <div class="radio-repeater">
                                        <input type="radio" name="q2" value="Yes" id="vehicle_yes" >
                                        <label for="vehicle_yes">Yes</label>
                                    </div>
                                    <div class="radio-repeater">
                                        <input type="radio" name="q2" value="No" id="vehicle_no" >
                                        <label for="vehicle_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-radio  text-center">
                                <label>Did the vehicle stop while driving?</label>
                                <div class="custom-box-radio">
                                    <div class="radio-repeater">
                                        <input type="radio" name="q3" value="Yes" id="stop_yes" >
                                        <label for="stop_yes">Yes</label>
                                    </div>
                                    <div class="radio-repeater">
                                        <input type="radio" name="q3" value="No" id="stop_no" >
                                        <label for="stop_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-radio  text-center">
                                <label>Has anyone attempted to jump it yet?</label>
                                <div class="custom-box-radio">
                                    <div class="radio-repeater">
                                        <input type="radio" name="q4" value="Yes" id="attempt_yes" >
                                        <label for="attempt_yes">Yes</label>
                                    </div>
                                    <div class="radio-repeater">
                                        <input type="radio" name="q4" value="No" id="attempt_no" >
                                        <label for="attempt_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-radio  text-center">
                                <label>Is the vehicle located in parking garage?</label>
                                <div class="custom-box-radio">
                                    <div class="radio-repeater">
                                        <input type="radio" name="q5" value="Yes"  id="parking_yes" >
                                        <label for="parking_yes" id="heightClearnce">Yes</label>
                                    </div>
                                    <div class="radio-repeater">
                                        <input type="radio" name="q5" value="No" id="parking_no" >
                                        <label for="parking_no">No</label>
                                    </div>
                                </div>

                            </div>

                            @elseif($type == 'tire')
                                <div class="custom-radio  text-center">
                                    <label>Which tire is flat?</label>
                                    <div class="custom-box-radio">
                                        <div class="custom-tyres">
                                            <div class="tyres-columns">
                                                <div class="tyres-radio">
                                                    <input type="radio" name="q1" value="FR" id="parking_yes1" >
                                                    <label for="parking_yes1">Driver Front</label>
                                                </div>
                                                <div class="tyres-radio">
                                                    <input type="radio" name="q1" value="BR" id="parking_no23" >
                                                    <label for="parking_no23">Driver Rear</label>
                                                </div>
                                            </div>
                                            <div class="tyres-columns">
                                                <img src="{{asset('/public/assets/images/car1.svg')}}"/>
                                            </div>
                                            <div class="tyres-columns">
                                                <div class="tyres-radio">
                                                    <input type="radio" name="q1" value="BR" id="parking_no4" >
                                                    <label for="parking_no4">Passenger Front</label>
                                                </div>
                                                <div class="tyres-radio">
                                                    <input type="radio" name="q1" value="BL" id="parking_no3" >
                                                    <label for="parking_no3">Passenger Rear</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Do you have a spare tire?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Yes" id="stop_yes1" >
                                            <label for="stop_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater" onclick="tirenoavail()">
                                            <input type="radio" name="q2" value="No">
                                            <label for="stop_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Are you with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="Yes" id="parking_yes2" >
                                            <label for="parking_yes2">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="No" id="parking_no2" >
                                            <label for="parking_no2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is the vehicle located in parking garage?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="Yes"  id="parking_yes" >
                                            <label for="parking_yes" id="heightClearnce">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="No" id="parking_no" >
                                            <label for="parking_no">No</label>
                                        </div>
                                    </div>
                                </div>



                            @elseif($type == 'tow')
                                <div class="custom-radio text-center">
                                    <label>Is the key is with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="Yes" id="key_yes1" >
                                            <label for="key_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="No" id="key_no1" >
                                            <label for="key_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Are you with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Yes" id="vehicle_yes1" >
                                            <label for="vehicle_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="No" id="vehicle_no1" >
                                            <label for="vehicle_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Can the vehicle put in neutral?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="Yes" id="stop_yes2" >
                                            <label for="stop_yes2">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="No" id="stop_no2" >
                                            <label for="stop_no2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is your vehicle 4WD?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q4" value="Yes" id="attempt_yes1" >
                                            <label for="attempt_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q4" value="No" id="attempt_no1" >
                                            <label for="attempt_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Has your Vehicle been involved into a car accident?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="Yes" id="attempt_yes211" >
                                            <label for="attempt_yes211">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="No" id="attempt_no211" >
                                            <label for="attempt_no211">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Are you going to ride with the tow truck?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater" onclick="clearwenotGoing()">
                                            <input type="radio" name="q6" value="Yes"  id="attempt_yes3" >
                                            <label for="attempt_yes9">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q6" value="No" id="attempt_no3" >
                                            <label for="attempt_no3">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is the vehicle located in parking garage?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q7" value="Yes"  id="parking_yes" >
                                            <label for="parking_yes" id="heightClearnce">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q7" value="No" id="parking_no" >
                                            <label for="parking_no">No</label>
                                        </div>
                                    </div>
                                </div>


                            @elseif($type == 'lockout')

                                <div class="custom-radio  text-center">
                                    <label>Are you with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Yes" id="vehicle_yes2" >
                                            <label for="vehicle_yes2">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="No" id="vehicle_no2" >
                                            <label for="vehicle_no2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is the vehicle on or running?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="Yes" id="stop_yes5" >
                                            <label for="stop_yes5">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="No" id="stop_no5" >
                                            <label for="stop_no5">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio text-center">
                                    <label>Is the key is with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="Yes" id="key_yes2" >
                                            <label for="key_yes2">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="No" id="key_no2" >
                                            <label for="key_no2">No</label>
                                        </div>
                                    </div>
                                </div>

                             <div class="custom-radio  text-center">
                                    <label>Where are the keys?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q7" value="Trunk (inaccessible)" id="parking_yes4" >
                                            <label for="parking_yes4">Trunk (inaccessible)</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q7" value="Inside Vehicle" id="parking_no6" >
                                            <label for="parking_no6">Inside Vehicle</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Unknown" id="parking_no6" >
                                            <label id="lostorBroken" for="parking_no7">Lost or Broken</label>
                                        </div>
                                    {{--                                        <div class="radio-repeater">--}}
                                    {{--                                            <input type="radio" name="q2" value="Trunk (accessible)" id="parking_no8" >--}}
                                    {{--                                            <label for="parking_no8">Trunk (accessible)</label>--}}
                                    {{--                                        </div>--}}
                                    </div>
                                </div>

                                <div class="custom-radio  text-center">
                                    <label>Are any children or pet locked inside?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q4" value="Yes" id="stop_yes4" >
                                            <label for="stop_yes4">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q4" value="No" id="stop_no4" >
                                            <label for="stop_no4">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is the vehicle located in parking garage?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="Yes" id="parking_yes5" >
                                            <label for="parking_yes5" id="heightClearnce">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="No" id="parking_no9" >
                                            <label for="parking_no9">No</label>
                                        </div>
                                    </div>
                                </div>


                            @elseif($type == 'fuel')
                                <div class="custom-radio  text-center">
                                    <label>Are you with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="Yes" id="vehicle_yes3" >
                                            <label for="vehicle_yes3">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="no" id="vehicle_no3" >
                                            <label for="vehicle_no3">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="custom-radio  text-center">
                                    <label>What type of fuel?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Regular" id="parking_yes4" >
                                            <label for="parking_yes4">Regular</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Premium" id="parking_no6" >
                                            <label for="parking_no6">Premium</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Plus" id="parking_no7" >
                                            <label for="parking_no7">Plus</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Diesel" id="parking_no8" >
                                            <label for="parking_no8">Diesel</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is the vehicle located in parking garage?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="Yes" id="parking_yes5" >
                                            <label for="parking_yes5" id="heightClearnce">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="no" id="parking_no9" >
                                            <label for="parking_no9">No</label>
                                        </div>
                                    </div>
                                </div>



                            @else($type == 'winch')
                                  <div class="custom-radio text-center">
                                    <label>Is the key is with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="Yes" id="key_yes1" >
                                            <label for="key_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q1" value="No" id="key_no1" >
                                            <label for="key_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Are you with the vehicle?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="Yes" id="vehicle_yes1" >
                                            <label for="vehicle_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q2" value="No" id="vehicle_no1" >
                                            <label for="vehicle_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Can the vehicle put in netural?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="Yes" id="stop_yes2" >
                                            <label for="stop_yes2">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q3" value="No" id="stop_no2" >
                                            <label for="stop_no2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Is your Vehicle 4WD?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q4" value="Yes" id="attempt_yes1" >
                                            <label for="attempt_yes1">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q4" value="No" id="attempt_no1" >
                                            <label for="attempt_no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Vehicle driveable after winch out?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="Yes" id="attempt_yes2" >
                                            <label for="attempt_yes2">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q5" value="No" id="attempt_no2" >
                                            <label for="attempt_no2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-radio  text-center">
                                    <label>Within 10 ft of a paved surface?</label>
                                    <div class="custom-box-radio">
                                        <div class="radio-repeater">
                                            <input type="radio" class="within10ft" name="q6" value="Yes" id="attempt_yes3" >
                                            <label for="attempt_yes3">Yes</label>
                                        </div>
                                        <div class="radio-repeater">
                                            <input type="radio" name="q6" value="No" id="attempt_no3" >
                                            <label for="attempt_no3">No</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="custom-radio">
                                <label>Add special notes or instructions</label>
                                <textarea placeholder="Type Something here…" name="notes" class="special-notes" ></textarea>
                            </div>
                            </div>
                            <div class="custom-radio">
                                <a class="btn btn-next next_steps modal_step_1_btn" id="">NEXT</a>
                            </div>

                        </div>
                    </div>
                    <div class="modal-steps-form modal_step_2 ">
                        <div class="step-main-repeater">
                            <div class="step-form-title text-center">
                                <img src="{{asset('public/assets/images/car1.svg')}}"/>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type == 'user' && count(\Illuminate\Support\Facades\Auth::user()->hasCars) > 0)
                            <div class="custom-radio selected-cars">
                                <select name="auth_car_id" id="authCars">
                                    <option disabled selected>Select</option>
                                    @foreach(\Illuminate\Support\Facades\Auth::user()->myCars as $car)
                                    <option value="{{$car->id}}">{{$car->make.' '.$car->name.' '.$car->model}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="customCarSlect">
                            <div class="step-form-title text-center">
                                <h4>Add the vehicle in need of service</h4>
                            </div>
                            <div class="custom-radio ">
                                <select name="year" id="yearSelect">
                                    <option disabled selected>Year</option>
                                </select>
                            </div>
                            <div class="custom-radio ">
                                <select name="make" id="selectMake">
                                    <option>Make</option>
                                </select>
                            </div>
                            <div class="custom-radio ">
                                <select name="model" id="modalSelect">
                                    <option>Model</option>
                                </select>
                            </div>
                            <div class="custom-radio ">
                                    <select name="color">
                                        <option disabled selected>Color</option>
                                        <option>Black</option>
                                        <option>Blue</option><option>Brown</option><!-- comment -->
                                        <option>Green</option><!-- comment -->
                                        <option>Grey</option><!-- comment --><option>Purple</option><!-- comment -->
                                        <option>Red</option>
                                        <option>Silver</option><!-- comment -->
                                        <option>White</option><!-- comment -->
                                        <option>Yellow</option><!-- comment --><option>hazel</option><!-- comment -->
                                    </select>
                            </div>
                            </div>
                            <div class="custom-radio">
                                <a class="btn btn-next next_steps modal_step_2_btn"  >NEXT</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-steps-form modal_step_3">
                        <div class="step-main-repeater">
                            <div class="step-form-title text-center">
                                <img src="{{asset('public/assets/images/contact_icon.svg')}}"/>
                                <h4>Contact Information</h4>
                                <p>Please provide the contact information for <br/>the driver in need of help</p>
                            </div>
                            <div class="custom-radio ">
                                <input type="text" name="full_name" class="general-field" id="name_" placeholder="Full Name" />
                            </div>
                            <div class="custom-radio ">
                                <input  type="hidden" name="phone" class="general-field" id="phone_" placeholder="Phone No." />
                                <input  type="text" pattern="^\d{10}$" name="phone_test" class="general-field phone_with_contry" id="phone_test" placeholder="Phone No." maxlength="10" />
                            </div>
                            <div class="custom-radio ">
                                <input type="email"  name="email_" class="general-field" onchange="checkemail()" id="email_" placeholder="email" />
                            </div>

                            <div class="custom-radio">
                                <a class="btn btn-next next_steps modal_step_3_btn">NEXT</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-steps-form modal_step_4">
                        <div class="inner-steps text-center">
                            <h2>Total Cost</h2>
                            <h3 id="priceheadding">${{$price}}</h3>
                        </div>
                        <div class="step-main-repeater">
                            <div class="step-form-title text-center ">
                                <img src="{{asset('public/assets/images/payment.svg')}}"/>
{{--                                <h4>Add credit or debit card</h4>--}}
                            </div>

                            <div class="ifram-payment">
                            <iframe src="https://payflowlink.paypal.com?MODE=LIVE&SECURETOKENID=fdsfdsf234234234234234&SECURETOKEN=7wK0f0dHV8kqE9nylwCgrdAkA"
                                    name="test_iframe" scrolling="no" id="payflow-iframe"  height="250px" style="display: none;"></iframe>
                            </div>
                            <img src="<?=asset('public/assets/images/paypal1122.png')?>">

                               <!-- <div id="paypal-button-container"></div>-->
                            <a target="_blank" href="https://driveroadside.com/terms-of-use" style="text-align: center; display: block;">Terms & Condition </a>
                        </div>
                    </div>
                    <div class="modal-steps-form modal_step_5">

                        <div class="step-main-repeater">
                            <div class="step-form-title text-center ">
                                <img src="{{asset('public/assets/images/payment.svg')}}"/>
                                <h4 >One time pay <h4 id="onetimepayPrice">${{$price}}</h4></h4>
                                <input type="hidden" value="{{$price}}" id="serviceprice">

                            </div>
                            <div class="custom-radio">
                                <button class="btn btn-next modal_step_5_btn"type="button" style="background-color: red">Pay Per Use</button>
                            </div>
                            <div class="credit-cart-save" style="margin-bottom: 5px;">
                                <button id="strp-card-btn-coupon-register" type="button" class="btn btn_grey ">Add Coupon</button>
                            </div>
                            <div class="custom-radio">
                                <a href="{{route('subsecriptions')}}"  class="btn btn-next modal_step_5_btn" type="button">Upgrade your Subscription</a>
                            </div>

                        </div>
                    </div>
                </div>

                </form>

            </div>
            <div class="scroll-images">
                <img src="{{asset('public/assets/images/scroll-down.png')}}"/>
            </div>
        </div>

    </div>
    <div class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" role="dialog" id="clearnceModalmain">
        <div class="modal-dialog" role="document">
            <div class="modal-content">



                <div class="modal-body">
                    <button type="button" onclick="shutModalFeet()"  class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="garage-content">
                        <div class="custom-radio">
                            <h4>Please provide the height clearance of the parking garage in feet.</h4>

                            <div class="quantity">
                                <input type="button" class="qtyminus" field="quantity">
                                <input type="number" name="quantity" min="1" value="1" class="qty" id="clearanceFeet">
                                <input type="button" class="qtyplus" field="quantity">
                            </div>
{{--                            <input type="number" class="address-field"  placeholder="0" name="clearanceFeet" >--}}
                        </div>
                        <div class="custom-radio">
                            <button type="button" class="btn btn-next " id="clearanceModal" >Save</button>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" role="dialog" id="coupon_system">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" onclick="shutModalFeet()"  class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="garage-content">
                        <div class="custom-radio">
                            <h4>Please add coupon and get discount</h4>

                            <div class="quantity">
                                <input type="text" name="coupon_added"  class="qty" id="coupon_added">
                            </div>
{{--                            <input type="number" class="address-field"  placeholder="0" name="clearanceFeet" >--}}
                        </div>
                        <div class="custom-radio">
                            <button type="button" class="btn btn-next " id="checkCouponUser" >Save</button>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>    <div class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" role="dialog" id="coupon_system-register">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" onclick="shutModalFeet()"  class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="garage-content">
                        <div class="custom-radio">
                            <h4>Please add coupon and get discount</h4>

                            <div class="quantity">
                                <input type="text" name="coupon_added"  class="qty" id="coupon_added_register">
                            </div>
{{--                            <input type="number" class="address-field"  placeholder="0" name="clearanceFeet" >--}}
                        </div>
                        <div class="custom-radio">
                            <button type="button" class="btn btn-next " id="checkCouponRegister" >Save</button>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
    @if($check == 1)
        <div class="modal" tabindex="-1" role="dialog" id="RepairLocations">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <button type="button" class="close" id="RepairLocationsbtn">
                        <span aria-hidden="true">&times;</span>
                    </button>

                <div class="modal-body">
                    <div class="garage-content">
                        <div class="custom-radio">
                            <div class="step-form-title text-center">
                                <h4>Drop off location</h4>
                            </div>

                            <div id="selectionDesLocation">
                                <label>Enter Drop off location</label>
                                <input type="text" class="address-field" placeholder="Enter Drop off Location" id="locations"/>
                                <div id="locations"></div>
                                <div class="or-loaction">
                                    <span>OR</span>
                                </div>
                                <button id="neatrestWorkshops" class="btn btn-next next_steps ">Find Closest Auto Shop</button>

                            </div>
                            <div class=" nearest-locations">
                            @foreach($carRepairs as $rlocations)

                            <div class="destinationPopUp"  style="border: #000000 1px solid">
                                <div class="destinationMile">
                                    <img src="{{asset('public/assets/images/pin1.png')}}" />
                                    <p>{{$rlocations['geometry']['location']['distance']}} Mi</p>
                                </div>
                                <p class="destinationTitle">{{$rlocations['name']}}</p>
                                {{--                                <p>Status : 1</p>--}}

                                <!-- comment -->
                                <p class="destinationLocation">{{$rlocations['vicinity']}}</p>

                                 <div class="reviewAndStar" >
                                    <p class="ratingStar">
                                        rate :
                                        <ul class="starIcon">
                                         @if(isset($rlocations['rating']))
                                         @for($i=0;$i < $rlocations['rating']; $i++)

                                            <li><img src="{{asset('public/assets/images/star.png')}}" /></li>
                                             @endfor
                                         @else
                                             <li>No Rating</li>
                                             @endif
                                        </ul>
                                    </p>
                                    <input type="hidden" id="address_{{$rlocations['place_id']}}" value="{{$rlocations['vicinity']}}">
                                    <input type="hidden" id="lat_{{$rlocations['place_id']}}" value="{{$rlocations['geometry']['location']['lat']}}">
                                    <input type="hidden" id="lng_{{$rlocations['place_id']}}" value="{{$rlocations['geometry']['location']['lat']}}">
                                    <input type="hidden" id="placeId_{{$rlocations['place_id']}}" value="{{$rlocations['place_id']}}">
                                    <input type="hidden" id="distance_{{$rlocations['place_id']}}" value="{{$rlocations['geometry']['location']['distance']}}">
                                    <div class="contact-btnss">
                                    <div id="PlaceInfo_{{$rlocations['place_id']}}" class="phone-icons">
                                        <a href="tel:{{$rlocations['contact']}}"  class="destinationPhoneCall_{{$rlocations['place_id']}}}}"  ><img src="{{asset('public/assets/images/phone-icon.png')}}" /></a>
                                    </div>
                                     <button class="destinationBtn" type="button" onclick="setDestination('{{$rlocations['place_id']}}')">Select</button>
                                     </div>
                                 </div>
                            </div>
                            @endforeach
                            </div>
                        </div>
<!--                        <div class="custom-radio">
                            <button type="button" class="btn btn-next " id="clearanceModal" >Save</button>
                        </div>-->
                    </div>


                </div>

            </div>
        </div>
    </div>
@endif

<div class="modal" tabindex="-1" role="dialog" id="service-cancel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="step-form-title text-center">
            <h4>Report Service</h4>
        </div>
        <div class="service-dropdown">
            <select>
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
        <div class="other-reasons">
            <textarea placeholder="Type Something here…" name="notes" class="special-notes"></textarea>
        </div>
        <div class="other-reasons custom-radio">
            <button class="btn btn-next next_steps">Submit</button>
        </div>
    </div>
  </div>
</div>
</main>

{{--<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOLE_MAP_KEY')}}"></script>--}}
{{--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{env('GOOLE_MAP_KEY')}}"></script>--}}
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script>
    $(document).on("click", ".paypal-button-row.paypal-button-number-1", function() {
        alert('hitt');
    });
   const phoneInputField = document.querySelector("#phone_test");
 const phoneInput = window.intlTelInput(phoneInputField, {
     initialCountry: "us",
  utilsScript:
    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});

 </script>
<script type="text/javascript">
    $('#tirenothave').on('click',function(){
    });

    //
   function tirenoavail()
   {
       Swal.fire({
           title: 'Sorry',
           text: "you will need a functional spare tire in order to complete the service otherwise you will need to request a tow",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Get tow service',
           cancleButtonText:""
       }).then((result) => {
           if (result.isConfirmed) {
               window.location.href="/step-2?type=tow";
           }
       })
   }
</script>
    <script>
        function clearwenotGoing()
        {
                 Swal.fire({
                title: 'Sorry',
                text: "Due to the Covid virus our rescue drivers are not allowing passengers. You can order a ride to your next destination using the link below.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Book Uber Ride',
                cancleButtonText:""
            }).then((result) => {
                if (result.isConfirmed) {
                window.location.href="https://www.uber.com/jp/en/ride/ubertaxi/";
                }
            })
        }
        $('#lostorBroken').on('click',function(){
            // alert('Sorry but we don’t provide key replacement services.');
            // const Toast = Swal.mixin({
            //     toast: true,
            //     position: 'top-end',
            //     showConfirmButton: false,
            //     timer: 3000,
            //     timerProgressBar: true,
            //     didOpen: (toast) => {
            //         toast.addEventListener('mouseenter', Swal.stopTimer)
            //         toast.addEventListener('mouseleave', Swal.resumeTimer)
            //     }
            // })
            Swal.fire({
                title: 'Sorry',
                text: "But we don’t provide key replacement services.",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay'
            }).then((result) => {
                if (result.isConfirmed) {

                    // $('#carId').val($carId);
                    // $("#deletecarForm").submit()
                }
            })
        });

        // function for closing modal
        $('#RepairLocationsbtn').on('click',function(){
            $('#battery_model').modal('hide');
        });

        $("#closeModalMain").on('click',function(){
            $('#battery_model').modal('hide');
        });

        $('.within10ft').on('click',function(){
                 Swal.fire({
                title: 'Sorry',
                text: "We apologize but we are unable provide winch services for vehicles more than 10 feet from a paved surface.",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay'
            }).then((result) => {
                if (result.isConfirmed) {

                    // $('#carId').val($carId);
                    // $("#deletecarForm").submit()
                }
            })
        });


        $('#address_pay').on('keypress',function (){
            autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address_pay'), { types: [ 'geocode' ] });
            google.maps.event.addListener(autocomplete2, 'place_changed', function() {
                fillInAddress();
            });
        });


        function fillInAddress()
        {
            var place = autocomplete2.getPlace();
            var componentForm = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'short_name',
                postal_code: 'short_name'
            };
            var elementCompements = {
                postal_code: 'zipcode',
                locality: 'city',
                country: 'country',
                administrative_area_level_1: 'state'
            };
            for (var component in elementCompements) {
                document.getElementById(elementCompements[component]).value = '';
            }
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    if (elementCompements[addressType])
                        document.getElementById(elementCompements[addressType]).value = val;
                }
            }
            var str = document.getElementById('address').value;
            document.getElementById('address').value = str.substring(0,47);
        }

        var address1;
        var lat = {{$lat}};
        var lng = {{$lng}};
        function initAutocomplete() {
            var lat = {{$lat}};
            var lng = {{$lng}};
          var defaultPlace = new google.maps.LatLng(lat,lng);
            var circle = new google.maps.Circle({ center: new google.maps.LatLng(lat, lng), radius: 50000 })
            var options = {
                location: defaultPlace,
                bounds:circle.getBounds(),
                strictbounds: true,
                 // types: ['establishment'],
                //componentRestrictions: { country: "" },
            };
                console.log(defaultPlace);
            address1 = new google.maps.places.Autocomplete(document.getElementById('locations'),options);
            // address1.setFields(['address_component', 'geometry']);

            address1.addListener('place_changed', fillInAddress1);
        }
        console.log(address1)
        function fillInAddress1() {
            var currentLat = {{$lat}};
            var currentLng = {{$lng}};
            var place = address1.getPlace();
            ////////
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            var distanceKm = getDistanceFromLatLonInKm(currentLat,currentLng,lat,lng);
            var distance = distanceKm*0.62137;
            var price = Math.round(99+(distance*5));
            $('#priceonetime').val(price);
            $('#priceheadding').html('$'+price);
            $('#onetimepayPrice').html('$'+price);
            $('#destinationLat').val(lat);
            $('#destinationLng').val(lng);
            $('#destinationaddress').val($('#locations').val());
            $('#serviceprice').val(price);
            $('#RepairLocations').modal('hide');
            $('#battery_model').modal('show');
        }

        $('.modal_step_5_btn').on('click',function(){
            $(this).prop('disabled', true);
        });

        function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = deg2rad(lat2-lat1);  // deg2rad below
            var dLon = deg2rad(lon2-lon1);
            var a =
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon/2) * Math.sin(dLon/2)
            ;
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var d = R * c; // Distance in km
            return d;
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180)
        }


        // function for getting place information from google by place id

        function getplaceDetails($placeId)
        {
            $.ajax({
                type: 'get',
                url: '{{route('getphoneNumber')}}',
                data:{placeId:$placeId},
                success: function (data) {
                    var newUrl = 'tell:'+data.phone;
                    $('.destinationPhoneCall_'+$placeId).attr("href", newUrl);
                    $("a").attr("href", "http://www.google.com/")
                   //  var html = '<div>' +
                   //      '<p>'+data.phone+'</p>' +
                   //      '</div>'
                   // $('#PlaceInfo_'+$placeId).html(html);
                }
            });

         {{-- console.log(url);--}}
         {{--  //getting response--}}
         {{--   $placeInfo = httpGet(url);--}}
         {{--   console.log($placeInfo);--}}
         {{--   alert();--}}
        }

        // function for get request in js
        function httpGet(theUrl)
        {
            var requestOptions = {
                method: 'GET',
                redirect: 'follow'
            };

            fetch("https://maps.googleapis.com/maps/api/place/details/json?place_id=ChIJZ05ifqsEGTkRr_K-f0jJsAM&fields=name,rating,formatted_phone_number&key=AIzaSyAGuoV2jNNM9d_mrSiMkjSwXJw29mBiO5I", requestOptions)
                .then(response => response.text())
                .then(result => console.log(result))
                .catch(error => console.log('error', error));
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOLE_MAP_KEY')}}&libraries=places&callback=initAutocomplete"></script>

<script type="text/javascript">

    function shutModalFeet()
    {
       $('#clearnceModalmain').hide();
    }

    $( document ).ready(function() {
        // $( "#selectMake" ).prop( "disabled", true );
        // $( "#modalSelect" ).prop( "disabled", true );
        $('.nearest-locations').hide();
    });

    $('#neatrestWorkshops').on('click',function(){
       $('#selectionDesLocation').hide();
       $('.nearest-locations').show();
    });

    $('.modal_step_5_btn').on('click',function()
    {
        $price = $('#serviceprice').val();
               $.ajax({
                type: 'POST',
                url: '{{route('payuseronetime')}}',
                data: {'price':$price,type:'{{$type}}'},
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                success: function (data) {
            if(data.error == 1)
                {
                     Toast.fire({
                        icon: 'error',
                        title: data.message,
                      });
                }else
                {
                    $( "#all_data" ).submit();
                }


                }
            });
    });

    $('#authCars').on('change',function(){
        $('.customCarSlect').hide();
    })

    $(function () {


        $('#all_data').on('submit', function (e) {
            createHubspot();
          $('#battery_model').modal('hide');
          $('#preloader').show();
           e.preventDefault();
            const Toast = Swal.mixin({
         toast: true,
         position: 'top-end',
         showConfirmButton: false,
         timer: 3000,
         timerProgressBar: true,
         didOpen: (toast) => {
           toast.addEventListener('mouseenter', Swal.stopTimer)
           toast.addEventListener('mouseleave', Swal.resumeTimer)
         }
       })
           $.ajax({
               type: 'post',
                url: '{{route('step_5')}}',
               data: $("#all_data").serialize(),
               success: function (data) {
           if(data.error == 1)
               {
                     Toast.fire({
                       icon: 'error',
                       title: data.message,
                     })
               }else
               {
                   $('#preloader').hide();
                    Toast.fire({
                       icon: 'success',
                       title: data.message,
                     });
                   createHubspot();
                  // window.location.href = "{{URL::to('jobdetail')}}/"+data.job_id;
                }


                }
            });

        });

    });

    //

    function createHubspot(){

        $.ajax({
            type: 'post',
            url: '{{route('create_hubspot')}}',
            data: $("#all_data").serialize(),
            success: function (data) {
               console.log('done hubspot');
            }
        });
    }
        function errorShow(){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'error',
                title: 'All fields are required!',
            })
        }
    $('.modal_step_1_btn').click(function(){
        $(".st2").addClass("active-tab-list");
        @if($type == 'battery')
        if ($("input[name=q1]:checked").val() == undefined ||$("input[name=q2]:checked").val() == undefined ||$("input[name=q3]:checked").val() == undefined ||$("input[name=q4]:checked").val() == undefined ||$("input[name=q5]:checked").val() == undefined){
                    errorShow();
        }else{
            $(".st2").removeClass("active-tab-list");
            $(".modal_step_1").removeClass("step-active");
            $(".modal_step_2").addClass("step-active");
        }
            @elseif($type == 'tire')
            if ($("input[name=q1]:checked").val() == undefined ||$("input[name=q2]:checked").val() == undefined ||$("input[name=q3]:checked").val() == undefined){
                errorShow();
                $(".st2").removeClass("active-tab-list");
            }else{
                $(".modal_step_1").removeClass("step-active");
                $(".modal_step_2").addClass("step-active");
            }
        @elseif($type == 'tow')
        if ($("input[name=q1]:checked").val() == undefined ||$("input[name=q2]:checked").val() == undefined ||$("input[name=q3]:checked").val() == undefined ||$("input[name=q4]:checked").val() == undefined ||$("input[name=q5]:checked").val() == undefined||$("input[name=q6]:checked").val() == undefined||$("input[name=q7]:checked").val() == undefined){
            errorShow();
            $(".st2").removeClass("active-tab-list");
        }else{
            $(".modal_step_1").removeClass("step-active");
            $(".modal_step_2").addClass("step-active");
        }
        @elseif($type == 'lockout')
        if ($("input[name=q1]:checked").val() == undefined ||$("input[name=q2]:checked").val() == undefined ||$("input[name=q3]:checked").val() == undefined ||$("input[name=q4]:checked").val() == undefined ||$("input[name=q5]:checked").val() == undefined){
            errorShow();
            $(".st2").removeClass("active-tab-list");
        }else{
            $(".modal_step_1").removeClass("step-active");
            $(".modal_step_2").addClass("step-active");
        }
        @elseif($type == 'fuel')
        if ($("input[name=q1]:checked").val() == undefined || $("input[name=q2]:checked").val() == undefined ||$("input[name=q3]:checked").val() == undefined){
            errorShow();
            $(".st2").removeClass("active-tab-list");
        }else{
            $(".modal_step_1").removeClass("step-active");
            $(".modal_step_2").addClass("step-active");
        }
        @elseif($type == 'winch')
        $(".modal_step_1").removeClass("step-active");
        $(".modal_step_2").addClass("step-active");
            @endif

    });
    $('.modal_step_2_btn').click(function(){
        $(".st3").addClass("active-tab-list");
        @if(\Illuminate\Support\Facades\Auth::user()->user_type == 'user')
            $('#email_user').val('{{\Illuminate\Support\Facades\Auth::user()->email}}' )
           checkuserSub();
        @else

        if ($("#yearSelect").val() == undefined || $("#selectMake").val() == undefined ||$("#modalSelect").val() == undefined){
            errorShow();
            $(".st3").removeClass("active-tab-list");
        }else{
        $(".modal_step_2").removeClass("step-active");
        $(".modal_step_3").addClass("step-active");
       }
            @endif

    });
    $('.modal_step_3_btn').click(function(){

        $('#phone_').val(phoneInput.getNumber());

        if ($('#email_').val() == '' || $('#name_').val() == '' || $('#phone_').val() == '' ){
            errorShow();
        }else {
            $(".st4").addClass("active-tab-list");
            $(".modal_step_3").removeClass("step-active");
            $(".modal_step_4").addClass("step-active");
            $(".tab-lists").addClass("step-active-lists");
            showIframe();
        }
    });
    $('.modal_step_4_btn').click(function(){
        $( "#all_data" ).submit();

    });

    function checkuserSub()
    {
        $price =  $('#priceonetime').val
                    $.ajax({
                type: 'get',
                url: '{{route('checkusersub')}}',
//                data: {'price':$price},
                success: function (data) {
            if(data.error == 1)
                {
                $(".modal_step_2").removeClass("step-active");
               $(".modal_step_5").addClass("step-active");
                }else
                {
                $( "#all_data" ).submit();
                }

                }
            });
    }

    var map;
    var check;
    var marker;
    var position = ['{{$lat}}','{{$lng}}'];

    var myLatlng = new google.maps.LatLng(position[0],position[1]);
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow({
        content:'<a onclick="confirm_location()" id="hook">Click to confirm location</a>'
    });
    function initialize(check){
        var mapOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
        var html = 'Click to confirm Location';
        var icon = {
            url:'{{asset('public/assets/images/drag location.svg')}}', // url
        };

            marker = new google.maps.Marker({
                map: map,
                position: myLatlng,
                draggable: true,
                icon: {
                    url:'{{asset('public/assets/images/drag location.svg')}}', // url
                    strokeColor: "blue",
                    scale: 3
                },

            });


        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('.lat').val(marker.getPosition().lat());
                    $('.lng').val(marker.getPosition().lng());

                    // infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                }
            }
        });

        google.maps.event.addListener(marker, 'dragend', function() {

            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('.lat').val(marker.getPosition().lat());
                        $('.lng').val(marker.getPosition().lng());

                        infowindow.open(map, marker);
                    }
                }
            });
        });

    }
    function initialize1(check){
        var mapOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
        var html = 'Confirm Location';
        var icon = {
            url:'{{asset('public/assets/images/roadside.svg')}}', // url
        };
        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: false,
            icon: {
                url:'{{asset('public/assets/images/roadside.svg')}}', // url
                strokeColor: "blue",
                scale: 3
            },

        });


        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('.lat').val(marker.getPosition().lat());
                    $('.lng').val(marker.getPosition().lng());
                    // infowindow.setContent(results[0].formatted_address);
                     infowindow.open(map, marker);
                }
            }
        });


    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $('.modal_step_1_btn').on('click',function(){
        $.ajax({
            url : 'getyears',
            type:'get',
            success: function(data){
                $('#yearSelect').html(data.years);
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'error',
                    title: msg,
                })

            },
        });
    });

    function confirm_location(){
        var check = 1;
        initialize();
{{--        @if($type == 'battery')--}}
            var check = $('#checkRepair').val();
            if(check == 1)
            {

             $('#RepairLocations').modal('show');
            }else {
                $('#battery_model').modal('toggle');
            }
{{--        @endif--}}

    }
    // const Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000,
    //     timerProgressBar: true,
    //     didOpen: (toast) => {
    //         toast.addEventListener('mouseenter', Swal.stopTimer)
    //         toast.addEventListener('mouseleave', Swal.resumeTimer)
    //     }
    // });

    //payment for guest userContact Information
    $('#strp-card-btn').on('click',function(){
       // alert($('#email_').val());
        document.getElementById("strp-card-btn").style.background='green';
        $('#strp-card-btn').html('<i class="fa fa-spinner fa-spin"></i>Loading');
        $('#strp-card-btn').prop('disabled', true);
        Stripe.setPublishableKey('{{ env('STRIPE_KEY')}}');
        Stripe.card.createToken({
            number: $('#cNumber').val(),
            cvc: $('#cCvc').val(),
            exp_month: $('#cExpMonth').val(),
            exp_year: $('#cExpYear').val(),

        }, stripeResponseHandler);



    });

    //
    function stripeResponseHandler(status, response)
    {
        if (response.error) {
            console.log(response.error)
            // Show the errors on the form
            document.getElementById("strp-card-btn").style.background='#2d81bc';
            $('.payment-errors').show().text(response.error.message);
            $('#strp-card-btn').html('Pay Now');
            $('#strp-card-btn').prop('disabled', false);
            // $form.find('button').prop('disabled', false);
        } else {
            // response contains id and card, which contains additional card details
            var token = response.id;

            $.ajax({
                type: 'post',
                url: '{{route('chargepaymentguest')}}',
                data: {
                    token: token,
                    price:$('#serviceprice').val(),
                    name:$('#name_').val(),
                    email:$('#email_').val(),
                    city:$('#city').val(),
                    street:$('#address_pay').val(),
                    country:$('#country').val(),
                    state:$('#state').val(),
                    type:'{{$type}}',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.error == 1) {

                        $('.payment-errors').html(data.message);
                        $('#strp-card-btn').html('Pay Now');
                        document.getElementById("strp-card-btn").style.background = '#2d81bc';
                        $('#strp-card-btn').prop('disabled', false);
                    } else {
                        $('#strp-card-btn').html('Pay Now');
                        document.getElementById("strp-card-btn").style.background = '#2d81bc';
                        $('#strp-card-btn').prop('disabled', false);
                        $("#all_data").submit();
                    }


                }
            })
        }

    }

    $('#heightClearnce').on('click',function()
    {
        // alert('hitttt');
        $('#clearnceModalmain').show();
    });

    $('#clearanceModal').on('click',function(){
        $feet = $('#clearanceFeet').val();
        $('#clearanceFeet1').val($feet);
        $('#clearnceModalmain').hide();
    })

    // function for upgrading subsecription
    $('#upgradeSubsecription').on('click',function()
    {
        $.ajax({
            type: 'post',
            url: '{{route('upgradeusersubsecription')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if(data.error == 1)
                {
                    Toast.fire({
                        icon: 'error',
                        title: data.message,
                    })
                }else
                {
                    $( "#all_data" ).submit();
                }


            }
        });
    });


        //function for setting destination location

        function setDestination($placeId)
        {
            $('#destinationLat').val($('#lat_'+$placeId).val());
            $('#destinationLng').val($('#lng_'+$placeId).val());
             $('#destinationaddress').val($('#address_'+$placeId).val());
            $('#destinationtplaceId').val($('#placeId_'+$placeId).val());
            var distance = $('#distance_'+$placeId).val();
            var price = Math.round(99+(distance*5));
            $('#priceonetime').val(price);
            $('#priceheadding').html('$'+price);
            $('#onetimepayPrice').html('$'+price);
            $('#serviceprice').val(price);
            $('#RepairLocations').modal('hide');
            $('#battery_model').modal('show');
    }

    function calcCrow(lat1, lon1, lat2, lon2)
    {
      var R = 6371; // km
      var dLat =toRad(lat2-lat1);
      var dLon = toRad(lon2-lon1);
      var lat1 = toRad(lat1);
      var lat2 = toRad(lat2);

      var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      var d = R * c;
      return d;
    }

    // Converts numeric degrees to radians
    function toRad(Value)
    {
        return Value * Math.PI / 180;
    }



</script>
@include('layouts/main_sidebar')
@include('layouts/footer')
<script>
    jQuery(document).ready(function() {

// This button will increment the value
        $('.qtyplus').click(function(e) {
// Stop acting like a button
            e.preventDefault();
// Get the field name
            fieldName = $(this).attr('field');
// Get its current value
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
// If is not undefined
            if (!isNaN(currentVal)) {
// Increment
                $('input[name=' + fieldName + ']').val(currentVal + 1);
            } else {
// Otherwise put a 0 there
                $('input[name=' + fieldName + ']').val(0);
            }
        });
// This button will decrement the value till 0
        $(".qtyminus").click(function(e) {
// Stop acting like a button
            e.preventDefault();
// Get the field name
            fieldName = $(this).attr('field');
// Get its current value
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
// If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
// Decrement one
                $('input[name=' + fieldName + ']').val(currentVal - 1);
            } else {
// Otherwise put a 0 there
                $('input[name=' + fieldName + ']').val(0);
            }
        });
    });


    {{--{--}}
    {{--    $('#email_').on('change',function(){--}}
    {{--        $email=$('#email_').val();--}}
    {{--        const Toast = Swal.mixin({--}}
    {{--            toast: true,--}}
    {{--            position: 'top-end',--}}
    {{--            showConfirmButton: false,--}}
    {{--            timer: 3000,--}}
    {{--            timerProgressBar: true,--}}
    {{--            didOpen: (toast) => {--}}
    {{--                toast.addEventListener('mouseenter', Swal.stopTimer)--}}
    {{--                toast.addEventListener('mouseleave', Swal.resumeTimer)--}}
    {{--            }--}}
    {{--        })--}}
    {{--        $.ajax({--}}
    {{--            type: 'post',--}}
    {{--            url: '{{route('checkemail')}}',--}}
    {{--            data:{'email':$email},--}}
    {{--            headers: {--}}
    {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
    {{--            },--}}
    {{--            dataType: "json",--}}
    {{--            success: function (data) {--}}
    {{--                console.log(data);--}}
    {{--                if(data.error == 1)--}}
    {{--                {--}}
    {{--                    Toast.fire({--}}
    {{--                        icon: 'error',--}}
    {{--                        title: data.message,--}}
    {{--                    })--}}
    {{--                    $('#email_').val('');--}}
    {{--                    $('.modal_step_3_btn').prop('disabled',true);--}}
    {{--                }else{--}}
    {{--                    $('.modal_step_3_btn').prop('disabled',false);--}}
    {{--                }--}}


    {{--            }--}}
    {{--        });--}}
    {{--    })--}}


    {{--}--}}


    $('#strp-card-btn-coupon').on('click',function(){
        document.getElementById("strp-card-btn-coupon").style.background='red';
        $('#coupon_system').modal('show');
    });
    $('#strp-card-btn-coupon-register').on('click',function(){
        document.getElementById("strp-card-btn-coupon").style.background='red';
        $('#coupon_system-register').modal('show');
    });

    $('#checkCouponUser').on('click',function (){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        $('#checkCouponUser').prop('disabled',true);
        $('#checkCouponUser').html('<i class="fa fa-spinner fa-spin"></i>Loading');
     $coupon = $('#coupon_added').val();
        $.ajax({
            type: 'post',
            url: '{{route('checkCouponUser')}}',
            data:{'coupon':$coupon},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                if(data.error == 1)
                {
                    Toast.fire({
                        icon: 'error',
                        title: data.message,
                    });
                    $('#checkCouponUser').html('Retry');
                    $('#checkCouponUser').prop('disabled',false);
                }else{

                    $price = $('#serviceprice').val();

                   $price =parseInt($price);
                   $discount = $price/100*parseInt(data.message);
                   $price = $price-$discount;
                    $('#priceheadding').html('$'+$price);
                    $('#onetimepayPrice').html('$'+$price);
                    $('#serviceprice').val($price);
                    $('#coupon_system').modal('hide');
                    $('#strp-card-btn-coupon').prop('disabled',true);

                    // $('.modal_step_3_btn').prop('disabled',false);
                }


            }
        });
    });

    $('#checkCouponRegister').on('click',function (){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        $('#checkCouponRegister').html('<i class="fa fa-spinner fa-spin"></i>Loading');
        $('#checkCouponRegister').prop('disabled',true);
     $coupon = $('#coupon_added_register').val();
        $.ajax({
            type: 'post',
            url: '{{route('checkCouponUser')}}',
            data:{'coupon':$coupon},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                if(data.error == 1)
                {
                    Toast.fire({
                        icon: 'error',
                        title: data.message,
                    })
                    $('#checkCouponRegister').html('Retry');
                    $('#checkCouponRegister').prop('disabled',false);
                }else{

                    $price = $('#serviceprice').val();

                   $price =parseInt($price);
                   $discount = $price/100*parseInt(data.message);
                   $price = $price-$discount;
                    $('#priceheadding').html('$'+$price);
                    $('#onetimepayPrice').html('$'+$price);
                    $('#serviceprice').val($price);
                    $('#coupon_system-register').modal('hide');
                    $('#strp-card-btn-coupon-register').prop('disabled',true);
                }


            }
        });
    });


$(document).ready(function(){



    // window.addEventListener("message", receiveMessage, false);
    // function receiveMessage(event)
    // {
    //     console.log('[parent]' + event.data);
    //     $('#preloader').show();
    //     $( "#all_data" ).submit();
    // }
});
    // window.onmessage = (event) => {
    //     event.source.window.postMessage('GOT_YOU_IFRAME', '*')
    // }

    function showIframe() {

        var amount = '{{$price}}';

        $('#pay_via_payflow').prop('disabled', true);
        var token = '<?=csrf_token()?>';
        $.ajax({
            type: 'post',
            url: '{{route('pay_via_payflow')}}',
            data: {
                'amount': amount,
                '_token': token
            },
            headers: {
                'X-CSRF-TOKEN': '<?=csrf_token()?>'
            },
            success: function (data) {
                if (data) {
                    var form = $( "#all_data" ).serialize();
                    localStorage.setItem("job_form",form);
                    var loc = 'https://payflowlink.paypal.com?MODE=LIVE&SECURETOKENID=' + data.secure_token_id + '&SECURETOKEN=' + data.secure_token + '&form='+form+'';
                    $('#payflow-iframe').attr('src', loc);
                    $('#payflow-iframe').show();

                    // $('#preloader').show();
                    //
                    // $( "#all_data" ).submit();
                } else {
                    // alert('Invalid account number');
                    // $('#pay_via_payflow').prop('disabled', false);

                }


            }, error: function (data) {
                console.log(data);
            }
        });
    }
    window.addEventListener('message', function(e) {
        // alert('56565');

        if(e.data == "iFrame sent a message"){
            $('#preloader').show();
            localStorage.removeItem("job_form");
            $( "#all_data" ).submit();
        }

        // const data = JSON.parse(e.data);
        // // Where does the message come from
        // const channel = data.channel;
    });

</script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
  {{--paypal.Buttons({--}}
  {{--  enableStandardCardFields: true,--}}
  {{--  hidePostalCode: true,--}}
  {{--  createOrder: function(data, actions) {--}}
  {{--      $price = $('#priceonetime').val();--}}
  {{--    // This function sets up the details of the transaction, including the amount and line item details.--}}
  {{--    return actions.order.create({--}}

  {{--      purchase_units: [{--}}
  {{--          "description":"My Product Name",--}}

  {{--        amount: {--}}
  {{--          value: '{{$price}}',--}}
  {{--        },--}}

  {{--      }],--}}
  {{--      shipping_type: 'PICKUP',--}}
  {{--      application_context: {--}}
  {{--      shipping_preference: 'NO_SHIPPING'--}}
  {{--    }--}}
  {{--    });--}}
  {{--  },--}}
  {{--  onApprove: function(data, actions) {--}}
  {{--      $('#battery_model').modal('hide');--}}
  {{--        $('#preloader').show();--}}
  {{--      $('#paypal-button-container').hide();--}}
  {{--    // This function captures the funds from the transaction.--}}
  {{--    return actions.order.capture().then(function(details) {--}}
  {{--      // This function shows a transaction success message to your buyer.--}}

  {{--      $( "#all_data" ).submit();--}}
  {{--    });--}}
  {{--  },--}}
  {{--  onError: function (err) {--}}
  {{--  // For example, redirect to a specific error page--}}
  {{--  console.log(err);--}}
  {{--}--}}
  {{--}).render('#paypal-button-container');--}}

var $myDiv = $('#card-fields-container');

if ( $myDiv.length > 0){
console.log('dfasd');
}


</script>

    <script type="text/javascript">


        socket.on('paypal_send', function (data) {
alert('dsds');
        });
    </script>
