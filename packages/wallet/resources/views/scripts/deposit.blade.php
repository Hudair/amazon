<script src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
"use strict";
;(function($, window, document) {
    $(document).ready(function(){
	    $('.payment-option').on('ifChecked', function(e) {

	    	var code = $(this).data('code');
			$("#payment-instructions.text-danger").removeClass('text-danger').addClass('text-info small');
			$('#payment-instructions').children('span').html($(this).data('info'));

	    	// Alter checkout button text Stripe
			if ('stripe' == code && $(this).val() != 'saved_card') {
				showCardForm();
			}
	    	else {
	    		hideCardForm();
	    	}

	    	// Alter checkout button text Authorize Net or cybersource
			if ('authorize-net' == code || 'cybersource' == code) {
				showAuthorizeNetCardForm();
			}
	    	else {
	    		hideAuthorizeNetCardForm();
	    	}

	    	// Alter checkout button
			if ('paypal-express' == code){
	            $('#paypal-express-btn').removeClass('hide');
	            $('#pay-now-btn').addClass('hide');
			}
			else{
	            $('#paypal-express-btn').addClass('hide');
	            $('#pay-now-btn').removeClass('hide');
			}

			// jrfpay form
			if ('jrfpay' == code){
				showJrfPayForm();
			}
			else{
	    		hideJrfPayForm();
			}
	    });

		// Submit the form
		$("a#paypal-express-btn").on('click', function(e) {
	      	e.preventDefault();
			$("form#depositForm").submit();
		});

		// Show cart form if the card option is selected
		var paymentOptionSelected = $('input[name="payment_method"]:checked');

		if (paymentOptionSelected.length > 0) {
			var code = paymentOptionSelected.data('code');

			if(code == 'stripe' && paymentOptionSelected.val() != 'saved_card') {
				showCardForm();
			}
			else if(code == 'authorize-net' || code == 'cybersource') {
				showAuthorizeNetCardForm();
			}
			else if ('paypal-express' == code){
	            $('#pay-now-btn').addClass('hide');
	            $('#paypal-express-btn').removeClass('hide');
			}
			else if('jrfpay' == code) {
				//Reset the otp code when redirect back
				$('#jrfpay-form').find('input#jrfpay-otp-code').val(null);

				showJrfPayForm(); // jrfpay package
			}
		}

	    // Stripe code, create a token
	    Stripe.setPublishableKey("{{ config('services.stripe.key') }}");

		$("form#depositForm").on('submit', function(e){
	      	e.preventDefault();

			var form = $(this);

			if(form.find("input[name='amount']").val() < 1) {
				return;
			}

			// Check if payment method has been selected or not
		  	if (! $("input:radio[name='payment_method']").is(":checked")) {
				$("#payment-instructions.text-info").removeClass('text-info small').addClass('text-danger');
				return;
		  	}

			apply_busy_filter('body');

			var payment_method = $('input[name=payment_method]:checked').val();

		  	// Skip the strip payment and submit if the payment method is not stripe
		  	if (payment_method == 'stripe') {
				// Stripe API skip the request if the information are not there
				if (! $("input[data-stripe='number']").val() || ! $("input[data-stripe='cvc']").val()) {
					return;
				}

			    Stripe.card.createToken(form, function(status, response) {
			        if (response.error) {
			          	form.find('.stripe-errors').text(response.error.message).removeClass('hide');
						remove_busy_filter('body');
			        } else {
			          	form.append($('<input type="hidden" name="cc_token">').val(response.id));
			          	form.get(0).submit();
			        }
				});
		  	}
		  	else if(payment_method == 'jrfpay' && ! $('#jrfpay-otp-code').val()){
		  		submitJrfPayRequest();
		  	}
		  	else {
				form.get(0).submit();
		  	}
	    });

		// Submit the form
		$("a#paypal-express-btn").on('click', function(e) {
	      	e.preventDefault();
			$("form#depositForm").submit();
		});

		$("#submit-btn-block").show(); // Show the submit buttons after loading the doms
    });

    // Stripe
    function showCardForm()
    {
		$('#cc-form').show().find('input:text, select').attr('required', 'required');
    }

    function hideCardForm()
    {
		$('#cc-form').hide().find('input, select').removeAttr('required');
    }

    // Authorize Net
    function showAuthorizeNetCardForm()
    {
		$('#authorize-net-cc-form').show().find('input, select').attr('required', 'required');
    }
    function hideAuthorizeNetCardForm()
    {
		$('#authorize-net-cc-form').hide().find('input, select').removeAttr('required');
    }

	// JRF Pay package
    function showJrfPayForm()
    {
		$('#jrfpay-form').show().find('input.jrfpay-request-field').attr('required', 'required');
    }

    function hideJrfPayForm()
    {
		$('#jrfpay-form').hide().find('input.jrfpay-request-field').removeAttr('required');
    }

    function submitJrfPayRequest()
    {
        var cart = $("#checkout-id").val();
        var request = $.ajax({
            url: "{{ config('jrfpay.routes.payment_request') ? route(config('jrfpay.routes.payment_request')) : "#" }}",
            type: 'POST',
            data: {
            	customer_id: $("#jrfpay-id").val(),
            	customer_pin: $("#jrfpay-pin").val(),
            	amount: Math.round($("input#amount").val())
            }
        });

		request.done(function(data) {
			$('#jrfpay-errors').removeClass('hide').html('<div class="alert alert-info flat small">'+data.message+'</div');

			// Add the transaction_id into the form
			$('form#depositForm').append('<input type="hidden" name="jrfpay_transaction_id" value="'+data.transaction_id+'" />');

			// Populate OTP field
			$('#jrfpay-form').find('input.jrfpay-request-field').remove();
			$('#jrfpay-otp').show().find('input#jrfpay-otp-code').attr('required', 'required');
		});

		request.fail(function(jqXHR, textStatus) {
			// Jrf Pay error
			if (textStatus == 'error') {
				$('#jrfpay-errors').removeClass('hide').html('<div class="alert alert-danger flat small">'+jqXHR.responseJSON.message+'</div');
			}

			// Form validation errors
			$.each(jqXHR.responseJSON.errors, function(key, value) {
				$('#jrfpay-errors').removeClass('hide').append('<div class="alert alert-danger flat small">'+value+'</div');
			});
		});

	    request.always(function () {
			remove_busy_filter('body');
	    });
    }
}(window.jQuery, window, document));
</script>