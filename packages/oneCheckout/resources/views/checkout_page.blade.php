@php
$item_count = 0;
$item_count_txt = '(';

$quantity = 0;
$quantity_txt = '(';

$sub_total = 0;
$sub_total_txt = '(';

$taxes = 0;
$taxes_txt = '(';

$shipping_cost = 0;
$shipping_cost_txt = '(';

$discount = 0;
$discount_txt = '(';

$packaging_cost = 0;
$packaging_cost_txt = '(';

$grand_total = 0;

foreach ($carts as $key => $cart) {
    $item_count += $cart->item_count;
    $item_count_txt .= $cart->item_count . '+';

    $quantity += $cart->quantity;
    $quantity_txt .= $cart->quantity . '+';

    if ($cart->packaging) {
        $packaging_cost += $cart->packaging;
        $packaging_cost_txt .= get_formated_currency($cart->packaging, 2) . '+';
    } else {
        $packaging_cost_txt .= get_formated_currency(0) . '+';
    }

    $sub_total += $cart->total;
    $sub_total_txt .= get_formated_currency($cart->total, 2) . '+';

    $taxes += $cart->taxes;
    $taxes_txt .= get_formated_currency($cart->taxes, 2) . '+';

    $temp_shipping = $cart->get_shipping_cost();
    $shipping_cost += $temp_shipping;
    $shipping_cost_txt .= get_formated_currency($temp_shipping, 2) . '+';

    if ($cart->coupon_id && $cart->discount) {
        $discount += $cart->discount;
        $discount_txt .= get_formated_currency($cart->discount, 2) . '+';
    } else {
        $discount_txt .= get_formated_currency(0) . '+';
    }

    $grand_total += $cart->calculate_grand_total();
}
@endphp

