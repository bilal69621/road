<?php include 'include/header.php'; ?>
<?php include 'include/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<style>
    .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline.ck-blurred {
        height: 320px;
        overflow: auto;
    }
</style>
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subscriptions
            <small>Roadside</small>
        </h1>

    </section>
    <?php include 'include/messages.php' ?>
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
                        <div class="pull-right">
                            <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#modal-blog"
                                    style="margin-top: -40px;">Add Blog
                            </button>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">

                            <thead>
                            <tr>
                                <th>Sr#</th>
                                <th>Name</th>
                                <th>blog</th>
                                <th>Created At</th>
                                <th></th>
                                <th></th>
                            </tr>

                            </thead>
                            <tbody>
                            <?php if (isset($coupons)) {
                                $count = 0;
                                foreach ($coupons as $Reason) {
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
                                        <td><?= ++$count; ?></td>
                                        <td style="width:250px"><?= $Reason->name; ?></td>
                                        <td><img style="width:150px;height:150px;object-fit: cover"
                                                 src="<?= "public/blog" . "/" . $Reason->main_image; ?>"></td>
                                        <td><?= $Reason->created_at; ?></td>
                                        <td><a onclick="getBlog(<?= $Reason->id ?>);"
                                               href="javascript:void(0);">Edit</a></td>
                                        <td><a onclick="return confirm('Are you sure you want to delete?')"
                                               href="<?= route('delete-post', $Reason->id) ?>">Delete</a></td>
                                    </tr>
                                <?php }
                            } ?>

                            </tbody>
                            <!--                            <tfoot>-->
                            <!--                            <tr>-->
                            <!--                                <th>Sr#</th>-->
                            <!--                                <th>name</th>-->
                            <!--                                <th>blog</th>-->
                            <!--                                <th>Created At</th>-->
                            <!---->
                            <!--                            </tr>-->
                            <!--                            </tfoot>-->
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
<div class="modal fade" id="modal-blog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Blog</h4>
            </div>
            <div class="modal-body">
                <form id="form" action="<?= asset('creat_blog') ?>" method="Post" enctype="multipart/form-data">
                    <?php include resource_path('views/admin/include/messages.php'); ?>
                    <?= csrf_field() ?>
                    <p class="login-box-msg">blog System</p>
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Blog name" required>
                    </div>
                    <div class="form-group has-feedback">
                        <textarea class="form-control" id="p_mdesc" name="p_mdesc" rows="100"></textarea>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="file" class="form-control" id="image_main" name="image_main"></input>
                    </div>
                    <div class="form-group has-feedback">
                        <select class="form-control" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $Reason) { ?>
                                <option value="<?= $Reason->id; ?>"><?= $Reason->name; ?></option>
                            <?php } ?>
                        </select></div>

                    <div class="row">
                        <div class="col-xs-4 res_col">
                            <button type="submit" class="btn btn-primary btn-block btn-flat register">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-blog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Blog</h4>
            </div>
            <div class="modal-body">
                <form id="edit_form" action="<?= route('update_blog') ?>" method="Post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <p class="login-box-msg">blog System</p>
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Blog name" id="edit_blog_name"
                               required>
                        <input type="hidden" name="id" class="form-control" placeholder="Blog name" id="edit_blog_id"
                               required>
                    </div>
                    <div class="form-group has-feedback ck-set-empty">
                        <textarea class="form-control" name="p_mdesc" rows="100" id="edit_blog_text"></textarea>
                    </div>
                    <div class="form-group has-feedback">
                        <img>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="file" class="form-control" id="image_main" name="image_main">
                    </div>
                    <div class="form-group has-feedback">
                        <select class="form-control" required name="category_id" id="edit_blog_category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $Reason) { ?>
                                <option value="<?= $Reason->id; ?>"><?= $Reason->name; ?></option>
                            <?php } ?>
                        </select></div>
                    <div class="row">
                        <div class="col-xs-4 res_col">
                            <button type="submit" class="btn btn-primary btn-block btn-flat register">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>
<script>
    $(document).on('show.bs.modal', '#edit-blog', function () {
        $('.ck-set-empty .ck.ck-editor').remove();
        ClassicEditor
            .create(document.querySelector('#edit_blog_text'))
            .catch(error => {
                console.error(error);
            });
    });
    $(document).on('show.bs.modal', '#modal-blog', function () {
        $('.ck-set-empty .ck.ck-editor').remove();
        ClassicEditor
            .create(document.querySelector('#p_mdesc'))
            .catch(error => {
                console.error(error);
            });
    });
    // var today = new Date().toISOString().split('T')[0];
    // document.getElementsByName("valid_till_data")[0].setAttribute('min', today);


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
    });

    function getBlog(blogs_id) {
        let blog_id = blogs_id;
        $.ajax({
            method: "post",
            url: "<?= route('get_blog') ?>",
            data: {
                '_token': "<?= csrf_token() ?>",
                'blog_id': blog_id,
            },
            success: function (data) {
                $("#edit_blog_id").val(data.id);
                $("#edit_blog_text").val(data.blog);
                $("#edit_blog_name").val(data.name);
                $("#edit_blog_category_id").val(data.category_id);
                $("#edit-blog").modal('show');


            }
        })
    }
</script>
