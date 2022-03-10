<?php include'includes/header.php'; ?>
<?php include'includes/sidebar.php'; ?>
<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Family Members<small>Drive Roadside</small></h1>
        <div class="pull-right" >
            <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#modal-default" style="margin-top: -40px;">Register New Member</button>
        </div>
    </section>
    <!--Error Message-->
    <div class="alert alert-danger fade in alert-dismissible ajax-msg-danger" style="display: none;">
        <span class="ajax-body-danger"></span>
    </div>
    <!--Success Message-->
    <div class="alert alert-success fade in alert-dismissible ajax-msg-success" style="display: none;">
        <span class="ajax-body-success"></span>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Family Members Details</h3>
                    </div>
                    <div class="row">
                        <?php foreach($family_members as $one) { ?>
                            <div class="col-md-6">
                            <!-- Profile Image -->
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <?php
                                    $img = $one->avatar ;
                                    if($img){
                                        ?>
                                        <img  class="profile-user-img img-responsive img-circle" alt="<?php $img? '': 'Image not Found'?>"  src="<?= asset('public/images/'.$img); ?>"/>
                                        <?php
                                    }else{
                                        ?>
                                        <img  class="profile-user-img img-responsive img-circle" alt="<?php $img? '': 'Image not Found'?>"  src="<?= asset('public/images/default/user_icon.png'); ?>"/>
                                        <?php
                                    }
                                    ?>                                    <h3 class="profile-username text-center"><?php echo $one->name; ?></h3>
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Email</b> <a class="pull-right"><?php echo $one->email; ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Contact Number</b> <a class="pull-right"><?php echo $one->contact_number; ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Registered On </b> <a class="pull-right"><?php echo $one->created_at; ?></a>
                                        </li>
                                    </ul>
                                        <a onclick="return delete_confirmation(event)" href="<?= asset('remove_member/' . $one->id); ?>" class="btn btn-primary btn-block"><b>Remove Member</b></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Register New Member</h4>
                </div>
                <div class="modal-body">
                    <form id="form" action="<?= asset('register_new_member') ?>" method ="Post">
                        <?php include resource_path('views/admin/include/messages.php'); ?>
                        <?= csrf_field() ?>
                        <p class="login-box-msg">Member Details</p>
                        <div class="form-group has-feedback">
                            <input type="text" name="name" class="form-control" placeholder="Full name" required>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" name="password" id='password' class="form-control" placeholder="Password" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" name="c_password" class="form-control" placeholder="Retype Password" required>
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="number" name="contact_number" class="form-control" placeholder="Contact" required>
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="address" name="address" class="form-control" placeholder="Street Address" required>
                            <span class="glyphicon  form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="city" name="city" class="form-control" placeholder="City" required>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="state" name="state" class="form-control" placeholder="State" required>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="zipcode" name="zipcode" class="form-control" placeholder="Zip Code" required>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="country" name="country" class="form-control" placeholder="Country" required>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 res_col">
                                <button type="submit" class="btn btn-primary btn-block btn-flat register">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>

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

<?php include'includes/footer.php'; ?>

