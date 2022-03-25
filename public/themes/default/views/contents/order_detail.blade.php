<!-- CONTENT SECTION -->
<section id="payment-detail-section" name="payment-detail-section" class="space20">
  <div class="container">
    <div class="row">
      <div class="col-md-12 nopadding">
        <table class="table" id="buyer-payment-detail-table">
          <thead>
            <tr>
              <th colspan="6">@lang('theme.payment_detail')</th>
            </tr>
          </thead>
          <tbody>
            <tr class="buyer-payment-info-head">
              <td>@lang('theme.price')</td>
              <td>@lang('theme.shipping_cost')</td>
              <td>@lang('theme.packaging_cost')</td>
              <td>@lang('theme.taxes')</td>
              <td>@lang('theme.discount')</td>
              <td>@lang('theme.total')</td>
            </tr>

            <tr class="buyer-payment-info-body">
              <td>{{ get_formated_currency($order->total, true, 2) }}</td>
              <td>{{ get_formated_currency($order->shipping + $order->handling, true, 2) }}</td>
              <td>{{ get_formated_currency($order->packaging, true, 2) }}</td>
              <td>{{ get_formated_currency($order->taxes, true, 2) }}</td>
              <td>{{ get_formated_currency($order->discount, true, 2) }}</td>
              <td>{{ get_formated_currency($order->grand_total, true, 2) }}</td>
            </tr>

            <tr>
              <td colspan="6"></td>
            </tr>

            <tr class="buyer-payment-info-head">
              <td colspan="2">@lang('theme.amount')</td>
              <td colspan="2">@lang('theme.payment_method')</td>
              <td colspan="2">@lang('theme.status')</td>
            </tr>

            <tr class="buyer-payment-info-body">
              <td colspan="2">{{ get_formated_currency($order->grand_total, true, 2) }}</td>
              <td colspan="2">{{ $order->paymentMethod->name }}</td>
              <td colspan="2">{!! $order->paymentStatusName() !!}</td>
            </tr>
          </tbody>
        </table>
      </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>

