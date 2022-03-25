<script src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  "use strict";;
  (function($, window, document) {
    $(document).ready(function() {
      // Check if customer exist
      var customer = {{ $customer ? 'true' : 'undefined' }};

      // Show email/password form is customer want to save the card/create account
      if ($("#create-account-checkbox, #remember-the-card").is(':checked')) {
        showAccountForm();
      }

      // Toggle account creation fields
      $('#create-account-checkbox, #remember-the-card').on('ifChecked', function() {
        $('#create-account-checkbox').iCheck('check');
        showAccountForm();
      });
      $('#create-account-checkbox').on('ifUnchecked', function() {
        $('#remember-the-card').iCheck('uncheck');
        $('#create-account').hide().find('input[type=email],input[type=password]').removeAttr('required');
      });

      $('.payment-option').on('ifChecked', function(e) {
        var code = $(this).data('code');
        $("#payment-instructions.text-danger").removeClass('text-danger').addClass('text-info small');
        $('#payment-instructions').children('span').html($(this).data('info'));

        // Alter checkout button text Stripe
        if ('stripe' == code && $(this).val() != 'saved_card') {
          showCardForm();
        } else {
          hideCardForm();
        }

        // Alter checkout button text Authorize Net or cybersource
        if ('authorize-net' == code || 'cybersource' == code) {
          showAuthorizeNetCardForm();
        } else {
          hideAuthorizeNetCardForm();
        }

        // Alter checkout button
        if ('paypal-express' == code || 'paypal-marketplace' == code) {
          $('#paypal-express-btn').removeClass('hide');
          $('#pay-now-btn').addClass('hide');
        } else {
          $('#paypal-express-btn').addClass('hide');
          $('#pay-now-btn').removeClass('hide');
        }

        if ('pip' == code) {
          $('#payment-instructions').addClass('hide');
          $('#payInPerson').removeClass('hide');
          $('#pay-now-btn-txt').addClass('hide');
          $('#place-order-text').removeClass('hide');
        } else {
          $('#payment-instructions').removeClass('hide');
          $('#payInPerson').addClass('hide');
          $('#place-order-text').addClass('hide');
          $('#pay-now-btn-txt').removeClass('hide');
        }

        // Razorpay form
        if ('razorpay' == code) {
          $('#pay-now-btn-txt').html('{!! trans('theme.button.pay_now') !!}');
        }

        // mpesa form
        if ('mpesa' == code) {
          showMPesaForm();
        } else {
          hideMPesaForm();
        }

        // jrfpay form
        if ('jrfpay' == code) {
          showJrfPayForm();
        } else {
          hideJrfPayForm();
        }
      });

      // Submit the form
      $("a#paypal-express-btn").on('click', function(e) {
        e.preventDefault();
        $("form[name='checkoutForm']").submit();
      });

      // Show cart form if the card option is selected
      var paymentOptionSelected = $('input[name="payment_method"]:checked');

      if (paymentOptionSelected.length > 0) {
        var code = paymentOptionSelected.data('code');

        if (code == 'stripe' && paymentOptionSelected.val() != 'saved_card') {
          showCardForm();
        } else if (code == 'authorize-net' || code == 'cybersource') {
          console.log(code);
          showAuthorizeNetCardForm();
        } else if ('paypal-express' == code || 'paypal-marketplace' == code) {
          $('#pay-now-btn').addClass('hide');
          $('#paypal-express-btn').removeClass('hide');
        } else if ('mpesa' == code) {
          showMPesaForm(); // jrfpay package
        } else if ('jrfpay' == code) {
          //Reset the otp code when redirect back
          $('#jrfpay-form').find('input#jrfpay-otp-code').val(null);

          showJrfPayForm(); // jrfpay package
        }
      }

      // Stripe code, create a token
      Stripe.setPublishableKey("{{ config('services.stripe.key') }}");

      $("form[name='checkoutForm']").on('submit', function(e) {
        e.preventDefault();

        var form = $(this);

        // Check if payment method has been selected or not
        if (!$("input:radio[name='payment_method']").is(":checked")) {
          $("#payment-instructions.text-info").removeClass('text-info small').addClass('text-danger');
          return;
        }

        // If customer exist the check shipping address is seleced
        if (typeof customer !== "undefined") {
          if (!$("input:radio[name='ship_to']").is(":checked")) {
            $('.address-list-item').addClass('has-error');
            $('#ship-to-error-block').html("{{ trans('theme.notify.select_shipping_address') }}");
            return;
          }
        }

        // Check if form validation pass
        if ($(".has-error").length) {
          @include('theme::layouts.notification', ['message' => trans('theme.notify.fill_required_info'), 'type' => 'warning', 'icon' => 'times-circle'])
          return;
        }

        apply_busy_filter('body');

        var payment_method = $('input[name=payment_method]:checked').data('code');

        // Skip the strip payment and submit if the payment method is not stripe
        if (payment_method == 'stripe') {
          // Stripe API skip the request if the information are not there
          if (!$("input[data-stripe='number']").val() || !$("input[data-stripe='cvc']").val()) {
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
        } else if (payment_method == 'razorpay' && !$('input[name=razorpay_payment_id]').val()) {
          @if (is_incevio_package_loaded('razorpay'))
            // Create and get razorpay order
            var request = $.ajax({
            url: "{{ isset($cart)? route('razorpay.payment.request', $cart): (isset($one_checkout_form)? route('razorpay.payment.request', 'all_checkout'): route('razorpay.payment.request')) }}",
            type: 'POST'
            });
          
            request.done(function(response) {
            remove_busy_filter('body');
            // console.log(response);
          
            // When server response with message
            if (response.message) {
            $('#checkout-notice-msg').html(response.message);
            $('#checkout-notice').show();
            }
          
            /**
            * The entire list of Checkout fields is available at
            * https://docs.razorpay.com/docs/checkout-form#checkout-fields
            */
            response.handler = function(response) {
          
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
          
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
          
            document.checkoutForm.submit();
            };
          
            response.modal = {
            ondismiss: function() {
            @include('theme::layouts.notification', ['message' => trans('theme.notify.payment_failed'), 'type' => 'warning', 'icon' => 'times-circle'])
            },
            // Boolean indicating whether pressing escape key
            // should close the checkout form. (default: true)
            escape: true,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
            };
          
            // Open the Razorpay payment form
            var rzp = new Razorpay(response);
            rzp.open();
            });
          
            request.fail(function(jqXHR, textStatus) {
            remove_busy_filter('body');
          
            // Razoray error
            if (textStatus == 'error') {
            $('#checkout-notice-msg').html(jqXHR.responseJSON.message);
            $('#checkout-notice').show();
            }
          
            @include('theme::layouts.notification', ['message' => trans('jrfpay::lang.bad_request'), 'type' => 'warning', 'icon' => 'times-circle'])
            });
          @endif

          @if (is_incevio_package_loaded('mpesa'))
            // // Create and get access token
            // var request = $.ajax({
            // url: "{!-- isset($cart) ? route(config('mpesa.routes.get_token'), $cart) : (isset($one_checkout_form) ? route(config('mpesa.routes.get_token'), 'all_checkout') : route(config('mpesa.routes.get_token'))) --}",
            // type: 'POST',
            // data: {
            // mpesa_account: $("#mpesa-account").val(),
            // }
            // });
          
            // request.done(function(response) {
            // remove_busy_filter('body');
          
            // // When server response with message
            // if (response.message) {
            // $('#checkout-notice-msg').html(response.message);
            // $('#checkout-notice').show();
            // }
          
            // console.log(response);
            // });
            // @endif
        } else if (payment_method == 'jrfpay' && !$('#jrfpay-otp-code').val()) {
          submitJrfPayRequest();
        } else {
          form.get(0).submit();
        }
      });

      $("#submit-btn-block").show(); // Show the submit buttons after loading the doms
    });

    function showAccountForm() {
      $('#create-account').show().find('input[type=email],input[type=password]').attr('required', 'required');
    }

    // Stripe
    function showCardForm() {
      $('#cc-form').show().find('input, select').attr('required', 'required');
      $('#pay-now-btn-txt').html('{!! trans('theme.button.pay_now') !!}');
    }

    function hideCardForm() {
      $('#cc-form').hide().find('input, select').removeAttr('required');
      $('#pay-now-btn-txt').text('{{ trans('theme.button.checkout') }}');
    }

    // Authorize Net
    function showAuthorizeNetCardForm() {
      $('#authorize-net-cc-form').show().find('input, select').attr('required', 'required');
      $('#pay-now-btn-txt').html('{!! trans('theme.button.pay_now') !!}');
    }

    function hideAuthorizeNetCardForm() {
      $('#authorize-net-cc-form').hide().find('input, select').removeAttr('required');
      $('#pay-now-btn-txt').text('{{ trans('theme.button.checkout') }}');
    }

    // M-Pesa Payment
    function showMPesaForm() {
      $('#mpesa-form').show().find('input.mpesa-request-field').attr('required', 'required');
    }

    function hideMPesaForm() {
      $('#mpesa-form').hide().find('input.mpesa-request-field').removeAttr('required');
    }

    // JRF Pay package
    function showJrfPayForm() {
      $('#jrfpay-form').show().find('input.jrfpay-request-field').attr('required', 'required');
    }

    function hideJrfPayForm() {
      $('#jrfpay-form').hide().find('input.jrfpay-request-field').removeAttr('required');
    }

    function submitJrfPayRequest() {
      var cart = $("#checkout-id").val();
      var request = $.ajax({
        url: "{{ config('jrfpay.routes') ? route(config('jrfpay.routes.payment_request')) : '#' }}",
        type: 'POST',
        data: {
          customer_id: $("#jrfpay-id").val(),
          customer_pin: $("#jrfpay-pin").val(),
          amount: Math.round($("#summary-grand-total" + cart).text())
        }
      });

      request.done(function(data) {
        $('#jrfpay-errors').removeClass('hide').html('<div class="alert alert-success flat small">' + data.message + '</div');

        // Add the transaction_id into the form
        $("form[name='checkoutForm']").append('<input type="hidden" name="jrfpay_transaction_id" value="' + data.transaction_id + '" />');

        // Populate OTP field
        $('#jrfpay-form').find('input.jrfpay-request-field').remove();
        $('#jrfpay-otp').show().find('input#jrfpay-otp-code').attr('required', 'required');
      });

      request.fail(function(jqXHR, textStatus) {
        // Jrf Pay error
        if (textStatus == 'error') {
          $('#jrfpay-errors').removeClass('hide').html('<div class="alert alert-danger flat small">' + jqXHR.responseJSON.message + '</div');
        }

        // Form validation errors
        $.each(jqXHR.responseJSON.errors, function(key, value) {
          $('#jrfpay-errors').removeClass('hide').append('<div class="alert alert-danger flat small">' + value + '</div');
        });

        @include('theme::layouts.notification', ['message' => trans('jrfpay::lang.bad_request'), 'type' => 'warning', 'icon' => 'times-circle'])
      });

      request.always(function() {
        remove_busy_filter('body');
      });
    }
  }(window.jQuery, window, document));
</script>
