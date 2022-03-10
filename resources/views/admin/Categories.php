<?php include'include/header.php'; ?>
<?php include'include/sidebar.php'; ?>
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
            Categories
            <small>Roadside</small>
        </h1>

    </section>
    <?php include'include/messages.php' ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Categories Details</h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="pull-right" >
                            <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#modal-category" style="margin-top: -40px;">Add Category</button>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Sr#</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th></th>
                                <th></th>
                            </tr>

                            </thead>
                            <tbody>
                            <?php if(isset($coupons)){
                                $count = 0;
                                foreach($coupons as $Reason){
                                    ?>
                                    <?php  ?>
                                    <tr>
                                        <td><?=++$count;?></td>
                                        <td style="width:250px"><?= $Reason->name; ?></td>
                                        <td><?= $Reason->created_at; ?></td>
                                        <td><a onclick="getBlog(<?= $Reason->id ?>);"  href="javascript:void(0);">Edit</a></td>
                                        <td><a onclick="return confirm('Are you sure you want to delete?')"
                                               href="<?= route('delete-category', $Reason->id) ?>">Delete</a></td>

                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
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
<div class="modal fade" id="modal-category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
            </div>
            <div class="modal-body">
                <form id="form" action="<?= asset('creat_category') ?>" method ="Post" enctype="multipart/form-data">
                    <?php include resource_path('views/admin/include/messages.php'); ?>
                    <?= csrf_field() ?>
                    <p class="login-box-msg">Category</p>
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Category name" required>
                    </div>
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
<div class="modal fade" id="edit-category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Blog</h4>
            </div>
            <div class="modal-body">
                <form id="edit_form" action="<?= route('update_category') ?>" method ="Post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <p class="login-box-msg">blog System</p>
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Category name"  id="edit_category_name" required>
                        <input type="hidden" name="id" class="form-control" placeholder="Category name"  id="edit_category_id" required>
                    </div>

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

<?php include'include/footer.php'; ?>
<!--<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>-->
<script>
    // $(document).on('show.bs.modal','#edit-category', function () {
    //     $('.ck-set-empty .ck.ck-editor').remove();
    //     ClassicEditor
    //         .create(document.querySelector('#edit_blog_text'))
    //         .catch(error => {
    //             console.error(error);
    //         });
    // });
    // $(document).on('show.bs.modal','#modal-blog', function () {
    //     $('.ck-set-empty .ck.ck-editor').remove();
    //     ClassicEditor
    //         .create(document.querySelector('#p_mdesc'))
    //         .catch(error => {
    //             console.error(error);
    //         });
    // });
    // var today = new Date().toISOString().split('T')[0];
    // document.getElementsByName("valid_till_data")[0].setAttribute('min', today);


    $(function () {
        $('#example1').DataTable({
            'responsive'  : true
        })
    });

    function getBlog(categorys_id) {
        let category_id = categorys_id;
        $.ajax({
            method: "post",
            url: "<?= route('get_category') ?>",
            data: {
                '_token': "<?= csrf_token() ?>",
                'category_id': category_id,
            },
            success: function (data) {
                $("#edit_category_id").val(data.id);
                $("#edit_category_name").val(data.name);
                $("#edit-category").modal('show');



            }
        })
    }
</script>
