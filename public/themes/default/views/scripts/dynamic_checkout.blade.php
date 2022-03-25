<script type="text/javascript">
  "use strict";;
  (function($, window, document) {
    $(document).ready(function() {
      $('.shopping-cart-table-wrap').each(function(e) {
        var cart = $(this).data('cart');
        var shop = $('#shop-id' + cart).val();
        var shippingOptions = getShippingOptions(cart);

        if (!shop || !shippingOptions) {
          disableCartCheckout(cart);
        } else {
          var shippingRateId = Number($('#shipping-rate-id' + cart).val());
          var shippingRate = $.grep(shippingOptions, function(el) {
            return el.id === shippingRateId;
          })[0];

          if (shippingRate) {
            setShippingCost(cart, shippingRate.name, shippingRate.rate, shippingRate.id);
          } else {
            setShippingOptions(cart);
          }
        }
      });

      //ONLY CHECKOUT PAGE CODE
      if ($("#checkout-id").length == 1) {
        // Disable checkout if seller has no payment option
        if ($('.payment-option').length == 0) {
          disableCartPayment('{{ trans('theme.notify.seller_has_no_payment_method') }}');
          $('#payment-instructions').children('span').html('{{ trans('theme.notify.seller_has_no_payment_method') }}');
        }

        // Reset the shipping option if any addess selected
        var address = $('input[type="radio"].ship-to-address:checked');
        if (address.val()) {
          setShippingZone($("#checkout-id").val(), address.data('country'), address.data('state'));
        }

        // Alter shipping address
        $('.customer-address-list .address-list-item').on('click', function() {
          var radio = $(this).find('input[type="radio"].ship-to-address');
          $('.address-list-item').removeClass('selected has-error');
          $(this).addClass('selected');
          radio.prop("checked", true);
          $('#ship-to-error-block').text('');

          setShippingZone($("#checkout-id").val(), radio.data('country'), radio.data('state'));
        });

        $("select#address_country").on('change', function() {
          // Show shipping charge may change msg if zone changes
          $(this).next('.help-block').html('<small>{{ trans('theme.notify.shipping_cost_may_change') }}</small>');
        });

        $("select#address_state").on('change', function(e) {
          if (!$("select#address_country").next('.help-block').text()) {
            $(this).next('.help-block').html('<small>{{ trans('theme.notify.shipping_cost_may_change') }}</small>');
          }

          var state_id = $(this).val();
          var country_id = $('#address_country').val();
          var cart = $("#checkout-id").val();

          // Reset the shipping options if the state is changed or have no state in the zone
          if (state_id || $(this).children('option').length < 2) {
            setShippingZone(cart, country_id, state_id);
          }
        });
      };
      //END ONLY CHECKOUT PAGE CODE

      // Open the form
      $(".ship_to").on("click", function(e) {
        e.preventDefault();
        $('#shipToModal').modal(); // Open the modal

        // Select the current id
        var country = $(this).attr('data-country');
        var state = $(this).attr('data-state');

        $("#shipTo_country").selectBoxIt().selectBoxIt('destroy'); //Reset the selectBoxIt
        $("#shipTo_country option[selected]").removeAttr("selected"); //Reset the old value
        $('#shipTo_country option[value="' + country + '"]').attr("selected", "selected"); //Select the current value
        $('#shipTo_country').selectBoxIt(); //Initialise the selectBoxIt

        // Populate states field if required
        if (state && $("#state_id_select_wrapper").hasClass('hidden')) {
          populateStateSelect(country, state);
        }

        // Set the cart id into the form
        $("input#cartinfo").val($(this).data('cart'));
      });

      // Submit
      $("#shipToForm").on("submit", function(e) {
        e.preventDefault();

        var data = $('form#shipToForm').serialize();

        var cart = $("input#cartinfo").val();
        var country_id = $("#shipTo_country").val();
        var state_id = $("#shipTo_state").val();

        // Check if the state is selected if exist
        if (state_id || $("#state_id_select_wrapper").hasClass('hidden')) {
          // Set the ship to text
          var text = state_id ? "#shipTo_state" : "#shipTo_country";
          $("#shipTo" + cart).text($(text + " option:selected").html());

          $('#shipToModal').modal('hide'); //Hide the modal

          // Set the ship to text
          var text = state_id ? "#shipTo_state" : "#shipTo_country";
          $("#shipTo").text($(text + " option:selected").html());

          // TEMPO
          setShippingZone(cart, country_id, state_id);

          updateCartOnServerside(cart);

          // Set shipping options for the zone
          // setShippingOptions(cart);

          // Remove the discount if the zone changes as discount can be depends on zone
          // resetDiscount(cart);
        }
      });

      //When change ship to Country
      $("#shipTo_country").change(function() {
        var id = $(this).val();
        $("#shipTo").attr('data-country', id).attr('data-state', null);
        populateStateSelect(id);
      });

      //When change ship to state
      $("#shipTo_state").change(function() {
        $("#shipTo").attr('data-state', $(this).val());
      });

      $("#login_to_shipp_btn").on('click', function(e) {
        e.preventDefault();

        $('#shipToModal').modal('hide');
        $('#loginModal').modal();
      });

      function populateStateSelect(country, state = null) {
        $.ajax({
          delay: 250,
          data: "id=" + country,
          url: "{{ route('ajax.getCountryStates') }}",
          success: function(result) {
            $("#shipTo_state").empty().selectBoxIt("refresh");
            if (result.length === 0) {
              // $("#shipTo").attr('data-state', null);
              // $("#shipTo_state").empty().selectBoxIt("refresh");
              $("#state_id_select_wrapper").removeClass('show').addClass('hidden').removeAttr('required');
            } else {
              $("#state_id_select_wrapper").removeClass('hidden').addClass('show');
              $("#shipTo_state").empty().attr('required', 'required').selectBoxIt("refresh");

              // Preparing the options and set the value
              var options = '<option value="">{{ trans('theme.select') }}</option>';
              for (var n in result) {
                options += '<option value="' + n + '">' + result[n] + '</option>';
              }
              $("#shipTo_state").append(options);

              // Pre select the state
              if (state)
                $('#shipTo_state option[value="' + state + '"]').attr("selected", "selected");

              $("#shipTo_state").selectBoxIt("refresh");
            }
          }
        });

        return;
      }

      function setShippingZone(cart, country_id, state_id) {
        var shop_id = $('#shop-id' + cart).val();
        var zone_id = $('#zone-id' + cart).val();

        // Set the values into the cart data
        $('input#shipto-country-id' + cart).val(country_id);
        $('input#shipto-state-id' + cart).val(state_id);

        var zone = getFromPHPHelper('get_shipping_zone_of', [shop_id, country_id, state_id]);
        zone = JSON.parse(zone);

        if ($.isEmptyObject(zone)) {
          @include('theme::layouts.notification', ['message' => trans('theme.notify.seller_doesnt_ship'), 'type' => 'warning', 'icon' => 'times-circle'])
        }

        // Skip and return if the zone is still the same
        if (zone.id == zone_id) return;

        $("#zone-id" + cart).val(zone.id);
        $("#tax-id" + cart).val(zone.tax_id);

        var options = getFromPHPHelper('getShippingRates', [zone.id]);
        $("#shipping-options" + cart).data('options', JSON.parse(options));

        // Set shipping options for the zone
        setShippingOptions(cart);

        // Remove the discount if the zone changes as discount can be depends on zone
        resetDiscount(cart);

        return;
      }

      // Update Item total on qty change
      $(".product-info-qty-input").on('change', function(e) {
        var cart = $(this).data('cart');
        var item = $(this).data('item');
        var qtt = $(this).val();
        var unitWeight = Number($("#unitWeight" + item).val());

        // Set Item Price
        var total = $('#item-price' + cart + '-' + item).data('value') * qtt;
        $('#item-total' + cart + '-' + item).text(getFormatedValue(total));

        // Set Item Weight
        var itemWeight = unitWeight * qtt;
        $("#itemWeight" + item).val(itemWeight);

        calculateCartTotal(cart);

        // Set shipping options for the zone
        setShippingOptions(cart);

        // Reset discount
        // resetDiscount(cart);
      });

      // Item remove from the cart
      $('.cart-item-remove').on('click', function(e) {
        e.preventDefault();
        var node = $(this);
        var cart = $(this).data('cart');
        var item = $(this).data('item');

        $.ajax({
          url: '{{ route('cart.removeItem') }}',
          type: 'POST',
          data: {
            'cart': cart,
            'item': item
          },
          dataType: 'JSON',
          complete: function(xhr, textStatus) {
            if (200 == xhr.status) {
              @include('theme::layouts.notification', ['message' => trans('theme.notify.item_removed_from_cart'), 'type' => 'success', 'icon' => 'check-circle'])

              node.parents('tr.cart-item-tr').remove();

              // Remove the cart if it has no items
              if ($('#table' + cart + ' tbody').children().length == 0) {
                $('#cartId' + cart).remove();

                // Check if no cart is there
                if ($('.shopping-cart-table-wrap').length == 0) {
                  location.reload();
                }
              } else {
                // Set shipping options for the zone
                setShippingOptions(cart);

                calculateCartTotal(cart);
              }

              // Decrease global cart item count by 1
              decreaseCartItem(1);
            } else {
              @include('theme::layouts.notification', ['message' => trans('theme.notify.failed'), 'type' => 'warning', 'icon' => 'times-circle'])
            }
          },
        });
      });

      // Coupon
      $('.apply_seller_coupon').on('click', function(e) {
        e.preventDefault();
        var cart = $(this).data('cart');
        var coupon = $('#coupon' + cart).val();
        var shop = $('#shop-id' + cart).val();
        var zone = $('#zone-id' + cart).val();
        // var totalPrice  = getOrderTotal(cart);
        coupon = coupon.trim();

        if (coupon) {
          $.ajax({
              url: '{{ route('validate.coupon') }}',
              type: 'POST',
              data: {
                'coupon': coupon,
                'shop': shop,
                'cart': cart,
                'zone': zone
              },
              dataType: 'JSON',
              success: function(data, textStatus, xhr) {
                applyDiscountOnCart(cart, data);
              }
            })
            .fail(function(response) {
              if (401 === response.status) {
                $('#loginModal').modal('show');
              } else if (500 === response.status) {
                // console.log(response);
              } else if (403 === response.status) {
                @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_not_valid'), 'type' => 'warning', 'icon' => 'times-circle'])
              } else if (404 === response.status) {
                @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_not_exist'), 'type' => 'danger', 'icon' => 'times-circle'])
              } else if (443 === response.status) {
                @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_not_valid_for_zone'), 'type' => 'warning', 'icon' => 'times-circle'])
              } else if (444 === response.status) {
                @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_limit_expired'), 'type' => 'warning', 'icon' => 'times-circle'])
              } else {
                @include('theme::layouts.notification', ['message' => trans('theme.notify.failed'), 'type' => 'danger', 'icon' => 'times-circle'])
              }

              // resetDiscount(cart);
            });
        }
      });

      // Popover
      $('[data-toggle="popover"]').on('click', function() {
        $('.popover').not(this).popover('hide');
      });

      var apply_btn = '<div class="space5"></div><button class="popover-submit-btn btn btn-black btn-block flat" type="button">{{ trans('theme.button.ok') }}</button>';

      // Do appropriate actions and Update order detail
      $(document).on("click", ".popover-submit-btn", function() {
        var node = $(this).parents('.popover-form');
        var nodeId = node.attr('id');
        var cart = node.data('cart');

        switch (nodeId) {
          case 'shipping-options-popover':
            var shipping = node.find('input[name=shipping_option]:checked');
            var name = shipping.attr('id') == 'custom_shipping' ? '{{ trans('theme.custom_shipping') }}' : shipping.attr('id');
            var id = shipping.parent('label').attr('id');
            setShippingCostThenSave(cart, name, shipping.val(), id);
            break;

          case 'packaging-options-popover':
            var packaging = node.find('input[name=packaging_option]:checked');
            var id = packaging.parent('label').attr('id');
            setPackagingCost(cart, packaging.attr('id'), packaging.val(), id);
            break;

            // case 'discount-options-popover':
            //   setDiscount(node.find('input#input-discount').val());
            //   break;
        }

        // Hide the popover
        $('[data-toggle="popover"]').popover('hide');
      });

      $('a.packaging-options').popover({
        html: true,
        placement: 'bottom',
        content: function() {
          var cart = $(this).data('cart');
          var current = getPackagingName(cart);
          var preChecked = String(current) == String('{{ trans('theme.basic_packaging') }}') ? 'checked' : '';

          var options = '<table class="table table-striped">' +
            '<tr><td><div class="radio"><label id="1"><input type="radio" name="packaging_option" id="{{ trans('theme.basic_packaging') }}" value="' + getFormatedValue(0) + '" ' + preChecked + '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('theme.basic_packaging') }}</label></div></td>' +
            '<td><span>{{ get_currency_prefix() }}' + getFormatedValue(0) + '{{ get_currency_suffix() }}</span></td></tr>';

          $(this).data('options').forEach(function(item) {
            preChecked = String(current) == String(item.name) ? 'checked' : '';

            options += '<tr><td><div class="radio"><label id="' + item.id + '"><input type="radio" name="packaging_option" id="' + item.name + '" value="' + getFormatedValue(item.cost) + '" ' + preChecked + '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + item.name + '</label></div></td>' +
              '<td><span>{{ get_currency_prefix() }}' + getFormatedValue(item.cost) + '{{ get_currency_suffix() }}</span></td></tr>';
          });
          options += '</table>';

          return '<div class="popover-form" id="packaging-options-popover" data-cart="' + cart + '">' +
            options + apply_btn +
            '</div>';
        }
      }).on('mouseenter', function() {
        var _this = this;
        $(this).popover('show');
        $('.popover').on('mouseleave', function() {
          $(_this).popover('hide');
        });
      }).on('mouseleave', function() {
        var _this = this;
        setTimeout(function() {
          if (!$('.popover:hover').length) {
            $(_this).popover('hide');
          }
        }, 100);
      });

      $('a.dynamic-shipping-rates').popover({
        html: true,
        placement: 'bottom',
        content: function() {
          var cart = $(this).data('cart');
          var current = getShippingName(cart);
          var preChecked = String(current) == String('{{ trans('theme.free_shipping') }}') ? 'checked' : '';

          var filtered = getShippingOptions(cart);
          var free_shipping = isFreeShipping(cart);
          var handlingCost = $('#handling-cost' + cart).val();

          if ($.isEmptyObject(filtered)) {
            var options = '<p class="space10"><span class="space10"></span>{{ trans('theme.seller_doesnt_ship') }}</p>';
          } else {
            var options = '<table class="table table-striped" id="checkout-options-table">';

            if (free_shipping) {
              options += '<tr><td><div class="radio"><label id="0"><input type="radio" name="shipping_option" id="{{ trans('theme.free_shipping') }}" value="' + getFormatedValue(0) + '" ' + preChecked + '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('theme.free_shipping') }}</label></div></td>' +
                '<td>&nbsp;</td><td>&nbsp;</td>' +
                '<td><span>{{ get_currency_prefix() }}' + getFormatedValue(0) + '{{ get_currency_suffix() }}</span></td></tr>';
            }

            filtered.forEach(function(item) {
              preChecked = String(current) == String(item.name) ? 'checked' : '';
              var shippingRate = Number(item.rate) + Number(handlingCost);

              options += '<tr><td><div class="radio"><label id="' + item.id + '"><input type="radio" name="shipping_option" id="' + item.name + '" value="' + getFormatedValue(item.rate) + '" ' + preChecked + '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + item.name + '</label></div></td>' +
                '<td>' + item.carrier.name + '</td>' +
                '<td><small class"text-muted">' + item.delivery_takes + '</small></td>' +
                '<td><span>{{ get_currency_prefix() }}' + getFormatedValue(shippingRate) + '{{ get_currency_suffix() }}</span></td></tr>';
            });
            options += '</table>';
          }

          return '<div class="popover-form" id="shipping-options-popover" data-cart="' + cart + '">' +
            options + apply_btn + '</div>';
        }
      }).on('mouseenter', function() {
        var _this = this;
        $(this).popover('show');
        $('.popover').on('mouseleave', function() {
          $(_this).popover('hide');
        });
      }).on('mouseleave', function() {
        var _this = this;
        setTimeout(function() {
          if (!$('.popover:hover').length) {
            $(_this).popover('hide');
          }
        }, 100);
      });

      // Functions
      function isFreeShipping(cart) {
        // Checkout page
        if ($("#checkout-id").length == 1) {
          return $("#freeShipping" + cart).val();
        }

        // Cart page
        var notFree = $(".freeShipping" + cart).filter(function() {
          return this.value != 1;
        });

        if (notFree.length == 0) {
          return true;
        }

        return false;
      }

      function getShippingOptions(cart) {
        var totalPrice = getOrderTotal(cart);
        var cartWeight = getCartWeight(cart);
        var shippingOptions = $("#shipping-options" + cart).data('options');

        if (!shippingOptions || $.isEmptyObject(shippingOptions)) {
          return shippingOptions;
        }

        var filtered = shippingOptions.filter(function(el) {
          var result = el.based_on == 'price' && el.minimum <= totalPrice && (!el.maximum || el.maximum >= totalPrice);

          if (cartWeight) {
            result = result || (el.based_on == 'weight' && el.minimum <= cartWeight && el.maximum >= cartWeight);
          }

          return result;
        });

        return filtered;
      }

      function calculateTax(cart) {
        var total = getOrderTotal(cart);
        var taxrate = getTaxrate(cart);

        var tax = (total * taxrate) / 100;

        if (tax > 0) {
          $("#tax-section-li" + cart).show();
        } else {
          $("#tax-section-li" + cart).hide();
        }

        $('#summary-taxes' + cart).text(getFormatedValue(tax));

        calculateOrderSummary(cart);
        return;
      };

      function calculateCartTotal(cart) {
        var total = 0;
        $('.item-total' + cart).each(function() {
          total += Number($(this).text());
        });

        $('#summary-total' + cart).text(getFormatedValue(total));

        calculateDiscount(cart);

        calculateTax(cart);
      }

      function calculateOrderSummary(cart) {
        var grand = getTotalAmount(cart) + getTax(cart);
        $("#summary-grand-total" + cart).text(getFormatedValue(grand));
        return;
      }

      function calculateDiscount(cart) {
        var coupon = $("#coupon-raw" + cart).val();

        coupon = JSON.parse(coupon);

        if (!coupon) {
          return;
        }

        var totalPrice = getOrderTotal(cart);

        if (coupon.min_order_amount && totalPrice < coupon.min_order_amount) {
          @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_min_order_value'), 'type' => 'danger', 'icon' => 'times-circle'])

          resetDiscount(cart);
          return;
        }

        var name = coupon.name;
        if ('percent' == coupon.type) {
          name += ' (' + getFormatedValue(coupon.value) + '%)';
          coupon.value = (coupon.value * (totalPrice / 100));
        } else {
          name += ' ({{ get_currency_prefix() }}' + getFormatedValue(coupon.value) + '{{ get_currency_suffix() }})';
        }

        if (coupon.value > 0) {
          $("#discount-section-li" + cart).show();
        } else {
          $("#discount-section-li" + cart).hide();
        }

        $('#summary-discount' + cart).text(getFormatedValue(coupon.value));
        $('#summary-discount-name' + cart).text(name);
        // $('#discount-id' + cart).val(coupon.id);
      }

      function getCartWeight(cart) {
        // Checkout page
        if ($("#checkout-id").length == 1) {
          return Number($("#cartWeight" + cart).val());
        }

        // Cart page
        var cartWeight = 0;
        $(".itemWeight" + cart).each(function() {
          cartWeight += ($(this).val() * 1);
        });

        return cartWeight;
      }

      function getTotalAmount(cart) {
        var total = getOrderTotal(cart);
        if (!total) {
          return total;
        }

        var packaging = getPackaging(cart);
        var shipping = getShipping(cart);
        var discount = getDiscount(cart);

        return (total + shipping + packaging) - discount;
      }

      function getPackagingName(cart) {
        return $("#summary-packaging-name" + cart).text().trim();
      };

      function getPackaging(cart) {
        return Number($("#summary-packaging" + cart).text());
      };

      function getShipping(cart) {
        return Number($("#summary-shipping" + cart).text());
      };

      function getShippingName(cart) {
        return $("#summary-shipping-name" + cart).text().trim();
      };

      function getTaxId(cart) {
        return $("#tax-id" + cart).val();
      };

      function getTaxrate(cart) {
        return Number($("#cart-taxrate" + cart).val());
      };

      function getTax(cart) {
        return Number($("#summary-taxes" + cart).text());
      };

      function getDiscount(cart) {
        return Number($("#summary-discount" + cart).text());
      }

      function getOrderTotal(cart) {
        return Number($("#summary-total" + cart).text());
      };

      // Setters
      function setPackagingCost(cart, name, value = 0, id = '') {
        value = value ? value : 0;
        $('#summary-packaging' + cart).text(getFormatedValue(value));
        $('#summary-packaging-name' + cart).text(name);
        $('#packaging-id' + cart).val(id);

        calculateTax(cart);
        updateCartOnServerside(cart);
        return;
      }

      function setShippingOptions(cart) {
        var filtered = getShippingOptions(cart);

        if (!$.isEmptyObject(filtered)) {
          filtered.sort(function(a, b) {
            return a.rate - b.rate
          });

          if (isFreeShipping(cart)) {
            setShippingCostThenSave(cart, '{{ trans('theme.free_shipping') }}', 0, 0);
          } else {
            setShippingCostThenSave(cart, filtered[0].name, filtered[0].rate, filtered[0].id);
          }

          enableCartCheckout(cart);
        } else {
          setShippingCostThenSave(cart);
          disableCartCheckout(cart);
        }

        setTaxes(cart);
      }

      function setShippingCostThenSave(cart, name = '', value = 0, id = '') {
        setShippingCost(cart, name, value, id);
        updateCartOnServerside(cart);
      }

      function setShippingCost(cart, name = '', value = 0, id = '') {
        var handlingCost = isFreeShipping(cart) && value == 0 ? 0 : $('#handling-cost' + cart).val();
        value = Number(value) + Number(handlingCost);
        $('#summary-shipping' + cart).text(getFormatedValue(value));
        $('#summary-shipping-name' + cart).text(name);
        $('#shipping-rate-id' + cart).val(id);
        calculateTax(cart);
        return;
      }

      function setTaxes(cart) {
        var totalPrice = getOrderTotal(cart);
        var tax_id = getTaxId(cart);

        if (!tax_id) {
          $('#summary-taxrate' + cart).text(0);
          calculateTax(cart);
          return;
        }

        $.ajax({
          url: "{{ route('ajax.getTaxRate') }}",
          data: {
            'ID': tax_id
          },
          success: function(result) {
            $('#summary-taxrate' + cart).text(result);
            $('#cart-taxrate' + cart).val(result);

            calculateTax(cart);
          }
        });

        return;
      }

      function applyDiscountOnCart(cart, coupon = null) {
        if (coupon == null) {
          @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_not_valid'), 'type' => 'danger', 'icon' => 'times-circle'])

          resetDiscount(cart);
          return;
        }

        // var name = coupon.name;
        // if ('percent' == coupon.type) {
        //   name += ' (' + getFormatedValue(coupon.value) + '%)';
        // } else {
        //   name += ' ({{ get_currency_prefix() }}' + getFormatedValue(coupon.value) + '{{ get_currency_suffix() }})';
        // }

        // $('#summary-discount-name' + cart).text(name);
        $('#coupon-raw' + cart).val(JSON.stringify(coupon));
        // // $('#discount-id' + cart).val(coupon.id);

        calculateCartTotal(cart);

        @include('theme::layouts.notification', ['message' => trans('theme.notify.coupon_applied'), 'type' => 'success', 'icon' => 'check-circle'])

        return;
      }

      function resetDiscount(cart) {
        $("#discount-section-li" + cart).hide();

        if ($('#coupon-raw' + cart).val()) {
          $('#summary-discount' + cart).text(getFormatedValue(0));
          $('#summary-discount-name' + cart).text('');
          $('#coupon-raw' + cart).val('');
          // $('#discount-id' + cart).val('');
          calculateTax(cart);
        }
        return;
      }

      function disableCartCheckout(cart) {
        disableCartPayment("{{ trans('theme.notify.seller_doesnt_ship') }}"); // For checkout page only

        $('#checkout-btn' + cart).attr("disabled", "disabled");
        $('#table' + cart + ' > tfoot').addClass('hidden');

        // Disanle all checkout option
        $('#allCheckoutBtn').attr("disabled", "disabled");
        $('#allCheckoutDisable').removeClass('hidden');

        var shop = $('#shop-id' + cart).val();
        if (!shop) {
          $('#store-unavailable-notice' + cart).removeClass('hidden');
          $('#table' + cart + ' td, #cart-summary' + cart).addClass('text-disable');
        } else {
          $('#shipping-notice' + cart).removeClass('hidden');
        }
      }

      function enableCartCheckout(cart) {
        enableCartPayment(); // For checkout page only

        $('#checkout-btn' + cart).removeAttr("disabled");
        $('#table' + cart + ' > tfoot').removeClass('hidden');
        $('#shipping-notice' + cart).addClass('hidden');

        // For oneCheckout option
        let all_clear = true;

        // Check if any cart is disabled
        $('.shopping-cart-table-wrap').each(function(e) {
          var temp = $(this).data('cart');
          if ($('#checkout-btn' + temp).is('[disabled=disabled]')) {
            all_clear = false;
            return false; // Skip the loop
          }
        });

        // Enable all check
        if (all_clear) {
          $('#allCheckoutBtn').removeAttr("disabled");
          $('#allCheckoutDisable').addClass('hidden');
        }
      }

      function disableCartPayment(msg = '') {
        $('#checkout-notice-msg').html(msg);
        $("#checkout-notice").show();
        $('#pay-now-btn, #paypal-express-btn').hide();
      }

      function enableCartPayment() {
        $("#checkout-notice").hide();
        $('#pay-now-btn, #paypal-express-btn').show();
      }

      // Update cart info on server side
      function updateCartOnServerside(cart) {
        let temproute = "{{ route('cart.update', '_CART_') }}";
        let formdata = $("form#formId" + cart).serializeArray();

        $.ajax({
            url: temproute.replace('_CART_', cart),
            type: 'PUT',
            data: formdata,
            success: function(result) {
              // console.log(result);
            }
          })
          .fail(function(response) {
            console.log(response.responseText);
            @include('theme::layouts.notification', ['message' => trans('theme.cart_update_failed'), 'type' => 'warning', 'icon' => 'times-circle'])
          });

        return;
      }

    });
  }(window.jQuery, window, document));
</script>
