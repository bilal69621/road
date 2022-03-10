<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Drive Roadside | Registration Page</title>
        <meta charset="UTF-8" name="description" content='Sign up today for our roadside assistance monthly plan for just $9.99. Our roadside assistance technicians are available 24/7 in all states'>

        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?= asset('public/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?= asset('public/bower_components/Ionicons/css/ionicons.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= asset('public/dist/css/AdminLTE.min.css') ?>">
        <link rel="stylesheet" href="<?= asset('public/template/css/all.css') ?>">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link
     rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
   />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <script src="https://script.tapfiliate.com/tapfiliate.js" type="text/javascript" async></script>
        <script type="text/javascript">
            (function(t,a,p){t.TapfiliateObject=a;t[a]=t[a]||function(){
            (t[a].q=t[a].q||[]).push(arguments)}})(window,'tap');

            tap('create', '11066-c05a86', { integration: "stripe" });
            tap('detect');
        </script>
    </head>
    <style>
        .error{
            color:blue;
        }
        .login-box-msg{
            font-size: 16px;
            color: #2481bc;
            font-weight: 600;
        }
        .register-box{
            margin: 2% auto;
        }
        .register-logo{
            margin-left: -28px;
        }
        .register-logo a{
            margin-left: -17px;
        }
        .flex-class{
            display: flex;
        }
        .flex-class .form-group:first-child{
            display: flex;
        }
        .flex-class .form-group:nth-child(2){
            display: flex;
            align-items: center;
        }
        .form-group input{
            margin-right: 8px;
        }
        .form-group label{
            margin-right: 7px;
            margin-bottom: 0;
        }
        .login-box-msg{
            padding: 4px 15px;
        }
        .login-box-msg1{
            padding: 0px 10px 20px 10px;
        }
        .btn-flat.register{
            margin-top: 10px;
        }
        .form-group span{
            display: block;
        }
        .register-logo a {
            margin-left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .register-logo a img {
            width: 80px;
        }
        .register-logo {
            margin-left: 0;
        }
        .error {
            font-weight: 400;
        }
        .cus_flex{
            display: flex;
            flex-direction: column;
        }
        .flex-class .form-control.error{
            border-color: blue;
        }
        .has-feedback label~.form-control-feedback{
            top: 0;
        }
        .res_col,
        .res_col button{
            width: 100%;
        }
        .store-content.register {
            display: flex;
            justify-content: center;
            background-color: #131313;
            padding: 40px 10px;
            margin-top: 20px;
        }
        .store-content.flex.register a {
            margin-right: 50px;
        }
        html,body {
            height: unset;
        }
        html {
            background-color: #fff;
        }
        .register-box {
            margin: 0 auto;
            padding-top: 20px;
            min-height: calc(100vh - 119px);
        }

        @media screen and (max-width: 1400px) {
            .register-box {
                min-height: unset;
                margin-bottom: 50px;
            }
        }
        @media screen and (max-width: 767px) {
            .store-content.flex.register a {
                margin-right: 25px;
            }
            .store-content.flex.register a:last-of-type {
                margin-right: 0;
            }
            .register-logo a {
                font-size: 16px;
                margin-left: 0;
                text-align: center;
            }
        }

        header#registeration {
    top: 0;
    padding: 10px 0;
    position: relative;
    background-color: rgba(0, 0, 0, 0.7)
}
header#registeration nav ul li.active a {
    color: #ef9a00;
}
header#registeration nav {
    align-items: center;
    justify-content: center;
    justify-content: space-between;
}
.site-menu ul li a {
    padding: 0 20px;
}
.site-logo {
    width: 100px;
}
.registeration-section {
    margin: 30px 0;
}
.registeration-header p {
    font-size: 18px;
    margin-bottom: 4px;
}
.registeration-header h5 {
    font-size: 20px;
    font-weight: bold;
}
.register-content {
    margin-top: 30px;
}
.register-form,
.register-card {
    width: 49%;
}
.register-card {
    height: 100%;
}
.register-card .billing-option {
    width: 100%;
}
.site-menu ul {
        display: flex;
        padding: 20px 0;
}
header#registeration nav ul li a {
    font-size: 13px;
}
.register-box {
    width: 60%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin: 0 auto;
}
.register-logo {
    width: 100%;
}
.register-box-body {
    width: 45%;
}
.register-card {
    width: 50%;
}
.registeration-heading {
    width: 100%;
    margin-bottom: 20px;
    text-align: center;
}
.registeration-heading h2 {
    font-size: 20px;
    letter-spacing: 1px;
    font-weight: bold;
}
.mobile-toggler1 {
    display: none;
}
.mobile-toggler1 span {
    height: 2px;
    width: 40px;
    background-color: #fff;
    margin-bottom: 13px;
    display: block;
}
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance:textfield;
}
@media screen and (max-width: 1024px) {
    .register-card {
        margin-top: 20px;
    }
    header#registeration {
        height: 92px;
    }
}
@media screen and (max-width: 991px) {
    .mobile-toggler1 {
        top: 35px;
    }
    .register-form {
        margin-bottom: 40px;
    }
    .register-form,.register-card {
        width: 100%;
    }
}

