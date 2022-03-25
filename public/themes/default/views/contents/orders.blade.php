@if ($orders->count() > 0)
  <div class="row justify-content-end mb-3">
    <div class="col-md-4 text-right">
      <form action="{{ url('/my/orders') }}" method="get">
        <div class="form-inline">
          <input type="text" class="form-control" name="q" placeholder="Search by order id...">
          <button type="submit" class="btn btn-success d-inline"><i class="fa fa-search"></i></button>
        </div>
      </form>
    </div>
  </div>

  <table class="table" id="buyer-order-table">
    <thead>
      <tr>
        <th colspan="3">@lang('theme.your_order_history')</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orders as $order)
        <tr class="order-info-head">
          <td width="40%">
            <h5 class="mb-2">
              <span>@lang('theme.order_id'): </span>
              <a class="btn-link" href="{{ route('order.detail', $order) }}">{{ $order->order_number }}</a>
              @if ($order->hasPendingCancellationRequest())
                <span class="label label-warning indent10 text-uppercase">
                  {{ trans('theme.' . $order->cancellation->request_type . '_requested') }}
                </span>
              @elseif($order->hasClosedCancellationRequest())
                <span class="indent10">
                  {{ trans('theme.' . $order->cancellation->request_type) }}
                </span>
                {!! $order->cancellation->statusName() !!}
              @elseif($order->isCanceled())
                <span class="indent10">{!! $order->orderStatus() !!}</span>
              @endif
              @if ($order->dispute)
                <span class="label label-danger indent10 text-uppercase">@lang('theme.disputed')</span>
              @endif
            </h5>
            <h5><span>@lang('theme.order_time_date'): </span>{{ $order->created_at->toDayDateTimeString() }}</h5>
          </td>
          <td width="40%" class="store-info">
            <h5 class="mb-2">
              <span>@lang('theme.store'):</span>
              @if ($order->shop->slug)
                <a href="{{ route('show.store', $order->shop->slug) }}"> {{ $order->shop->name }}</a>
              @else
                @lang('theme.store_not_available')
              @endif
            </h5>
            <h5>
              <span>@lang('theme.status')</span>
              {!! $order->orderStatus(true) . ' &nbsp; ' . $order->paymentStatusName() !!}
            </h5>
          </td>
          <td width="20%" class="order-amount">
            <h5 class="mb-2"><span>@lang('theme.order_amount'): </span>{{ get_formated_currency($order->grand_total, true, 2) }}</h5>
            <div class="text-center">
              <div class="btn-group" role="group">
                <a class="btn btn-xs btn-default flat" href="{{ route('order.detail', $order) }}">@lang('theme.button.order_detail')</a>
                @if ($order->dispute)
                  <a href="{{ route('dispute.open', $order) }}" class="btn btn-xs btn-default flat" data-confirm="@lang('theme.confirm_action.open_a_dispute')">@lang('theme.dispute_detail')</a>
                @else
                  <a href="{{ route('dispute.open', $order) }}" class="confirm btn btn-xs btn-default flat" data-confirm="@lang('theme.confirm_action.open_a_dispute')">@lang('theme.button.open_dispute')</a>
                @endif
              </div>
            </div>
          </td>
        </tr> <!-- /.order-info-head -->

        @foreach ($order->inventories as $item)
          <tr class="order-body">
            <td colspan="2">
              <div class="product-img-wrap">
                <img src="{{ get_storage_file_url(optional($item->image)->path, 'small') }}" alt="{{ $item->slug }}" title="{{ $item->slug }}" />
              </div>
              <div class="product-info">
                <a href="{{ route('show.product', $item->slug) }}" class="product-info-title" style="display: inline;">{{ $item->pivot->item_description }}</a>

                @if ($order->cancellation && $order->cancellation->isItemInRequest($item->id))
                  <span class="label label-danger indent10">
                    {{ trans('theme.' . $order->cancellation->request_type . '_requested') }}
                  </span>
                @endif

                <div class="order-info-amount">
                  <span>{{ get_formated_currency($item->pivot->unit_price, true, 2) }} x {{ $item->pivot->quantity }}</span>
                </div>
                {{-- <ul class="order-info-properties">
                          <li>Size: <span>L</span></li>
                          <li>Color: <span>RED</span></li>
                      </ul> --}}
              </div>
            </td>

            @if ($loop->first)
              <td rowspan="{{ $loop->count }}" class="order-actions">
                <a href="{{ route('order.again', $order) }}" class="btn btn-default btn-sm btn-block flat">
                  <i class="fas fa-shopping-cart"></i> @lang('theme.order_again')
                </a>

                @unless($order->isCanceled())
                  <a href="{{ route('order.invoice', $order) }}" class="btn btn-default btn-sm btn-block flat">
                    <i class="fas fa-cloud-download"></i> @lang('theme.invoice')
                  </a>

                  @if ($order->canBeCanceled())

                    {!! Form::model($order, ['method' => 'PUT', 'route' => ['order.cancel', $order]]) !!}
                    {!! Form::button('<i class="fas fa-times-circle-o"></i> ' . trans('theme.cancel_order'), ['type' => 'submit', 'class' => 'confirm btn btn-default btn-block flat', 'data-confirm' => trans('theme.confirm_action.cant_undo')]) !!}
                    {!! Form::close() !!}

                  @elseif($order->canRequestCancellation())

                    <a href="{{ route('cancellation.form', ['order' => $order, 'action' => 'cancel']) }}" class="modalAction btn btn-default btn-sm btn-block flat"><i class="fas fa-times"></i> @lang('theme.cancel_items')</a>

                  @endif

                  @if ($order->canTrack())
                    <a href="{{ route('order.track', $order) }}" class="btn btn-black btn-sm btn-block flat">
                      <i class="fas fa-map-marker"></i> @lang('theme.button.track_order')
                    </a>
                  @endif

                  @if ($order->canEvaluate())
                    <a href="{{ route('order.feedback', $order) }}" class="btn btn-primary btn-sm btn-block flat">
                      @lang('theme.button.give_feedback')
                    </a>
                  @endif

                  @if ($order->isFulfilled())
                    @if ($order->canRequestReturn())
                      <a href="{{ route('cancellation.form', ['order' => $order, 'action' => 'return']) }}" class="modalAction btn btn-default btn-sm btn-block flat"><i class="fas fa-undo"></i> @lang('theme.return_items')</a>
                    @endif

                    @unless($order->goods_received)
                      {!! Form::model($order, ['method' => 'PUT', 'route' => ['goods.received', $order]]) !!}
                      {!! Form::button(trans('theme.button.confirm_goods_received'), ['type' => 'submit', 'class' => 'confirm btn btn-primary btn-block flat', 'data-confirm' => trans('theme.confirm_action.goods_received')]) !!}
                      {!! Form::close() !!}
                    @endunless
                  @endif

                @endunless

                <a href="{{ route('order.detail', $order) . '#message-section' }}" class="btn btn-link btn-block">
                  <i class="fas fa-envelope-o"></i> @lang('theme.button.contact_seller')
                </a>
              </td>
            @endif
          </tr> <!-- /.order-body -->
        @endforeach

        @if ($order->message_to_customer)
          <tr class="message_from_seller">
            <td colspan="3">
              <p>
                <strong>@lang('theme.message_from_seller'): </strong> {{ $order->message_to_customer }}
              </p>
            </td>
          </tr>
        @endif

        @if ($order->buyer_note)
          <tr class="order-info-footer">
            <td colspan="3">
              <p class="order-detail-buyer-note">
                <span>@lang('theme.note'): </span> {{ $order->buyer_note }}
              </p>
            </td>
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>
  <div class="sep"></div>
@else
  <div class="clearfix space50"></div>
  <p class="lead text-center space50">
    @lang('theme.no_order_history')
    <br />
    <a href="{{ url('/') }}" class="btn btn-primary btn-sm flat">@lang('theme.button.shop_now')</a>
  </p>
@endif

<div class="row pagenav-wrapper">
  {{ $orders->links('theme::layouts.pagination') }}
</div><!-- /.row .pagenav-wrapper -->
<div class="clearfix space20"></div>
