<?php include'include/header.php'; ?>
<?php include'include/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subscriptions
            <small>Roadside</small>
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
                    <div class="box-body">
                        <div class="pull-right" >
                            <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#modal-default" style="margin-top: -40px;">Add Coupon</button>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">

                            <thead>
                            <tr>
                                <th>Sr#</th>
                                <th>Coupon</th>
                                <th>Valid Till</th>
                                <th>Discount</th>
                            </tr>

                            </thead>
                            <tbody>
                            <?php if(isset($coupons)){

                                $count = 0;
                                foreach($coupons as $Reason){
                                    ?>


                                    <?php /*
                 <tr>
                  <td><?=++$count;?></td>
                  <td><?= subscribed_user($subscription->id);       ?> </td>
                  <td><?=$subscription['plan']->nickname;?></td>
                  <td><?php printf("%.2f",$subscription['plan']->amount/100);?></td>
                  <td><?=date('F d, Y',$subscription->current_period_end);?></td>
                  <td><?= subscribed_mile_coverd($subscription->id) ? subscribed_mile_coverd($subscription->id)->miles_covered :'0.00' ;?></td>
                </tr>
                    */ ?>
                                    <tr>
                                        <td><?=++$count;?></td>
                                        <td><?= $Reason->name; ?></td>
                                        <td><?= date('d M Y',$Reason->valid); ?></td>
                                        <td><?= $Reason->discount; ?></td>
                                    </tr>
                                <?php }
                            } ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Sr#</th>
                                <th>name</th>
                                <th>Valid Till</th>
                                <th>Discount</th>

                            </tr>
                            </tfoot>
                        </table>
                    </div>
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
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Coupon</h4>
            </div>
            <div class="modal-body">
                <form id="form" action="<?= asset('creat_coupon') ?>" method ="Post">
                    <?php include resource_path('views/admin/include/messages.php'); ?>
                    <?= csrf_field() ?>
                    <p class="login-box-msg">Coupon</p>
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Coupon name" required>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="discount" class="form-control" placeholder="Discount IN % ON TOTAL AMOUNT " required>
                    </div>

                    <div class="form-group has-feedback">
                        <input type="date" name="valid_till_data" class="form-control" placeholder="Valid for Months" required>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 res_col">
                            <button type="submit" class="btn btn-primary btn-block btn-flat register">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<?php include'include/footer.php'; ?>

<script>

    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("valid_till_data")[0].setAttribute('min', today);


    $(function () {
        $('#example1').DataTable({
            'responsive'  : true
        })
        $('#example2').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'responsive'  : true
        })
    });

</script>
