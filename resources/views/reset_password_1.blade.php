<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>RoadSide | Reset Password</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= asset('public/bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= asset('public/bower_components/font-awesome/css/font-awesome.min.css')?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= asset('public/bower_components/Ionicons/css/ionicons.min.css')?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('public/dist/css/AdminLTE.min.css')?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= asset('public/plugins/iCheck/square/blue.css')?>">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <?php if(isset($stripe_detail) && !empty($stripe_detail) && isset($user) && !empty($user)){ ?>
    <script src="https://script.tapfiliate.com/tapfiliate.js" type="text/javascript" async></script>
    <script type="text/javascript">
        (function(t,a,p){t.TapfiliateObject=a;t[a]=t[a]||function(){
            (t[a].q=t[a].q||[]).push(arguments)}})(window,'tap');

        tap('create', '11066-c05a86', { integration: "stripe" });
        tap('conversion', '<?=$stripe_detail->stripe_id?>', <?=$amount?>, {customer_id: '<?=$user->stripe_id?>'});

    </script>

    <?php } ?>
</head>
<style>
    .error{
        color:blue;
    }
    .banner-overlay {
        position: absolute;
        top: 0;
        left:0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
    }
    .banner-section {
        height: 100vh;
    }
    .banner-section video {
        object-fit: cover;
    }
    .forget-id {
        text-align: right;
        color: #3c8dbc;
        font-weight: bold;
        margin-bottom: 20px;
        text-decoration: none;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .remember-id input {
        margin-top: 0;
        margin-right: 5px;
    }
    .remember-id {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .remember-id label {
        margin: 0;
    }
</style>

<?php
$plan = '';
$ref = '';
if (isset($_GET['plan']) && !empty($_GET['plan'])) {
    $plan = $_GET['plan'];
}
if (isset($_GET['ref']) && !empty($_GET['ref'])) {
    $ref = $_GET['ref'];
}
?>
<body class="hold-transition login-page">

<section class="banner-section">
    <video width="100%" height="100%" autoplay="" muted="" loop="">
        <source src="http://localhost/roadsideapi/public/template/video/banner-video.mp4" type="video/mp4">
    </video>
    <div class="banner-overlay">
        <div class="container">
            <div class="login-box">
                <div class="login-logo">
                <!--<img src="<?= asset('public/image/logo.png')?>" width="100px"></img>-->
                    <a href="<?= route('home') ?>">Drive <b>Roadside</b></a>

                </div>
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <?php if(isset($success) && !empty($success)){ ?>
                    <h3>Successfully Subscribed.</h3> <!-- You have Successfully Subscribed for the <?php // $plan?> Membership-->
                    <?php } ?>
                    <p class="login-box-msg">Reset Password</p>

                        <form method="get" id="passwordForm" action="{{ asset('/reset') }}">
                            @csrf

                            <?php //if (Session::has('success')) { ?>
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times</a>
                                    <?php echo Session::get('success') ?>
                                </div>
                                <?php// } ?>
                            @elseif(session()->has('error'))
                                <?php // if (Session::has('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times</a>
                                    <?php echo Session::get('error') ?>
                                </div>
                            @endif
                            <?php // } ?>
                            <?php //if ($errors->any()) { ?>
                            @if( count( $errors ) > 0 )
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <?php //} ?>
                            @endif
                            <input type="password" class="input-lg form-control" name="password" id="password1" placeholder="New Password" autocomplete="off">
                            <br>
                            <div class="row">
                            </div>
                            <input type="password" class="input-lg form-control" name="password_confirmation" id="password2" placeholder="Repeat Password" autocomplete="off">
                            <div class="row">
                            </div>
                            <input type="hidden" name="token" id="passtoken" value="">
                            <br>
                            <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" value="Change Password">
                            <br>
                            <br>
                        </form>
                    <!-- /.social-auth-links -->
                </div>
                <!-- /.login-box-body -->
            </div>
        </div>
    </div>
</section>



<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?= asset('public/bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= asset('public/bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>

<script>
    var url = window.location.href;
    var array = url.split("/");
    var num = array.length;
    $('#passtoken').val(array[num-1]);
</script>
</body>
</html>