@media screen and (max-width: 767px) {
    .register-card .billing-option {
        width: 100%;
    }
    .mobile-toggler1 {
        display: block;
    }
   #registeration .site-menu {
    position: absolute;
    display: unset;
    background-color: #333;
    width: 100%;
    padding: 20px 0;
    display: none;
    width: 100%;
    top: 90px;
    left: 0;
    display: none;
}
header nav ul {
    display: unset !important;
}
header nav ul li {
    margin: 10px 0;
}
.register-box-body {
    width: 95%;
}
.register-card {
    width: 95%;
}

}
    </style>
    <?php
    $plan = '';
    $ref = '';
    $plan_name = '';
    $price = '';
    if (isset($_GET['plan']) && !empty($_GET['plan'])) {
        $plan = $_GET['plan'];
        if($plan == '10MilesPlusYear'){
            $price = '$99.99';
            $plan_name = '1 Year Membership';
        } else if($plan == '10MilesYear'){
            $price = '$59.99';
            $plan_name = '6 Month Membership';
        } else if($plan == '6Months'){
            $price = '$9.99';
            $plan_name = '1 Month Membership';
        }else if($plan == '10MilesMonthlyPlan'){
            $price = '$9.99';
            $plan_name = 'Monthly Membership';
        }else if($plan == '10MilesPlusYearFamilyPlan'){
            $price = '$149.99';
            $plan_name = '1 Year Family Membership';
        }
    } else {
        $price = '$9.99';
            $plan_name = '1 Month Membership';
    }
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $ref = $_GET['ref'];
    }
    ?>
    <body class="hold-transition register-page">
        <header id="registeration">
        <div class="container">
            <nav class="flex">
                <div class="site-logo">
                    <a href="<?= asset('/') ?>"><img src="<?= config('app.asset_template') ?>/images/drive.png" alt=""></a>
                </div>
                <div class="site-menu">
                    <ul>
                        <li class="active"><a href="<?= asset('/') ?>">HOME</a></li>
                        <li><a href="<?= asset('/about') ?>">ABOUT US</a></li>
                        <li><a href="<?= asset('/membership') ?>">MEMBERSHIP</a></li>
                        <li><a href="<?= asset('/partnership') ?>">PARTNERSHIP</a></li>
                    </ul>
                </div>
                <div class="mobile-toggler1">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>
        <div class="register-box">
            <div class="register-logo">
<!--                <a href="#"><img src="https://i.ibb.co/ZY5GT5d/Mobile-Roadside-Logo.png" alt="Mobile-Roadside-Logo"></a>-->
                <a href="<?=route('home')?>?ref=<?=$ref?>&plan=<?=$plan?>">DRIVE | Roadside</b></a>
            </div>
