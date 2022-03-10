<?php if ($errors->any()){ ?>
    <div class="alert alert-danger web-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <?php echo implode('', $errors->all('<p>:message</p>')) ?>
    </div>
<?php } ?>

<?php if (Session::has('error')) { ?>
    <div class="alert alert-danger web-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <?php echo Session::get('error') ?>
    </div>
<?php } ?>
  <?php $error = Session::pull('token_error');
if(isset($error)){?>
    <div class="alert alert-danger web-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <?php echo $error ?>
    </div>
<?php } ?>

<?php $success = Session::pull('token_success');
        if(isset($success)){ ?>
    <div class="alert alert-success web-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <?php echo $success ?>
    </div>
<?php } ?>
<?php if (Session::has('success')) { ?>
    <div class="alert alert-success web-alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <?php echo Session::get('success') ?>
    </div>
<?php } ?>
<div class="alert alert-success fade in alert-dismissible ajax-msg" style="display: none;">
        <span class="ajax-body"></span>
    </div>
 <div class="alert alert-success  ajax-msg1" style="display: none">

    <span>Saved Successfully</span>
    </div>

<script>
    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 3000);
</script>
