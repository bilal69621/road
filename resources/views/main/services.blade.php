<section class="service-section">
    <div class="container">
        <div class="service-row">
            <div class="service-column text-center">
                <a href="{{route('step_2',['type'=>'battery'])}}" class="wait2">
                    @if($type == 'battery')
                        <div class="service-repeater service-active">
                            @else
                                <div class="service-repeater">

                                    @endif
                                    <div class="service-img">
                                        <img src="{{asset('public/assets/images/icon1.svg')}}"/>
                                    </div>
                                    <h4>Battery</h4>
                                </div>
                        </div></a>
                <div class="service-column text-center">
                    <a href="{{route('step_2',['type'=>'tire'])}}" class="wait2">
                        @if($type == 'tire')
                            <div class="service-repeater service-active">
                                @else
                                    <div class="service-repeater">

                                        @endif
                                        <div class="service-img">
                                            <img src="{{asset('public/assets/images/icon2.svg')}}"/>
                                        </div>
                                        <h4>Tire</h4>
                                    </div>
                            </div></a>
                    <div class="service-column text-center">
                        <a href="{{route('step_2',['type'=>'tow'])}}" class="wait2">
                            @if($type == 'tow')
                                <div class="service-repeater service-active">
                                    @else
                                        <div class="service-repeater">

                                            @endif
                                            <div class="service-img">
                                                <img src="{{asset('public/assets/images/icon3.svg')}}"/>
                                            </div>
                                            <h4>Tow</h4>
                                        </div>
                                </div></a>
                        <div class="service-column text-center">
                            <a href="{{route('step_2',['type'=>'lockout'])}}" class="wait2">
                                @if($type == 'lockout')
                                    <div class="service-repeater service-active">
                                        @else
                                            <div class="service-repeater">

                                                @endif
                                                <div class="service-img">
                                                    <img src="{{asset('public/assets/images/icon4.svg')}}"/>
                                                </div>
                                                <h4>Lockout</h4>
                                            </div>
                                    </div></a>
                            <div class="service-column text-center">
                                <a href="{{route('step_2',['type'=>'fuel'])}}" class="wait2">
                                    @if($type == 'fuel')
                                        <div class="service-repeater service-active">
                                            @else
                                                <div class="service-repeater">

                                                    @endif
                                                    <div class="service-img">
                                                        <img src="{{asset('public/assets/images/icon5.svg')}}"/>
                                                    </div>
                                                    <h4>Fuel</h4>
                                                </div>
                                        </div></a>
                                <div class="service-column text-center">
                                    <a href="{{route('step_2',['type'=>'winch'])}}" class="wait2">
                                        @if($type == 'winch')
                                            <div class="service-repeater service-active">
                                                @else
                                                    <div class="service-repeater">

                                                        @endif
                                                        <div class="service-img">
                                                            <img src="{{asset('public/assets/images/icon6.svg')}}"/>
                                                        </div>
                                                        <h4>Winch</h4>
                                                    </div>
                                            </div></a>

                                </div>
                            </div>


</section>
<div class="preloader preloader-imgs" id="preloader" style="display:none; z-index: 99999;">
    <img src="{{asset('/public/assets/images/preloader.gif')}}" style="width:10%;"/>
    <p>Searching for the closest roadside provider...</p>
</div>


<script type="module">
    $(".wait2").click(function(e){
        e.preventDefault();
         document.getElementById("preloader").style.display = "flex";
        if(this.href) {
            var target = this.href;
            setTimeout(function(){
                window.location = target;
            }, 3000);
        }
    });
</script>
