@include('layouts/head')
<body>
@include('layouts/header')
<main>
    <div class="dashboard-container">
           <div class="dashboard-sidebar">
               <div class="sidebar-leftbar">
                    <section class="leftbar-container">
                        <div class="main-title">
                            <h3>Membership Plans</h3>

                        </div>
                        <div class="memebership-row">
                            @foreach($plans as $plan)
                                <div class="memebership-column">
                                    @if($plan->interval_count == '1')
                                    <div class="membership-innerColum" style="background-color: green">
                                    @else
                                    <div class="membership-innerColum">
                                    @endif
                                    <label>
                                        <div class="membership-head">

                                            <h5>{{$plan->interval_count}} {{$plan->interval}} MEMBERSHIP</h5>
                                        </div>
                                        <div class="membership-body">
                                            <h4><sup>$</sup>{{$plan->amount_decimal/100}}</h4>
                                            <p>Autopay Every {{$plan->interval_count}} {{$plan->interval}}</p>
                                              @if($plan->interval_count == '1')
                                           <h6>4 Roadside Events</h6>
                                            @else
                                            <h6>2 Roadside Events</h6>
                                            @endif
                                            <ul>
                                                <li>
                                                    SERVICES
                                                </li>
                                                <li>Jumpstart</li>
                                                <li>Locksmith</li>
                                                <li>Tire Change</li>
                                                <li>Fuel Delivery</li>
                                                <li>Towing</li>
                                            </ul>
                                        </div>
                                        <div class="membership-footer">
                                            @if(empty($userSub->getSubscription))
                                            @if(!empty($userSub->stripe_id))<!-- comment -->
                                            <a href="<?= asset('create_subscription_u/' .$plan->id ); ?>" class="btn btn-join"><b>Join now</b></a>
                                                <button onclick="couponcheck('<?= $plan->id ?>')" type="button" class="btn btn_md_green btn-join ">Apply Coupon</button>
                                            @else
                                            <button data-target="#payment" data-toggle="modal" type="button" class="btn btn_md_green third_next " id="third_next" value="{{$plan->id}}" >Join Now</button>
                                            @endif
                                            @elseif($userSub->getSubscription->stripe_plan == $plan->id )<!-- comment -->
                                            <b style="color: white;font-weight: 900">Status Active</b>
                                            @if($plan->interval_count == 6)
                                                    <a href="<?= asset('create_subscription_u/' .$plan->id ); ?>"  class="btn btn-join" style="background-color: #3C8DBC;border-color: #3C8DBC;"><b>Upgrade</b></a>
                                                    <button onclick="couponcheckupgrade('<?= $plan->id ?>')" type="button" class="btn btn_md_green btn-join ">Apply Coupon</button>
                                                @else
                                                    <a href="<?= asset('create_subscription_u/' .$plan->id ); ?>"  class="btn btn-join" style="background-color: #268001;border-color: #268001;"><b>Upgrade</b></a>
                                                    <button onclick="couponcheckupgrade('<?= $plan->id ?>')" type="button" class="btn btn_md_green btn-join ">Apply Coupon</button>
                                                @endif
                                                <a href="javascript:void(0)" id="cancelSub" class="btn btn-danger btn-join" style="background-color: red;border-color: red;"><b>Cancel</b></a>
                                            @else
                                                    <button onclick="couponcheckupgrade('<?= $plan->id ?>')" type="button" class="btn btn_md_green btn-join ">Apply Coupon</button>
                                            <a href="<?= asset('upgrade_subscription_u/' . $plan->id); ?>" class="btn btn-join"><b>Upgrade now</b></a>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @endforeach
                        </div>
                        <div class="full-width-text">
                            <p>Need immediate roadside assistance? Call Now!</p>
                            <a href="{{route('rescue')}}" class="btn btn-blue">Get Help</a>
                        </div>
                    </section>
               </div>
           </div>
       </div>
  <div class="modal" tabindex="-1" role="dialog" id="cancelSubsecription">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="step-form-title text-center">
            <h4>Report Service</h4>
        </div>
        <form method="POST" action="<?= asset('cancel_subscription_u/'); ?>">
            @csrf
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
            <button class="btn btn-next next_steps">Submit</button>
        </div>
        </form>
    </div>
  </div>
</div>

    <div class="modal fade" id="couponcheck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('create_subscription_coupon')}}" method="POST">
                    @csrf
                <div class="modal-body">
                    <input type="text" name="coupon" id="couponNumber" class="general-field" placeholder="Coupon" required>
                </div>
                <input type="hidden" name="plan_id" value="" id="plan_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="applycoupon">APPLY & BUY</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="couponcheckup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('create_subscription_coupon')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="coupon" id="couponNumberup" class="general-field" placeholder="Coupon" required>
                    </div>
                    <input type="hidden" name="plan_id" value="" id="plan_idup">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="applycouponup">APPLY & BUY</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@include('layouts/sidebar')
<script>
    $('#cancelSub').on('click',function(){
        $('#cancelSubsecription').modal('toggle');
    });

   function couponcheck($planId)
   {
       $('#plan_id').val($planId);
       $('#couponcheck').modal('show');
   }

   $('#applycoupon').on('click',function (){
       $('#applycoupon').prop('disabled', true);
     $coupon = $('#couponNumber').val();

     $plan_id = $('#plan_id').val()

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
           url: 'create_subscription_coupon',
           type:'post',
           data:{'coupon':$coupon,'plan_id':$plan_id,"_token": "{{ csrf_token() }}"},
           dataType: 'JSON',
           success: function(data){
               if(data.error == 1)
               {
                   Toast.fire({
                       icon: 'error',
                       title: data.message,
                   })
                   $('#applycoupon').prop('disabled', false);
               }else{
                  location.reload();
               }
           }
       });
   })

    function couponcheckupgrade($planId)
    {
        $('#plan_idup').val($planId);
        $('#couponcheckup').modal('show');
    }

    $('#applycouponup').on('click',function (){
        $('#applycouponup').prop('disabled', true);
        $coupon = $('#couponNumberup').val();

        $plan_id = $('#plan_idup').val()

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
            url: 'upgrade_subscription_coupon',
            type:'post',
            data:{'coupon':$coupon,'plan_id':$plan_id,"_token": "{{ csrf_token() }}"},
            dataType: 'JSON',
            success: function(data){
                if(data.error == 1)
                {
                    Toast.fire({
                        icon: 'error',
                        title: data.message,
                    })
                    $('#applycouponup').prop('disabled', false);
                }else{
                    location.reload();
                }
            }
        });
    })

</script>
@include('layouts/footer')

</body>
</html>

