<?php include'includes/header.php'; ?>
<?php include'includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Payment Method<small>Drive Roadside</small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Payment Method Details</h3>
                        <?php include resource_path('views/admin/include/messages.php'); ?>
                        <!--Error Message-->
                    <div class="alert alert-danger fade in alert-dismissible ajax-msg-danger" style="display: none;">
                        <span class="ajax-body-danger"></span>
                    </div>
                    <!--Success Message-->
                    <div class="alert alert-success fade in alert-dismissible ajax-msg-success" style="display: none;">
                        <span class="ajax-body-success"></span>
                    </div>

                    <div class="box-body">
                        <div class="box-header with-border"></div>
                    </div>
                    <div class="form-group">
                        <label>Card Number</label>
                        <span class="form-control general-field">**** **** <?php echo Auth::user()->card_last_four; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Card Holder</label>
                        <span class="form-control general-field"><?php echo Auth::user()->name; ?></span>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <button type="button" class="btn  btn-success" data-toggle="modal" data-target="#modal-default">Update Payment Method</button>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Payment Method</h4>
                </div>
                <div class="modal-body">
                    <form id="form" action="<?= asset('update_payment_method') ?>" method ="Post">
                        <?= csrf_field() ?>
                        <p class="login-box-msg">Payment Info</p>
                        <div class="form-group">
                            <span style="color:red" class="payment-errors"></span>
                            <label>Card Number</label>
                            <input name="cardnumber" class="form-control general-field card-field" size="16" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 16)
                                    return false;" data-stripe="number" type="number" placeholder="4242 4242 4242 4242" required>
                        </div>
                        <div class="form-group">
                            <label>Card Holder</label>
                            <input type="text" class="form-control general-field " name="cardholdername" placeholder="Card holder name" value="<?php echo Auth::user()->name; ?>" readonly required>
                        </div>
                        <div class="cart-holder">
                            <label>Exp.Date</label>
                            <div class="flex-class">
                                <div class="form-group ">
                                    <div class="cus_flex">
                                        <input size="2" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 2)
                                            return false;" data-stripe="exp-month" name="month" type="number" class="form-control" placeholder="Month" required/>
                                    </div>
                                    <input size="4" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 4)
                                            return false;" data-stripe="exp-year" type="number" name="year" class="form-control" placeholder="Year" required/>
                                </div>
                                <div class="form-group ">
                                    <label>CVC</label>
                                    <input  size="3" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 3)
                                            return false;" data-stripe="cvc"  type="number" name="cvc" class="form-control general-field" placeholder="CVC" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 res_col">
                                <button type="submit" class="btn btn-primary btn-block btn-flat register">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

</div>
<?php include'includes/footer.php'; ?>
<script>
    Stripe.setPublishableKey('pk_test_EbSWPS8eAbEXdAq34Dj65NEa00cYn94Vo7');
    jQuery(function () {
        $('#form').submit(function (event) {
            event.preventDefault();
            var $form = $(this);
            $this = $(this);
            $form.validate({
                rules: {
                   cardnumber: {
                        required: true,
                    }, cardholdername: {
                        required: true,
                    }, cvc: {
                        required: true,
                    }, month: {
                        required: true,
                    }, year: {
                        required: true,
                    }, address: {
                        required: true,
                    }
                }
                , messages: {
                    cvc: {
                        required: "",
                    }, month: {
                        required: "",
                        range: [1, 12]
                    }, year: {
                        required: "",
                    }, address: {
                        required: "",
                    }

                }, submitHandler: function (form) {
                }
            });

            $form.find('button').prop('disabled', true);

            Stripe.card.createToken($form, stripeResponseHandler);

        });
    });
    function stripeResponseHandler(status, response) {
        var $form = $('#form');
        if (response.error) {
            // Show the errors on the form
            $form.find('.payment-errors').text(response.error.message);
            $form.find('button').prop('disabled', false);
        } else {
            $form.get(0).submit();
        }
    }
    $('.mobile-toggler1').click(function(){
        $('.site-menu').slideToggle();
    });
</script>
