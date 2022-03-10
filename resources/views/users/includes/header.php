<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?= csrf_token() ?>" />
  <title><?= (isset($title) ? $title : 'Drive RoadSide | Admin') ; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/Ionicons/css/ionicons.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= asset('public/dist/css/AdminLTE.min.css')?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= asset('public/dist/css/skins/_all-skins.min.css')?>">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/morris.js/morris.css')?>">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/jvectormap/jquery-jvectormap.css')?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/bootstrap-daterangepicker/daterangepicker.css')?>">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?= asset('public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= asset('public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <link rel="stylesheet" href="http://cdn.datatables.net/responsive/1.0.0/css/dataTables.responsive.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <link rel="stylesheet" href="<?= asset('public/css/all.css')?>">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/png" href="{{asset('images/default/fav.png')}}"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        function delete_confirmation(event) {
            event.preventDefault();
            var urlToRedirect = event.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, remove it!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        window.location.href = urlToRedirect;
                    } else {
                        swal("Cancelled", "Your member is safe :)", "error");

                    }
                });
        }
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?= route('home') ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">Drive <b>Roadside</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Drive <b>Roadside</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
       <?php $admin = Illuminate\Support\Facades\Auth::user(); ?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!--<img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                <span class=""><?=$admin->name?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->

                <p>
                  <?= $admin->name;?>
                    <small><?php
                           $date =  \Carbon\Carbon::now();
                                   echo date_format($date,"d-m-Y"); ?></small>

                <div class="row">
                    <?php
                    $img = $admin->avatar ;
                    if($img){
                        $img = asset('public/svg/'.$admin->avatar );
                    }else{
                        $img = asset('public/images/default/user_icon.png');
                    }
                    ?>
                    <img src="<?= $img ?>" width="80px" class="img-circle"  >

                </div>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">

                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?= asset('edit_user_profile')?>" class="btn btn-primary btn-flat">Edit Profile</a>
                </div>
                  <div class="pull-right">
                    <a href="<?= asset('logout_user')?>" class="btn btn-danger btn-flat">Sign out</a>
                </div>

              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class=""></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
