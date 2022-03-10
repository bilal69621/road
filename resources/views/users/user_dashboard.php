<?php include'includes/header.php'; ?>
<?php include'includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <?php include resource_path('views/admin/include/messages.php'); ?>
        <div class="alert alert-success alert-dismissible">
                <h1> Step 1! </h1>
                Register Yourself <i class="icon fa fa-check" style="font-size:48px;"></i>
              </div>
         <div class="alert alert-success alert-dismissible">
                <h1> Step 2!</h1>
                Pay For MemberShip <i class="icon fa fa-check" style="font-size:48px;"></i>
              </div>
         <div class="alert alert-danger alert-dismissible">
                <h1> Step 3! </h1>
                <a target="_blank" href= "https://www.driveroadside.com">Download/Use Application </a><i class="fa fa-close" style="font-size:48px;"></i>
              </div>

        <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
</div>

<?php include'includes/footer.php'; ?>
