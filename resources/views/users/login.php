<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>RoadSide | Log in</title>
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
  <!-- Global site tag (gtag.js) - Google Ads: 676743917 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-676743917"></script>
    <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-676743917');
    </script>
      <?php if(isset($stripe_detail) && !empty($stripe_detail) && isset($user) && !empty($user)){ ?>
        <script src="https://script.tapfiliate.com/tapfiliate.js" type="text/javascript" async></script>
        <script type="text/javascript">
              (function(t,a,p){t.TapfiliateObject=a;t[a]=t[a]||function(){
              (t[a].q=t[a].q||[]).push(arguments)}})(window,'tap');

              tap('create', '11066-c05a86', { integration: "stripe" });
              tap('conversion', '<?=$stripe_detail->stripe_id?>', <?=$amount?>, {customer_id: '<?=$user->stripe_id?>'});

        </script>

        <?php
            if ($plan == '10MilesPlusYear'){
              ?>
                  <!-- Event snippet for 1 Year Membership Purchase conversion page -->
                  <script> gtag('event', 'conversion', { 'send_to': 'AW-676743917/oWr7COjbj9ABEO2V2cIC', 'value': 99.99, 'currency': 'USD', 'transaction_id': '' }); </script>
                  <img id='_SHRSL_img_1' src='https://www.shareasale.com/sale.cfm?tracking=<?=$user->id?>&amount=99.99&merchantID=2635918&transtype=sale' width='1' height='1'>
                  <script src='https://www.dwin1.com/19038.js' type='text/javascript' defer='defer'></script>

              <?php
              } else if ($plan == '10MilesYear') {
              ?>
                  <!-- Event snippet for 6 Month Purchase conversion page -->
                  <script> gtag('event', 'conversion', { 'send_to': 'AW-676743917/AIxICMeS8s8BEO2V2cIC', 'value': 59.99, 'currency': 'USD', 'transaction_id': '' }); </script>
                 <img id='_SHRSL_img_1' src='https://www.shareasale.com/sale.cfm?tracking=<?=$user->id?>&amount=59.99&merchantID=2635918&transtype=sale' width='1' height='1'>
                  <script src='https://www.dwin1.com/19038.js' type='text/javascript' defer='defer'></script>
                  <?php
              }
          }
          ?>

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
            <source src="<?= asset('public/template/video/banner-video.mp4') ?>" type="video/mp4">
        </video>
        <div class="banner-overlay">
            <div class="container">
                <div class="login-box">
  <div class="login-logo">
      <!--<img src="<?= asset('public/image/logo.png')?>" width="100px"></img>-->
    <a href="<?= route('home') ?>">Drive | <b>Roadside</b></a>

  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
          <?php if(isset($success) && !empty($success)){ ?>
        <h3>Successfully Subscribed.</h3> <!-- You have Successfully Subscribed for the <?php // $plan?> Membership-->
    <?php } ?>
    <p class="login-box-msg">User Login</p>
      <div class="alert alert-danger sign-up-ajax-msg-danger" style="display: none">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times</a>
          <span class="sign-up-ajax-body-danger"></span>
      </div>
    <?php include resource_path('views/admin/include/messages.php'); ?>
    <form action="<?= asset('userpostlogin');?>" method="post">
        <?= csrf_field();?>
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Email" id="useremail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
<!--        <div class="forget-id">
            <a href="#">Forget user ID</a>
        </div>-->
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
        <div class="forget-id">
            <a href="<?=asset('forget-password'); ?> ">Forget password</a>
        </div>
        <div class="remember-id">
            <input type="checkbox" id="remember" /> <label for="remember">Remember My user ID</label>
        </div>
      <div class="row">
        <div class="col-xs-7">
          <div class="checkbox icheck">
            <label>

            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>

                <!--<a target="_blank" href= "<?php // echo asset('app_redirect_back')?>">Go back to your app</a>-->

    </form>
    <!-- /.social-auth-links -->
  </div>
  <!-- /.login-box-body -->
                    <div class="col-xs-12">
                        <a href="https://www.roadsidemembership.com/login" class="btn btn-primary btn-block btn-flat">Sign In as Partner</a>
                    </div>
</div>

            </div>
        </div>
    </section>



<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?= asset('public/bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= asset('public/bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>


</body>
</html>
