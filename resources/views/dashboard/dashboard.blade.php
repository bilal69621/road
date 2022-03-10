@include('layouts/head')
<body>
@include('layouts/header')
<main>
    <div class="dashboard-container">
        <div class="dashboard-sidebar">

            <div class="sidebar-leftbar">
                <section class="leftbar-container">
                    <div class="main-title">
                        <h3>My Cars</h3>
                    </div>
                    <div class="car-repeater-row">
                        @foreach($cars as $car)
                        <div class="car-column" id="carDiv_{{$car->id}}">
                            <div class="car-innerColumn text-center">
                                <img src="{{asset('public/assets/images/car.svg')}}"/>
                                <h5>{{$car->name}}</h5>
                                <p>{{$car->make}}-{{$car->year}}</p>
                                <div class="delete-icons">
                                    <input type="hidden" id="car_{{$car->id }}"/>
                                    <img src="{{asset('public/assets/images/delete-icons.svg')}}" onclick="archiveFunction({{$car->id}})"/>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <button class="car-column" data-toggle='modal' data-target="#add_car">
                                <div class="car-innerColumn text-center">
                                    <img src="{{asset('public/assets/images/add_Car.svg')}}"/>
                                    <h5>Add Car</h5>
                                </div>
                            </button>
                    </div>
                </section>

                <form action="#" method="POST" id="deletecarForm">
                    @csrf
                    <input type="hidden" name="carId" id="carId">
                </form>
            </div>
        </div>
    </div>
    @include('popups/addcar')

</main>
@include('layouts/sidebar')
@include('layouts/footer')
</body>
</html>
