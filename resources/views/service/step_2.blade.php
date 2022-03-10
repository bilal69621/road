@include('layouts/head')
<body>
    @if($user->name == 'Guest User')
    @include('layouts/guestuserheader')
    @else
    @include('layouts/main_header')
    @endif
<main class="page-wrap parent-main">
    <section class="account-area payment-section step1-section step2-payments  viewport-height">
        <div class="container">
            <div class="account-area-content">
                <h3 class="mb-5" style="color: #54F21F;">A roadside technician is ready to <br/>be dispatched to your location</h3>
                <form action="{{route('step_3')}}" method="post">
                    @csrf
                <h3>{{$type}} Service</h3>
                <div class="banners-titles">
                    <div class="pricing-area"><p>$</p><h4 class="price">{{$price->price}}</h4></div>
                    <!-- ${{$price->price}} -->
                    <input type="hidden" name="type" value="{{$type}}">

                    <input type="hidden" name="price" id="servicePrice" value="{{$price->price}}">
                    @if($type == "tow")<!-- comment -->
                    <p class="total-miles">+ $9/mile</p>
                    @endif
                </div>
                <button class="btn btn-transparent">GET HELP NOW</button>
                </form>
            </div>
        </div>
    </section>
    <!-- @include('main.services') -->

</main>
@include('layouts/main_sidebar')
@include('layouts/footer')
</body>
</html>

<script>
    import Index from "../../../public/bower_components/Flot/examples/zooming/index.html";
    export default {
        components: {Index}
    }
</script>