<section>
  <div class="container mb-5">
    @if (Session::has('error'))
      <div class="notice notice-danger notice-sm">
        <strong>{{ trans('theme.error') }}</strong> {{ Session::get('error') }}
      </div>
    @endif

    <div class="notice notice-warning notice-lg space20" id="checkout-notice" style="display: none;">
      <strong>{{ trans('theme.warning') }}</strong>
      <span id="checkout-notice-msg">@lang('checkout::lang.checkout_all_not_possible')</span>
    </div>

    {!! Form::open(['method' => 'POST', 'route' => [config('checkout.routes.place_order')], 'id' => 'checkoutForm', 'name' => 'checkoutForm', 'files' => true, 'data-toggle' => 'validator', 'novalidate']) !!}
    <div class="row shopping-cart-table-wrap mb-4" id="cartId">
      <div class="col-md-4 bg-light">
        <div class="seller-info my-3">
          <div class="text-muted small mb-3">
            <i class="far fa-store"></i> {{ trans('theme.sold_by') }}
          </div>

          @foreach ($carts as $temp)
            <a href="{{ route('show.store', $temp->shop->slug) }}" class="seller-info-name mr-3">
              <img src="{{ get_storage_file_url(optional($temp->shop->logoImage)->path, 'thumbnail') }}" class="seller-info-logo img-sm" data-toggle="tooltip" title="{{ $temp->shop->name }}" alt="{{ trans('theme.logo') }}">
            </a>
          @endforeach
        </div><!-- /.seller-info -->

        <hr class="style3 mt-4" />

        <h3 class="widget-title">
          {{ trans('theme.order_detail') }}:

          <span class="pull-right">{{ trans('theme.cart_count') . '(' . $carts->count() . ')' }}</span>
        </h3>

        <ul class="shopping-cart-summary ">
          <li>
            <span>
              {{ trans('theme.item_count') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $item_count_txt) }}</div>
            </span>
            <span>{{ $item_count }}</span>
          </li>

          <li>
            <span>
              {{ trans('theme.quantity') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $quantity_txt) }}</div>
            </span>
            <span>{{ $quantity }}</span>
          </li>

          <li>
            <span>
              {{ trans('theme.subtotal') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $sub_total_txt) }}</div>
            </span>
            <span>{{ get_formated_currency($sub_total, 2) }}</span>
          </li>

          <li>
            <span>
              {{ trans('theme.shipping') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $shipping_cost_txt) }}</div>
            </span>
            <span>{{ get_formated_currency($shipping_cost, 2) }}</span>
          </li>

          <li>
            <span>
              {{ trans('theme.packaging') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $packaging_cost_txt) }}</div>
            </span>
            <span>{{ get_formated_currency($packaging_cost, 2) }}</span>
          </li>

          <li id="discount-section-li" style="display: {{ $discount > 0 ? 'block' : 'none' }};">
            <span>
              {{ trans('theme.discount') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $discount_txt) }}</div>
            </span>
            <span>-{{ get_formated_currency($discount, 2) }}</span>
          </li>

          <li id="tax-section-li" style="display: {{ $taxes > 0 ? 'block' : 'none' }};">
            <span>
              {{ trans('theme.taxes') }}
              <div class="small text-info">{{ \Str::replaceLast('+', ')', $taxes_txt) }}</div>
            </span>
            <span>{{ get_formated_currency($taxes, 2) }}</span>
          </li>

          <li>
            <span class="lead">{{ trans('theme.total') }}</span>
            <span class="lead">{{ get_formated_currency($grand_total, 2) }}</span>
          </li>
        </ul>

        <hr class="style1 muted" />

        <div class="text-center mb-3">
          <a class="btn btn-black flat" href="{{ route('cart.index') }}">{{ trans('theme.button.update_cart') }}</a>
          <a class="btn btn-black flat" href="{{ url('/') }}">{{ trans('theme.button.continue_shopping') }}</a>
        </div>
      </div> <!-- /.col-md-3 -->

      <div class="col-md-5">
        <h3 class="widget-title">
          <i class="far fa-shipping-fast"></i> {{ trans('theme.ship_to') }}

          <em class="text-primary text-italic">
            @if ($cart->ship_to_state_id)
              {{ $cart->state->name }}
            @elseif($cart->ship_to_country_id)
              {{ $cart->country->name }}
            @endif
          </em>
        </h3>

        @if (isset($customer))
          @php
            $pre_select = null;
          @endphp

          <div class="row customer-address-list">
            @foreach ($customer->addresses as $address)
              <div class="col-sm-12 col-md-6 nopadding-{{ $loop->iteration % 2 == 1 ? 'right' : 'left' }}">
                @if ($cart->ship_to_country_id == $address->country_id && $cart->ship_to_state_id == $address->state_id)
                  @php
                    $ship_to_this_address = null;
                    if (!$pre_select && $cart->ship_to_country_id == $address->country_id && $cart->ship_to_state_id == $address->state_id) {
                        $pre_select = 1;
                        $ship_to_this_address = true;
                    }
                  @endphp

                  <div class="address-list-item {{ $ship_to_this_address ? 'selected' : '' }} selectable">
                    {!! $address->toHtml('<br/>', false) !!}

                    <input type="radio" class="ship-to-address" name="ship_to" value="{{ $address->id }}" {{ $ship_to_this_address ? 'checked' : '' }} data-country="{{ $address->country_id }}" data-state="{{ $address->state_id }}" required>
                  </div>
                @else
                  <div class="address-list-item hidden-xs" style="cursor: not-allowed;">
                    {!! $address->toHtml('<br/>', false) !!}
                  </div>
                @endif
              </div>

              @if ($loop->iteration % 2 == 0)
                <div class="clearfix"></div>
              @endif
            @endforeach
          </div>

          <div class="alert alert-warning {{ $pre_select ? 'hidden' : '' }}" role="alert">
            {{ trans('checkout::lang.no_delivery_address_on_zone') }}
          </div>

          <small id="ship-to-error-block" class="text-danger pull-right"></small>

          <div class="col-sm-12 my-4 d-flex justify-content-center">
            <a href="{{ route('my.address.create') }}" class="modalAction btn btn-default flat">
              <i class="fas fa-address-card-o"></i> @lang('theme.button.add_new_address')
            </a>
          </div>
        @else
          @include('partials.checkout_shiping_address', ['one_checkout_form' => true])
        @endif

        <hr class="style4 muted" />

        @if (is_incevio_package_loaded('pharmacy'))
          @include('pharmacy::checkout_form')
        @endif

        <div class="form-group">
          {!! Form::label('buyer_note', trans('theme.leave_message_to_seller')) !!}
          {!! Form::textarea('buyer_note', null, ['class' => 'form-control flat summernote-without-toolbar', 'placeholder' => trans('theme.placeholder.message_to_seller'), 'rows' => '2', 'maxlength' => '250']) !!}
          <div class="help-block with-errors"></div>
        </div>
      </div> <!-- /.col-md-5 -->

      <div class="col-md-3">
        @include('partials.payment_options')
      </div> <!-- /.col-md-4 -->
    </div><!-- /.row -->
    {!! Form::close() !!}
  </div>
</section>