<!--<div class="registeration-heading">
                <h5>Register for a new</h5>
                <h2><?=$plan_name?></h2>
            </div>-->
            <div class="register-box-body">
                <p class="login-box-msg login-box-msg1">Register For a New <?= ucwords($plan_name) ?></p>

                <form id="form" action="<?= asset('register_membership') ?>" method ="Post">
                    <?php include resource_path('views/admin/include/messages.php'); ?>
                    <?= csrf_field() ?>
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Full name">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" id='password' class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="c_password" class="form-control" placeholder="Retype password">
                        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="hidden" name="contact_number" id="contact_number" class="form-control" placeholder="Contact">
                        <input style="width: 129%;" type="text" name="contact_number_f" id="contact_number_f" class="form-control" placeholder="Contact">
                        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" id="address" name="address" class="form-control" placeholder="Street Address">
                        <span class="glyphicon  form-control-feedback"></span>
                    </div>
                    <div class="field-row">
                        <div class="form-group has-feedback">
                            <input type="text" id="city" name="city" class="form-control" placeholder="City">
                            <!--<span class="glyphicon glyphicon-phone form-control-feedback"></span>-->
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="state" name="state" class="form-control" placeholder="State">
                            <!--<span class="glyphicon glyphicon-phone form-control-feedback"></span>-->
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="zipcode" name="zipcode" class="form-control" placeholder="Zip Code">
                            <!--<span class="glyphicon glyphicon-phone form-control-feedback"></span>-->
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="country" name="country" class="form-control" placeholder="Country">
<!--                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>-->
                        </div>
                    </div>
                    <p class="login-box-msg">Payment Info</p>
                    <div class="form-group">
                        <span style="color:red" class="payment-errors"></span>
                        <label>Card Number</label>
                        <input name="cardnumber" class="form-control general-field card-field" size="16" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 16)
                                    return false;" data-stripe="number" type="number" placeholder="4242 4242 4242 4242">
                        <input type="hidden" name="plan" value="<?=$plan?>" id="choose_plan">
                    </div>
                    <div class="form-group">
                        <label>Card Holder</label>
                        <input type="text" class="form-control general-field " name="cardholdername" placeholder="Card holder name">
                    </div>
                    <div class="cart-holder">
                        <label>Exp.Date</label>
                        <div class="flex-class">
                            <div class="form-group ">
                                <div class="cus_flex">
                                    <input size="2" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 2)
                                            return false;" data-stripe="exp-month" name="month" type="number" class="form-control" placeholder="Month" />
                                </div>
                                <input size="4" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 4)
                                            return false;" data-stripe="exp-year" type="number" name="year" class="form-control" placeholder="Year" />
                            </div>
                            <div class="form-group ">
                                <label>CVC</label>
                                <input  size="3" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 3)
                                            return false;" data-stripe="cvc"  type="number" name="cvc" class="form-control general-field" placeholder="CVC">
                            </div>
                        </div>
                    </div>
                    <!--                                <div class="credit-cart-save">
                                                        <button class="btn btn_grey">Save</button>
                                                    </div>-->
                    <div class="row">
                        <div class="col-xs-8" id="coupontest">

                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4 res_col">
                           <button type="button" class="btn btn-success btn-block btn-flat register" id='register_coupon'>Apply Coupon & Signup</button>
                            <button type="submit" class="btn btn-primary btn-block btn-flat register">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!--    <div class="social-auth-links text-center">
                      <p>- OR -</p>
                      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using
                        Facebook</a>
                      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using
                        Google+</a>
                    </div>-->

                <!--    <a href="login.html" class="text-center">I already have a membership</a>-->
            </div>
            <!-- /.form-box -->

            <div class="modal custom-modal" tabindex="-1" role="dialog" id="register_coupon" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="custom-radio ">
                        <h2>Coupon system</h2>
                    </div>
                    <div class="modal-body">
                        <form id="addCarForm" action="#" method="POST">
                            <div class="modal-steps-form step-active">
                                <div class="step-main-repeater ">
                                    <div class="custom-radio ">
                                     <input type="text" class="form-control" name="register_coupon" id="coupon">
                                    </div>
                                    <br>
                                    <div class="custom-radio">
                                        <button type="button" class="btn btn-next" id="check">check coupon</button>
                                    </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
        </div>
        </div>

            <div class="register-card">
                    <div class="billing-option option2 dropshadow">
                        <div class="billing-images flex">
                            <img src="<?= config('app.asset_template') ?>/images/stars.png" alt="">
                            <img src="<?= config('app.asset_template') ?>/images/ride-option.png" alt="" class="logos-imgs">
                        </div>
                        <h3><?=$price?> <span> AUTOPAY</span></h3>
                        <h5><?=$plan_name?></h5>

                    </div>
                </div>
        </div>
        <!-- /.register-box -->
        <div class="store-content flex register">
            <a href="https://apps.apple.com/us/app/roadside-assistance/id1483339220?ls=1"><img src="<?= config('app.asset_template') ?>/images/app-store.png" alt=""></a>
            <a href="https://play.google.com/store/apps/details?id=com.codingpixel.dev.roadside"><img src="<?= config('app.asset_template') ?>/images/play-store.png" alt=""></a>
        </div>

        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <!-- jQuery 3 -->
        <script src="<?= asset('public/bower_components/jquery/dist/jquery.min.js') ?>"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="<?= asset('public/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
        <!-- iCheck -->
        <!--<script src="../../plugins/iCheck/icheck.min.js"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
         <script>
        const phoneInputField = document.querySelector("#contact_number_f");
        const phoneInput = window.intlTelInput(phoneInputField, {
         preferredCountries: "us",
         utilsScript:
           "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
       });

 </script>
        <script>

            $('#register_coupon').on('click',function ()
            {
                // $('#register_coupon').modal('hide');
                // $coupon = $('#coupon').val();
             $html = ` <div class="form-group has-feedback">
                    <input type="text" id="coupon" name="coupon" class="form-control" placeholder="BW7XOO">
                        <span class="glyphicon  form-control-feedback"></span>
                </div>`;
                   $('#coupontest').html($html);
                   $('#register_coupon').prop('disabled',true);
                   // $('#couponValue').val($coupon);
                   // $('#form').submit();
            }
            );




            Stripe.setPublishableKey('pk_test_EbSWPS8eAbEXdAq34Dj65NEa00cYn94Vo7');
            jQuery(function () {

                $('#form').submit(function (event) {
                    event.preventDefault();
                    $('#contact_number').val(phoneInput.getNumber());
                    var $form = $(this);

                    $this = $(this);

                    //Form validation
                    $form.validate({
                        rules: {
                            name: {
                                required: true,
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            password: {
                                required: true,
                                minlength: 6
                            },
                            c_password: {
                                required: true,
                                minlength: 6,
                                equalTo: "#password"

                            }, contact_number: {
                                required: true,
                            }, cardnumber: {
                                required: true,
                            }, cardholdername: {
                                required: true,
                            }, cvc: {
                                required: true,
                            }, month: {
                                required: true,
                            }, year: {
                                required: true,
                            }, address: {
                                required: true,
                            }
                        }
                        , messages: {
                            name: {
                                required: "Enter Full name",
                            },
                            password: {
                                required: "",
                                minlength: "Your password must be at least 6 characters long"
                            },
                            email: {
                                required: "Enter email",
                                email: ""
                            },
                            c_password: {
                                required: "",
                                equalTo: "Please enter the same password as above"
                            },
                            contact_number: {
                                required: "Enter Contact Number",
                            }, cvc: {
                                required: "",
                            }, month: {
                                required: "",
                                range: [1, 12]
                            }, year: {
                                required: "",
                            }, address: {
                                required: "",
                            }

                        }, submitHandler: function (form) {



                        }

                    });

                    // Disable the submit button to prevent repeated clicks

                    $form.find('button').prop('disabled', true);


                    Stripe.card.createToken($form, stripeResponseHandler);


                    // Prevent the form from submitting with the default action
//                                                    return false;
                });
            });
            function stripeResponseHandler(status, response) {
                var $form = $('#form');

                if (response.error) {
                    // Show the errors on the form
                    $form.find('.payment-errors').text(response.error.message);
                    $form.find('button').prop('disabled', false);

                } else {
                    // response contains id and card, which contains additional card details
                    //
//                                                    var token = response.id;
                    // Insert the token into the form so it gets submitted to the server
//                                                    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
//                                                    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
//
                    $form.get(0).submit();
                }
            }
$('.mobile-toggler1').click(function(){
    $('.site-menu').slideToggle();
});
</script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhMoC9OLQs1fxPJkPWxdgC9dui6pIKQoA&libraries=places&callback=initAutocomplete" async defer></script>
        <script>
            var address1;
            function initAutocomplete() {
                var options = {
                    types: []
                };

                address1 = new google.maps.places.Autocomplete(document.getElementById('address'), options);
                address1.setFields(['address_component', 'geometry']);
                address1.addListener('place_changed', fillInAddress1);
            }
            function fillInAddress1() {
                var place = address1.getPlace();
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
        </script>

    </body>
</html>
