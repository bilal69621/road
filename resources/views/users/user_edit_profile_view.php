<?php include'includes/header.php'; ?>
<?php include'includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small><?= isset($title) ? $title : ''; ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?= isset($title) ? $title : ''; ?></h3>
                        <div class="pull-right">
                            <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#modal-default">Change Password</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-header with-border">

                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <!--Success Message-->
                        <!--                    <div class="alert alert-success fade in alert-dismissible ajax-msg" style="display: none;">
                                                    <span class="ajax-body"></span>
                                            </div>-->
                        <form method="POST" enctype="multipart/form-data" action="<?= asset('update_user'); ?>" >
                            <?php if (Session::has('success')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times</a>
                                    <?php echo Session::get('success') ?>
                                </div>
                            <?php } ?>
                            <?php if (Session::has('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times</a>
                                    <?php echo Session::get('error') ?>
                                </div>
                            <?php } ?>
                            <?php if ($errors->any()) { ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors->all() as $error) { ?>
                                            <li><?= $error ?></li>
                                        <?php }
                                        ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="full_name" value="<?=  htmlspecialchars($detail->name, ENT_QUOTES, 'UTF-8') ?>" placeholder="Full Name">
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($detail->email, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter Email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($detail->address, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter Address">
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($detail->city, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter City">
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" id="state" name="state" value="<?= htmlspecialchars($detail->state, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter State">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="<?= htmlspecialchars($detail->country, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter Country">
                                </div>
                                <div class="form-group">
                                    <label>ZipCode</label>
                                    <input type="text" class="form-control" id="zipcode" name="zipcode" value="<?= htmlspecialchars($detail->zipcode, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter ZipCode">
                                </div>
                                <div class="form-group img_size">
                                    <label>Upload Image <span style="color: red">*MAX SIZE 2MB</span></label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file">
                                                Browse??? <input type="file" id="imgInp"  name="profile_img">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                    <div class="profile_images">
                                        <?php
                                            $img = $detail->avatar ;
                                        if($img){
                                            ?>
                                            <img style="width: 299px;height: 300px;background-repeat: no-repeat;background-size: cover; background-position: center;" id='img-upload' alt="<?php $detail->avatar? '': 'Image not Found'?>"  src="<?= asset('public/svg/'.$detail->avatar ); ?>"/>
                                            <?php
                                        }else{
                                            ?>
                                            <img style="width: 299px;height: 300px;background-repeat: no-repeat;background-size: cover; background-position: center;" id='img-upload' alt="<?php $detail->avatar? '': 'Image not Found'?>"  src="<?= asset('public/images/default/user_icon.png'); ?>"/>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.box-body -->
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--Error Message-->
                <div class="alert alert-danger fade in alert-dismissible ajax-msg-danger" style="display: none;">
                    <span class="ajax-body-danger"></span>
                </div>
                <!--Success Message-->
                <div class="alert alert-success fade in alert-dismissible ajax-msg-success" style="display: none;">
                    <span class="ajax-body-success"></span>
                </div>
                <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>-->
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Enter Current Password</label>
                    <input required="true" type="password" class="form-control" name="current_password" id="current_password" value="" placeholder="Enter Old Password">
                </div>
                <div class="form-group">
                    <label>Enter New Password</label>
                    <input required="true" type="password" class="form-control" name="password" id="password" value="" placeholder="Enter New Password">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input required="true" type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="" placeholder="Confirm New Password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" onclick="clearData()" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="changePass()">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal-dialog -->







<?php include'includes/footer.php'; ?>

<script>
    $(document).ready( function() {
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        });
    });
    //        Change Password Function
    function changePass(){
        //get values from input fields
        var current_password, password, password_confirmation = "";
        current_password        = $('#current_password').val();
        password                = $('#password').val();
        password_confirmation   = $('#password_confirmation').val();

        $.ajax({
            url: "<?= asset('change_user_password');?>",
            data: {
                '_token' : '<?= csrf_token(); ?>',
                current_password: current_password ,
                password    : password,
                password_confirmation    : password_confirmation,
            },
            type: "POST",
            success: function (success) {
                // hide error messages
                $('.ajax-msg-danger').hide();
                // show success message div
                $('.ajax-msg-success').show();
                //empty if had already some value
                $('.ajax-body-success').empty();
                $('#current_password').val('');
                $('#password').val('');
                $('#password_confirmation').val('');
                //append the success message
                $('.ajax-body-success').append(success.message);

            }, error: function (err) {

                // hide success messages
                $('.ajax-msg-success').hide();
                // show error message div
                $('.ajax-msg-danger').show();
                //empty if had already any value
                $('.ajax-body-danger').empty();
                $('#current_password').val('');
                $('#password').val('');
                $('#password_confirmation').val('');

                //Check error message from default validator
                if(err.responseJSON.from =='validator'){
                    //Seprate into single val
                    $.each(err.responseJSON.message, function(key,value) {
                        //if array have consists of more arrays
                        if(value.length > 1){
                            //Seprate into single arrays
                            $.each(value, function(key,val) {
                                //Appendnig the values to targeted class
                                $('.ajax-body-danger').append(val+'<br>');
                            });
                        }else{
                            //Appendnig the values to targeted class
                            $('.ajax-body-danger').append(value+'<br>');
                        }

                    });
                }
                //Check error message is due to invalid password
                else if(err.responseJSON.from =='invalid'){


                    //Appendnig the values to targeted class
                    $('.ajax-body-danger').append(err.responseJSON.message);
                }
            }
        })

    }
    //On Close the model clear and hide all the fields
    function clearData(){
        $('.ajax-msg-success').hide();
        $('.ajax-body-success').empty();
        $('.ajax-msg-danger').hide();
        $('.ajax-body-danger').empty();
        $('#current_password').val('');
        $('#password').val('');
        $('#password_confirmation').val('');
    }
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
