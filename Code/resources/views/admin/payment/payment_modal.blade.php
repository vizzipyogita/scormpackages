<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">{{$pageTitle}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

		<div class="row hide" id="ErrorDiv" style="padding:10px;">
			<div class="col">
				<div class="alert alert-danger alert-dismissible" role="alert">
					<span id="errorMsg"></span>					
				</div>
			</div>
		</div>       

		<form role="form" action="{{ route('paymentPost') }}" method="post" class="mb-3 require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
			@csrf
			<input type="hidden" id="subscriptionPlan" name="subscriptionPlan" value="{{$planType}}">
			<input type="hidden" id="subscriptionPlanAmount" name="subscriptionPlanAmount" value="{{$planAmount}}">
			<input type="hidden" id="netAmmount" name="netAmmount" value="{{$planAmount}}">
            <input type="hidden" id="plan_id" name="plan_id" value="{{$planId}}">
			<div class="row">
				<div class="form-group justify-content-center text-center">
                    @if($planType == 'M')
					    <h3>{!! Session::get('currency') !!}{{$planAmount}}/per month</h3>
                    @else
                        <h3>{!! Session::get('currency') !!}{{$planAmount}}/per year</h3>
                    @endif
				</div>
				<div class="mt-4 margin_zero margin_bottom_twenty justify-content-center text-center padding_left_ten">
					<img class="img-responsive margin_top_twenty" src="/assets/img/icons/unicons/paymentgatewayimage.png" width="200px;">
				</div>
				<div class='form-row row'>
					<div class='col-md-12 error form-group hide'>
						<div class='alert-danger alert'>
						</div>
					</div>
				</div>
				<div class="form-group formPayment">
					<div class="credit-card-box" id="creditCardBox">
						<div class="row">
							<div class="form-group">
								<input type="checkbox" id="apply_coupon" name="apply_coupon" onclick="showCouponDiv()" value="" checked>
								<label for="vehicle1"> Ask for Coupon</label>
							</div>
						</div>
						<div class="row mt-4" id="coupon_apply_div">
							<div class="form-group">
								<label for="">Coupon Code</label>
								<input type="text" class="form-control" id="coupoun_code" name="coupoun_code" placeholder="Enter Coupon Code" onblur="checkCouponValidity(this, '/coupons/details')">
							</div>
							<div class="form-group hide" id="coupon_info_div">
								<label for="" style="color:#0ab10a">Coupon Applied</label>
								<label class="" style="float: right;">Discount Ammount: <span id="discount_amount">0</span></label>
							</div>
						</div>
						<div class="row mt-4">
							<div class="form-group required">
								<label for="">Name on Card<span class="star">*</span></label>
								<input type="text" class="form-control" id="card_on_name" name="card_on_name" placeholder="Name on Card">
							</div>
						</div>
						<div class="row mt-4">
							<div class="form-group payment-errors">
								<label for="">Card Number<span class="star">*</span></label>
								<div class="input-group mb-3 required">
									<input type="tel" class="form-control cc-number card-number" id="cardNumber" name="cardNumber" placeholder="xxxx xxxx xxxx xxxx" minlength="16" maxlength="16" pattern="\d*" autocomplete="cc-number"/>
									<div class="input-group-append">
										<span class="input-group-text"><i class='bx bx-credit-card'></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
							<label for="cardExpiry">Expiry Date<span class="star">*</span></label>

								<div class="form-group exp-wrapper required">
									
									<input autocomplete="off" class="exp form-control card-expiry-month" id="month" maxlength="2" pattern="[0-9]*" inputmode="numerical" placeholder="MM" type="text" data-pattern-validate />
									<input autocomplete="off" class="exp form-control card-expiry-year" id="year" maxlength="2" pattern="[0-9]*" inputmode="numerical" placeholder="YY" type="text" data-pattern-validate />
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6">
								<div class="form-group required">
									<label for="">CVV<span class="star">*</span></label>
									<input autocomplete='off' class='form-control card-cvc' placeholder='xxx' size='4' minlength="3" maxlength="3" type='text' style="width:50%">
								</div>
							</div>
						</div>
						<div class="row mt-4">
							<label class="" style="float: right; font-size:20px"><strong>Payable Amount: {!! Session::get('currency') !!}<span id="total_display_amount">{{$planAmount}}</span></strong></label>
						</div>
						<div class="form-group mt-3 justify-content-center text-center">
							<button type="submit" class="btn btn-primary" id="subscription_pay_btn">Pay</button>
							<a class="btn btn-secondary" href="/organization/upgrade">Cancel</a>
						</div>
						<div class="mt-4 margin_zero margin_bottom_twenty justify-content-center text-center padding_left_ten">
							<img class="img-responsive margin_top_twenty" src="/assets/img/icons/unicons/powered_by_stripe.png" width="200px;">
						</div>
						
					</div>
				</div>
			</div>
		</form>

    </div>
</div>

<script>
    $('#payment-form').parsley();

    const monthInput = document.querySelector('#month');
    const yearInput = document.querySelector('#year');

    const focusSibling = function(target, direction, callback) {
    const nextTarget = target[direction];
    nextTarget && nextTarget.focus();
    // if callback is supplied we return the sibling target which has focus
    callback && callback(nextTarget);
    }

    // input event only fires if there is space in the input for entry. 
    // If an input of x length has x characters, keyboard press will not fire this input event.
    monthInput.addEventListener('input', (event) => {

    const value = event.target.value.toString();
    // adds 0 to month user input like 9 -> 09
    if (value.length === 1 && value > 1) {
        event.target.value = "0" + value;
    }
    // bounds
    if (value === "00") {
        event.target.value = "01";
    } else if (value > 12) {
        event.target.value = "12";
    }
    // if we have a filled input we jump to the year input
    2 <= event.target.value.length && focusSibling(event.target, "nextElementSibling");
    event.stopImmediatePropagation();
    });

    yearInput.addEventListener('keydown', (event) => {
    // if the year is empty jump to the month input
    if (event.key === "Backspace" && event.target.selectionStart === 0) {
        focusSibling(event.target, "previousElementSibling");
        event.stopImmediatePropagation();
    }
    });

    const inputMatchesPattern = function(e) {
    const { 
        value, 
        selectionStart, 
        selectionEnd, 
        pattern 
    } = e.target;
    
    const character = String.fromCharCode(e.which);
    const proposedEntry = value.slice(0, selectionStart) + character + value.slice(selectionEnd);
    const match = proposedEntry.match(pattern);
    
    return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        match && match["0"] === match.input; // pattern regex isMatch - workaround for passing [0-9]* into RegExp
    };

    document.querySelectorAll('input[data-pattern-validate]').forEach(el => el.addEventListener('keypress', e => {
    if (!inputMatchesPattern(e)) {
        return e.preventDefault();
    }
    }));


    $(function() {
    
        /*------------------------------------------
        --------------------------------------------
        Stripe Payment Code
        --------------------------------------------
        --------------------------------------------*/
        
        var $form = $(".require-validation");
        
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                            'input[type=text]', 'input[type=file]',
                            'textarea'].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
            $errorMessage.addClass('hide');
        
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
            });
        
            if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
            }
        
        });
        
        /*------------------------------------------
        --------------------------------------------
        Stripe Response Handler
        --------------------------------------------
        --------------------------------------------*/
        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];
                   console.log(token); 
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
        
    });
</script>