@include('layouts/head')
<body>
@include('layouts/header')
<main>
      <div class="dashboard-container">
           <div class="dashboard-sidebar">

               <div class="sidebar-leftbar">
                    <section class="leftbar-container">
                        <div class="main-title">
                            <h3>Payment</h3>
                        </div>
                        <div class="grey-container">
                            <div class="payment-row">
                                @if($cards)
                              @foreach($cards as $card)
                                <div class="payment-repeater" id="card_{{$card->id}}">
                                    <div class="payment-image">
                                         @if($card->brand == 'visa')
                                        <img src="{{asset('public/assets/images/visa.svg')}}"/>
                                        @else
                                        <img src="{{asset('public/assets/images/visa.svg')}}"/>
                                        @endif
                                    </div>
                                    <div class="payment-name">
                                        <h4>{{$card->last4}}</h4>
                                        <p>Expires {{$card->exp_month}}/{{$card->exp_year}}</p>
                                    </div>
                                    <div class="payment-option">
                                        <img src="{{asset('public/assets/images/delete-circle.svg')}}" onclick="DeleteCardComfermation({{$card->id}})"/>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>nothing to show</p>
                                @endif
                                <div class="payment-repeater">
                                    <button class="car-column" data-toggle='modal' data-target="#addcard">
                                    <div class="payment-image">
                                        <img src="{{asset('public/assets/images/add-card.svg')}}"/>
                                    </div>
                                    <div class="payment-name">
                                        <h4>Add Card</h4>
                                    </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                   <form action="#" method="POST" id="deleteCardForm">
                       @csrf
                       <input type="hidden" name="cardId" id="cardId">
                   </form>
               </div>
           </div>
       </div>
</main>

@include('popups/addcard')
@include('layouts/sidebar')
@include('layouts/footer')

</body>
</html><!-- comment -->

