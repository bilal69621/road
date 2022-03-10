 <div class="modal custom-modal" tabindex="-1" role="dialog" id="addcard">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
                <div class="modal-steps-form step-active">
                    <div class="step-main-repeater">
                        <div class="step-form-title text-center">
                            <img src="{{asset('public/assets/images/payment.svg')}}"/>
                            <h4>Add credit or debit card</h4>
                        </div>
                        <form id="payment-form" action="{{route('createcard')}}" method="POST"  >
                            <?= csrf_field(); ?>
                            <div class="credit-cart">
                                <h6>Credit Card</h6>
                                <span style="color:red" class="payment-errors"></span>
                                <div class="form-group">
                                    <input type="hidden" id="stripe_token">
                                    <label>Card Number</label>
                                    <input class="form-control general-field card-field" size="16" pattern="/^-?\d+\.?\d*$/"
                                           onKeyPress="if (this.value.length == 16)
                                            return false;" data-stripe="number" type="number"
                                           placeholder="4242 4242 4242 4242" required>
                                    <input type="hidden" name="choose_plan" id="choose_plan">
                                </div>
                                <div class="form-group">
                                    <label>Card Holder</label>
                                        <input type="text" class="form-control general-field " name="name" id="card_name"
                                               placeholder="Card holder name" required>
                                    </div>
                                    <div class="cart-holder">

                                        <div class="form-group">
                                            <label>Exp.Month</label>
                                            <input min="1" max="12" size="2" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 2)
                                                    return false;" data-stripe="exp-month" type="number" class="form-control general-field"
                                                   placeholder="Month" required/>
                                             <label>Exp.Year</label>
                                            <input min="2020" size="4" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 4)
                                                    return false;" data-stripe="exp-year" type="number" class="form-control general-field"
                                                   placeholder="Year" required/>
                                        </div>
                                        <div class="form-group ">
                                            <label>CVC</label>
                                            <input size="3" pattern="/^-?\d+\.?\d*$/" onKeyPress="if (this.value.length == 3)
                                                    return false;" data-stripe="cvc" type="number"
                                                   class="form-control general-field" placeholder="CVC" required>
                                        </div>
                                    </div>
                                    <div class="credit-cart-save">
                                        <button id="strp-card-btn" class="btn btn_grey ">Save</button>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
//     function check(){
Stripe.setPublishableKey('{{ env('STRIPE_KEY')}}');
                                    $('#payment-form').submit(function (event) {
                                        var $form = $(this);
                                        // Disable the submit button to prevent repeated clicks
                                        $form.find('button').prop('disabled', true);

                                        Stripe.card.createToken($form, stripeResponseHandler);

                                        // Prevent the form from submitting with the default action
                                        return false;
                                    });
                                function stripeResponseHandler(status, response) {
                                    var $form = $('#payment-form');

                                    if (response.error) {
                                        console.log(response.error)
                                        // Show the errors on the form
                                        $form.find('.payment-errors').show().text(response.error.message);
                                        $form.find('button').prop('disabled', false);
                                    } else {
                                        // response contains id and card, which contains additional card details
                                        var token = response.id;
                                        // Insert the token into the form so it gets submitted to the server
                                        $form.append($('<input type="hidden" name="stripeToken" />').val(token));

                                        // and submit
                                        $form.get(0).submit();
                                    }
                                }
//                                }
    </script>
