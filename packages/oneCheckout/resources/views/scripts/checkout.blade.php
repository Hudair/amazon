<script type="text/javascript">
  "use strict";;
  (function($, window, document) {
    $(document).ready(function() {
      // Disable checkout if seller has no payment option
      if ($('.payment-option').length == 0) {
        disableCartPayment('{{ trans('theme.notify.seller_has_no_payment_method') }}');
        $('#payment-instructions').children('span').html('{{ trans('theme.notify.seller_has_no_payment_method') }}');
      }

      // Check if customer exist
      var customer = '{{ $customer ? 'true' : 'undefined' }}';

      // Reset the shipping option if any addess selected
      var address = $('input[type="radio"].ship-to-address:checked');
      if (!address.val() && typeof customer == "undefined") {
        disableCartPayment('{{ trans('checkout::lang.no_delivery_address_on_zone') }}');
      }

      // Change shipping address
      $('.customer-address-list .address-list-item.selectable').on('click', function() {
        var radio = $(this).find('input[type="radio"].ship-to-address');
        $('.address-list-item').removeClass('selected has-error');
        $(this).addClass('selected');
        radio.prop("checked", true);
        $('#ship-to-error-block').text('');
      });
    });

    function disableCartPayment(msg = '') {
      $('#checkout-notice-msg').html(msg);
      $("#checkout-notice").show();
      $('#pay-now-btn, #paypal-express-btn').hide();
    }
  }(window.jQuery, window, document));
</script>
