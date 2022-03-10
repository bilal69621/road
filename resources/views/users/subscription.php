<?php use Carbon\Carbon;

include'includes/header.php'; ?>
<?php include'includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subscriptions
            <small>Drive RoadSide</small>
        </h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Subscriptions Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <?php if (empty($subscription->getSubscription)) { ?>
                        <a href="<?= asset('add_subscription_view'); ?>" class="btn btn-success "><b>Add subscription</b></a>
                    <?php } ?>
                    <?php if (isset($subscription->getSubscription)) { ?>
                        <div class="row">
                            <div class='col-md-4 col-sm-12'></div>

                            <div class="col-md-4 col-sm-12">

                                <!-- Profile Image -->
                                <div class="box box-primary">
                                    <div class="box-body box-profile">
                                        <img class="profile-user-img img-responsive img-circle" src="<?= asset('public/images/admin/profile_pic/demo.png') ?>">


                                        <?php if ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) { ?>
                                            <h3 class="profile-username text-center">Monthly Plan</h3>
                                        <?php } elseif ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) { ?>
                                            <h3 class="profile-username text-center">1 Year Membership (Family Plan)</h3>
                                        <?php } elseif ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) { ?>
                                            <h3 class="profile-username text-center">1 Year Membership</h3>
                                        <?php } elseif ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) { ?>
                                            <h3 class="profile-username text-center">6 Months Membership</h3>
                                            <?php } elseif ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) { ?>
                                            <h3 class="profile-username text-center">1 Month Membership</h3>
                                        <?php }
                                        ?>


                                        <ul class="list-group list-group-unbordered">

                                            <li class="list-group-item">
                                                <b>Plan</b> <a class="pull-right"> <?php
                                    if ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
                                        echo "Monthly";
                                    } else if ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
                                        echo "1 Year +";
                                    } else if ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
                                        echo "1 Year +";
                                    } elseif ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
                                        echo "6 Months";
                                    } else if ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) {
                                        echo '6 Months';
                                    }
                                        ?></a>
                                            </li>
                                            <?php
                                            $payments = $subscription->getPaymnet;
                                            $total = 0;
                                            foreach ($payments as $payment) {
                                                if($payment->charge_id == $subscription->getSubscription->stripe_id ){
                                                    $total = $payment->amount;
                                                }
                                            }
                                            ?>
                                            <li class="list-group-item">
                                                <b>Cost</b> <a class="pull-right">$ <?php if($total == 0) { echo '9.99 Monthly - $ 29.99 at SignUp';} else {echo $total / 100;} ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Subscription End</b> <a class="pull-right"><?=$subscription->getSubscription->ends_at; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Roadside Event Available</b> <a class="pull-right"><?= $subscription->getSubscription->counter; ?></a>
                                            </li>
                                            <?php
                                            if ($subscription->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) { ?>
                                            <li class="list-group-item">
                                                <b>Next trip will be available at </b> <a class="pull-right">
                                                    <?php
                                                        $subscription_date = $subscription->getSubscription->created_at;
                                                        $trip_date = $subscription->getSubscription->updated_at;

                                                        $first_span_end_date = Carbon::parse($subscription_date)->addMonths(3);
                                                        $second_span_end_date = Carbon::parse($first_span_end_date)->addMonths(3);
                                                        $third_span_end_date = Carbon::parse($second_span_end_date)->addMonths(3);
                                                        $forth_span_end_date = Carbon::parse($third_span_end_date)->addMonths(3);

                                                        // if($trip_date < $first_span_end_date){
                                                        //   $next_trip_avaliable_after = Carbon::parse($first_span_end_date)->addDay(1);
                                                        //}
                                                        //if($trip_date < $second_span_end_date){
                                                        //  $next_trip_avaliable_after = Carbon::parse($second_span_end_date)->addDay(1);
                                                        // }
                                                        //if($trip_date < $third_span_end_date){
                                                        //  $next_trip_avaliable_after = Carbon::parse($third_span_end_date)->addDay(1);
                                                        //}
                                                        //if($trip_date < $forth_span_end_date){
                                                        //  $next_trip_avaliable_after = Carbon::parse($forth_span_end_date)->addDay(1);
                                                        //}

                                                        if($trip_date < $first_span_end_date){
                                                            $next_trip_avaliable_after = $first_span_end_date;
                                                        }
                                                        if($trip_date < $second_span_end_date){
                                                            $next_trip_avaliable_after = $second_span_end_date;
                                                        }
                                                        if($trip_date < $third_span_end_date){
                                                            $next_trip_avaliable_after = $third_span_end_date;
                                                        }
                                                        if($trip_date < $forth_span_end_date){
                                                            $next_trip_avaliable_after = $forth_span_end_date;
                                                        }
                                                        echo $next_trip_avaliable_after;
                                                    }
                                                    ?>
                                                </a>
                                            </li>
<!--                                            <li class="list-group-item">
                                                <b>Miles Covered</b> <a class="pull-right"><?php // ($subscription->getSubscription->miles_covered) ? $subscription->getSubscription->miles_covered : 0; ?> </a>
                                            </li>-->
                                            <li class="list-group-item">
                                                <b>Miles Per Event</b> <a class="pull-right"><?= (isset($subscription->getSubscription->total_miles)) ? $subscription->getSubscription->total_miles : 0; ?> </a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Status</b> <a class="pull-right"><?=($subscription->getSubscription->status == '1')? 'Active' : 'InActive' ?></a>
                                            </li>

                                        </ul>
                                        <?php if (!isset(Auth::user()->linked_id)) { ?>
                                            <a href="<?= asset('cancel_subscription'); ?>" class="btn btn-danger btn-block"><b>Cancel</b></a>
                                        <?php }  ?>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>





<?php include'includes/footer.php'; ?>

<script>
    $(function () {
        $('#example1').DataTable({
            'responsive': true
        })
        $('#example2').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'responsive': true
        })
    })
    function cancelSub(id)
    {
        if (confirm('Are you sure you want to cancel this ?')) {
            console.log(id);
            $.ajax({
                url: '<?= asset('cancel_sub'); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": "<?= csrf_token() ?>",
                    "id": id
                },
                success: function (data) {
                    if (data == 1) {
                        location.reload();
                    }

                }

            });
        } else {
            // Do nothing!
        }


    }



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
                }else{
                    location.reload();
                }
            }
        });
    })
</script>
