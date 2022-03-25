@if ($coupons->count() > 0)
  <table class="table" id="buyer-order-table">
    <thead>
      <tr>
        <th>{{ trans('theme.value') }}</th>
        <th>{{ trans('theme.store') }}</th>
        <th>{{ trans('theme.coupon_code') }}</th>
        <th width="30%">{{ trans('theme.validity') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($coupons as $coupon)
        <tr>
          <td class="text-center">
            @php
              $value = $coupon->type == 'amount' ? get_formated_currency($coupon->value, true, 2) : get_formated_decimal($coupon->value) . '%';
            @endphp

            <div class="customer-coupon-lists {{ $coupon->ending_time < \Carbon\Carbon::now() ? 'customer-coupons-expired' : '' }}">
              <div class="coupon-item">
                <span class="customer-coupons-limit">
                  @if ($coupon->min_order_amount)
                    {{ trans('theme.when_min_order_value', ['value' => get_formated_currency($coupon->min_order_amount, true, 2)]) }}
                  @endif
                </span>
                <span class="customer-coupon-value">{{ trans('theme.coupon_off', ['value' => $value]) }}</span>
              </div>
            </div>
          </td>
          <td class="vertical-center">
            <a href="{{ route('show.store', $coupon->shop->slug) }}" target="_blank">{{ $coupon->shop->name }}</a>
            <small><i class="far fa-external-link text-muted"></i></small>
          </td>
          <td class="text-center vertical-center">{{ $coupon->code }}</td>
          <td class="vertical-center"> {!! $coupon->validityText() !!}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="sep"></div>
@else
  <div class="clearfix space50"></div>
  <p class="lead text-center space50">
    @lang('theme.nothing_found')
  </p>
@endif

<div class="row pagenav-wrapper">
  {{ $coupons->links('theme::layouts.pagination') }}
</div><!-- /.row .pagenav-wrapper -->
<div class="clearfix space20"></div>