@if ($order->refunds->count())
  <section id="refund-detail-section" name="refund-detail-section" class="space20">
    <div class="container">
      <div class="row">
        <div class="col-md-12 nopadding">
          <table class="table" id="buyer-payment-detail-table">
            <thead>
              <tr>
                <th colspan="6">@lang('theme.refunds')</th>
              </tr>
            </thead>
            <tbody>
              <tr class="buyer-payment-info-head">
                <td>{{ trans('theme.return_goods') }}</td>
                <td>{{ trans('theme.amount') }}</td>
                <td>{{ trans('theme.status') }}</td>
                <td>{{ trans('theme.created_at') }}</td>
                <td>{{ trans('theme.updated_at') }}</td>
              </tr>

              @foreach ($order->refunds as $refund)
                <tr class="buyer-payment-info-body">
                  <td>{!! get_yes_or_no($refund->return_goods) !!}</td>
                  <td>{{ get_formated_currency($refund->amount, true, 2) }}</td>
                  <td>{!! $refund->statusName() !!}</td>
                  <td>{{ $refund->created_at->diffForHumans() }}</td>
                  <td>{{ $refund->updated_at->diffForHumans() }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div><!-- /.col-md-12 -->
      </div><!-- /.row -->
    </div><!-- /.container -->
  </section>
@endif

<section id="order-detail-section" name="order-detail-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 nopadding">
        <table class="table" id="buyer-order-table" name="buyer-order-table">
          <thead>
            <tr>
              <th colspan="3">@lang('theme.order_detail')</th>
            </tr>
          </thead>
          <tbody>
            <tr class="buyer-payment-info-head">
              <td>@lang('theme.shipping_address'):</td>
              <td colspan="2">@lang('theme.billing_address'):</td>
            </tr>
            <tr>
              <td>{!! $order->shipping_address !!}</td>
              <td colspan="2">{!! $order->billing_address !!}</td>
            </tr>
            <tr class="order-info-head">
              <td width="40%">
                <h5 class="my-1">
                  <span>@lang('theme.order_id'): </span>
                  {{ $order->order_number }}

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
                <h5 class="mt-2">
                  <span>@lang('theme.order_time_date'): </span>{{ $order->created_at->toDayDateTimeString() }}
                </h5>
              </td>
              <td width="40%" class="store-info">
                <h5 class="my-1">
                  <span>@lang('theme.store'):</span>
                  @if ($order->shop->slug)
                    <a href="{{ route('show.store', $order->shop->slug) }}">
                      {{ $order->shop->name }}
                    </a>
                  @else
                    @lang('theme.store_not_available')
                  @endif
                </h5>
                <h5 class="mt-2">
                  <span>@lang('theme.status')</span>
                  {!! $order->orderStatus(true) . ' &nbsp; ' . $order->paymentStatusName() !!}
                </h5>
              </td>
              <td width="20%" class="order-amount">
                <h5 class="my-1">
                  <span>@lang('theme.order_amount'): </span>{{ get_formated_currency($order->grand_total, true, 2) }}
                </h5>
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

                    @if ($order->dispute)
                      <a href="{{ route('dispute.open', $order) }}" class="btn btn-link btn-block" data-confirm="@lang('theme.confirm_action.open_a_dispute')">@lang('theme.dispute_detail')</a>
                    @else
                      <a href="{{ route('dispute.open', $order) }}" class="confirm btn btn-link btn-block" data-confirm="@lang('theme.confirm_action.open_a_dispute')">@lang('theme.button.open_dispute')</a>
                    @endif
                  </td>
                @endif
              </tr> <!-- /.order-body -->
            @endforeach

            @if ($order->message_to_customer)
              <tr class="message_from_seller">
                <td colspan="3">
                  <p>
                    <strong>@lang('theme.message_from_seller'): </strong> {!! $order->message_to_customer !!}
                  </p>
                </td>
              </tr>
            @endif

            @if ($order->buyer_note)
              <tr class="order-info-footer">
                <td colspan="3">
                  <p class="order-detail-buyer-note">
                    <strong>@lang('theme.note'): </strong> {!! $order->buyer_note !!}
                  </p>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>

<section id="message-section" name="message-section">
  <div class="container">
    <div class="section-title">
      <h4>@lang('theme.section_headings.contact_seller')</h4>
    </div>
    <div class="message-list">
      <div class="row">
        {!! Form::open(['route' => ['order.conversation', $order], 'files' => true, 'id' => 'conversation-form', 'data-toggle' => 'validator']) !!}
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('message', trans('theme.write_your_message')) !!}
            {!! Form::textarea('message', null, ['class' => 'form-control form-control flat', 'placeholder' => trans('theme.leave_message_to_seller'), 'rows' => '4', 'maxlength' => 500, 'required']) !!}
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('photoInput', trans('theme.button.upload_photo')) !!}
            {!! Form::file('photo') !!}
            <span class="help-block small">@lang('theme.help.upload_photo')</span>
          </div>
          @unless($order->order_status_id == \App\Models\Order::STATUS_DELIVERED)
            <div class="checkbox">
              <label>
                {!! Form::checkbox('goods_received', 1, null, ['class' => 'i-check-blue']) !!} {{ trans('theme.goods_received') }}
              </label>
            </div>
          @endunless
          {!! Form::button(trans('theme.button.send_message'), ['type' => 'submit', 'class' => 'btn btn-info flat']) !!}
        </div>
        {!! Form::close() !!}
      </div> <!-- /.row -->

      @if ($order->conversation)
        <div class="message-list-header">
          <h4>@lang('theme.message_history')</h4>
        </div>

        @foreach ($order->conversation->replies->sortByDesc('created_at') as $msg)
          <div class="row message-list-item {{ $msg->customer_id ? 'message-buyer message-me' : 'message-seller' }}">
            <div class="col-2 nopadding-right">
              @unless($msg->customer_id)
                <div class="message-user-info">
                  <div class="message-user-name" title="seller">{{ $order->shop->name ?? lang('theme.seller') }}</div>
                  <div class="message-date">{{ $msg->created_at->toDayDateTimeString() }}</div>
                </div>
              @endunless
            </div>
            <div class="col-8">
              <div class="message-content-wrapper">
                <div class="message-content">{!! $msg->reply !!}</div>
                @if ($attachment = optional($msg->attachments)->first())
                  <a href="{{ get_storage_file_url($attachment->path, 'original') }}" class="pull-right message-attachment" target="_blank">
                    <img src="{{ get_storage_file_url($attachment->path, 'tiny') }}" class="img-sm thumbnail">
                  </a>
                @endif
              </div>
            </div>
            <div class="col-2 nopadding-left">
              @if ($msg->customer_id)
                <div class="message-user-info">
                  <div class="message-user-name" title="me">@lang('theme.me')</div>
                  <div class="message-date">{{ $msg->created_at->toDayDateTimeString() }}</div>
                </div>
              @endif
            </div>
          </div>
        @endforeach

        <div class="row message-list-item message-buyer message-me">
          <div class="col-2 nopadding-right">
          </div>
          <div class="col-8">
            <div class="message-content-wrapper">
              <div class="message-content">{!! $order->conversation->message !!}</div>
              @if ($attachment = optional($order->conversation->attachments)->first())
                <a href="{{ get_storage_file_url($attachment->path, 'original') }}" class="pull-right message-attachment" target="_blank">
                  <img src="{{ get_storage_file_url($attachment->path, 'tiny') }}" class="img-sm thumbnail">
                </a>
              @endif
            </div>
          </div>
          <div class="col-2 nopadding-left">
            <div class="message-user-info">
              <div class="message-user-name" title="me">@lang('theme.me')</div>
              <div class="message-date">{{ $order->conversation->created_at->toDayDateTimeString() }}</div>
            </div>
          </div>
        </div>
      @endif
    </div><!-- /.message-list -->
  </div><!-- /.container -->
</section>
<!-- END CONTENT SECTION -->

<div class="clearfix space20"></div>
