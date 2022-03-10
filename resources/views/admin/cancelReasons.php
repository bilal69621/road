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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr#</th>
                  <th>User Name</th>
                  <th>User Email</th>
                  <th>User Phone</th>
                  <th>Reason</th>
                  <th>Detail</th>
                  <th>Cancel Date</th>
                </tr>

                </thead>
                <tbody>
                    <?php if(isset($Reasons)){

                        $count = 0;
                        foreach($Reasons as $Reason){
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
                  <td><?= $Reason->user->name; ?></td>
                  <td><?= $Reason->user->email; ?></td>
                  <td><?= $Reason->user->contact_number; ?></td>
                  <td><?= $Reason->reason;?> </td>
                  <td><?= $Reason->detail_reason;?> </td>
                  <td><?= $Reason->created_at;?> </td>

                </tr>
                        <?php }
                        } ?>

                </tbody>
                <tfoot>
                <tr>
                    <th>Sr#</th>
                  <th>User Name</th>
                  <th>Reason</th>
                  <th>Detail</th>
                  <th>Cancel Type</th>
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

<?php include'include/footer.php'; ?>

<script>
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
