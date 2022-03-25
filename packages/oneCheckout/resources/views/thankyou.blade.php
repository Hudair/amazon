<section>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <p class="lead">@lang('theme.notify.order_placed_thanks')</p>
        @php
          $order = $orders[0];
          $payment_instructions = null;
          if (optional($order->paymentMethod)->type == \App\Models\PaymentMethod::TYPE_MANUAL) {
              $payment_instructions = get_from_option_table('wallet_payment_info_' . $order->paymentMethod->code);
          }
        @endphp

        @if ($payment_instructions)
          <p class="text-primary space50">
            <strong>@lang('theme.payment_instruction'): </strong>
            {!! $payment_instructions !!}
          </p>
        @elseif(!$order->isPaid())
          <p class="text-danger space50">
            <strong>@lang('theme.payment_status'): </strong> {!! $order->paymentStatusName() !!}
          </p>
        @endif

        @if ($order->pickup())
          @php
            $warehouseIds = [];
          @endphp

          @foreach ($order->inventories as $key => $inventory)
            @if (!empty($inventory->warehouse))
              @if (!in_array($inventory->warehouse_id, $warehouseIds))
                @php
                  $warehouseIds[] = $inventory->warehouse_id;
                @endphp

                <p class="small space10" style="margin-top: 10px"><i class="fas fa-info-circle"></i>
                  {{ trans('theme.notify.business_days') }}: <em>{{ $inventory->warehouse->business_days }}</em>
                </p>
                <p class="small space10"><i class="fas fa-info-circle"></i>
                  {{ trans('theme.notify.availability') }}: <em>{{ $inventory->warehouse->opening_time }} - {{ $inventory->warehouse->close_time }}</em>
                </p>
                <p class="small space10"><i class="fas fa-info-circle"></i>
                  {{ trans('theme.notify.order_number') }}: <em>{{ $order->order_number }}</em>
                </p>
                <p class="small space10"><i class="fas fa-info-circle"></i>
                  {{ trans('theme.notify.pick_up_order_from') }}: <br />
                  <em>{!! address_str_to_html($inventory->warehouse->address->toString()) !!}</em>
                </p>
              @endif
            @endif
          @endforeach
        @else
          <p class="small space30"><i class="fas fa-info-circle"></i>
            {{ trans('theme.notify.order_will_ship_to') }}: <em>"{!! $order->shipping_address !!}"</em>
          </p>
        @endif

        <p class="lead text-center space50">
          <a class="btn btn-primary flat" href="{{ url('/') }}">{{ trans('theme.button.continue_shopping') }}</a>

          @if (\Auth::guard('customer')->check())
            <a class="btn btn-default flat" href="{{ route('account', 'orders') }}">@lang('theme.button.order_detail')</a>
          @endif
        </p>
      </div><!-- /.col-md-8 -->
    </div><!-- /.row -->
  </div> <!-- /.container -->
</section>
