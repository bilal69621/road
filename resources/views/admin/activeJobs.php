<?php include'include/header.php'; ?>
<?php include'include/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
   <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Active Jobs
            <small>Drive Roadside</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Active Jobs</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr#</th>
                                    <th>User Name </th>
                                    <th>User Email </th>
                                    <th>User Phone </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($jobs)) {
                                    $count = 0;
                                    foreach ($jobs as $job) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count; ?></td>
                                            <td><?= $job->user->name; ?> </td>
                                            <td><?= $job->user->contact_number; ?></td>
                                            <td><?= $job->user->email; ?></td>

                                        </tr>
                                    <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sr#</th>
                                    <th>Name </th>
                                    <th>Number </th>
                                    <th>Email </th>

<!--                                    <th>Charge Amount</th>
                                    <th>Charge Date</th>
                                    <th>Subscription Plan</th>
                                    <th>Subscription End</th>
                                    <th>Mile Covered</th>-->
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
            'responsive':true
        });
        $('#example2').DataTable({
            'responsive':true,
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        })
    })
</script>