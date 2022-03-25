@extends('admin.layouts.master')

@section('content')
  <div class="row">
    <div class="col-md-8">
      @if ($order->cancellation)
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-warning"></i> {{ trans('app.' . $order->cancellation->request_type . '_request') }}
            </h3>
            <div class="box-tools pull-right">{!! $order->cancellation->statusName() !!}</div>
          </div> <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-8">
                <p>
                  <strong>@lang('app.reason'):</strong>
                  {!! $order->cancellation->reason !!}
                </p>

                @if ($order->cancellation->description)
                  <p>
                    <strong>@lang('app.detail'):</strong>
                    {{ $order->cancellation->description ?? '' }}
                  </p>
                @endif

                <strong>{{ trans('app.requested_items') }}:</strong>
              </div>
              <div class="col-sm-4 text-right">
                @can('cancel', $order)
                  @if ($order->cancellation->isNew())
                    {!! Form::open(['route' => ['admin.order.cancellation.handle', $order, 'approve'], 'method' => 'put', 'class' => 'form-inline indent5']) !!}
                    <button class="btn btn-default-outline btn-sm confirm" type="submit">
                      <i class="fa fa-check"></i>
                      {{ trans('app.approve') }}
                    </button>
                    {!! Form::close() !!}

                    {!! Form::open(['route' => ['admin.order.cancellation.handle', $order, 'decline'], 'method' => 'put', 'class' => 'form-inline indent5']) !!}
                    <button class="btn btn-danger btn-sm confirm" type="submit">
                      <i class="fa fa-times"></i>
                      {{ trans('app.decline') }}
                    </button>
                    {!! Form::close() !!}
                  @endif

                  @if ($order->cancellation->inReview())
                    @if (Auth::user()->isFromPlatform())
                      <a href="javascript:void(0)" data-link="{{ route('admin.order.cancellation.create', $order) }}" class='ajax-modal-btn btn btn-default btn-sm'>
                        {{ trans('app.approve') }}
                      </a>
                    @else
                      <span class="label label-info">{!! trans('app.waiting_for_approval') !!}</span>
                    @endif
                  @endif
                @endcan
              </div>

              <span class="spacer10"></span>

              <div class="col-sm-12">
                <table class="table table-sripe">
                  <tbody id="items">
                    @if ($order->cancellation->isPartial())
                      @foreach ($order->inventories as $item)
                        @if (in_array($item->id, $order->cancellation->items))
                          <tr>
                            <td>
                              <img src="{{ get_product_img_src($item, 'tiny') }}" class="img-circle img-md" alt="{{ trans('app.image') }}">
                            </td>
                            <td class="nopadding-right" width="55%">
                              {{ $item->pivot->item_description }}
                              <a href="{{ route('show.product', $item->slug) }}" target="_blank" class="indent5 small"><i class=" fa fa-external-link"></i></a>
                            </td>
                            <td class="nopadding-right" width="15%">
                              {{ get_formated_currency($item->pivot->unit_price, 2) }}
                            </td>
                            <td>x</td>
                            <td class="nopadding-right" width="10%">
                              {{ $item->pivot->quantity }}
                            </td>
                            <td class="nopadding-right text-center" width="10%">
                              {{ get_formated_currency($item->pivot->quantity * $item->pivot->unit_price, 2) }}
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    @else
                      <tr id='empty-cart'>
                        <td colspan="6">{{ trans('app.all_items') }}</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div> <!-- /.col-* -->
            </div> <!-- /.row -->
          </div> <!-- /.box-body -->
        </div> <!-- /.box -->
      @endif

      <div class="box">
        <div class="box-header with-border">

          <h3 class="box-title">
            <i class="fa fa-shopping-cart"></i> {{ trans('app.order') . ': ' . $order->order_number }}
          </h3>

          @if ($order->dispute)
            <span class="label label-danger indent5">{{ trans('app.statuses.disputed') }}</span>
          @endif

          @can('fulfill', $order)
            <a data-link="{{ route('admin.order.deliveryboys', $order->id) }}" class="ajax-modal-btn btn btn-flat btn-default indent10" style="cursor: pointer;">
              <i class="fa fa-user"></i> {{ trans('app.assign_deliveryboy') }}
            </a>
          @endcan

          <div class="box-tools pull-right">
            {!! $order->orderStatus() !!}
          </div>

        </div> <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="well well-lg">
                <span class="lead">
                  {{ trans('app.payment') . ': ' . $order->paymentMethod->name }}
                </span>

                <span class="pull-right lead">
                  {!! $order->paymentStatusName() !!}
                </span>
              </div>
            </div>
          </div><!-- /.row -->

          <div class="row">
            <div class="col-md-12">
              <h4>{{ trans('app.order_details') }}</h4>
              <span class="spacer10"></span>

              <table class="table table-sripe">
                <tbody id="items">
                  @if (count($order->inventories) > 0)
                    @foreach ($order->inventories as $item)
                      <tr>
                        <td>
                          <img src="{{ get_product_img_src($item, 'tiny') }}" class="img-circle img-md" alt="{{ trans('app.image') }}">
                        </td>
                        <td class="nopadding-right" width="55%">
                          {{ $item->pivot->item_description }}
                          <a href="{{ route('show.product', $item->slug) }}" target="_blank" class="indent5 small"><i class=" fa fa-external-link"></i></a>
                        </td>
                        <td class="nopadding-right text-right " width="15%">
                          {{ get_formated_currency($item->pivot->unit_price, 2) }}
                        </td>
                        <td>&times;</td>
                        <td class="nopadding text-left" width="10%">
                          {{ $item->pivot->quantity }}
                        </td>
                        <td class="nopadding-right text-center" width="10%">
                          {{ get_formated_currency($item->pivot->quantity * $item->pivot->unit_price, 2) }}
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr id='empty-cart'>
                      <td colspan="6">{{ trans('help.empty_cart') }}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div><!-- /.row -->

          <span class="spacer30"></span>

          <div class="row">
            <div class="col-md-6">
              <dir class="spacer30"></dir>
              @if ($order->buyer_note)
                {{ trans('app.buyer_note') }}:
                <blockquote>
                  {!! $order->buyer_note !!}
                </blockquote>
              @endif

              <dir class="spacer30"></dir>
              @if ($order->admin_note)
                {{ trans('app.admin_note') }}:

                @can('fulfill', $order)
                  <a href="javascript:void(0)" data-link="{{ route('admin.order.order.adminNote', $order) }}" class='ajax-modal-btn btn btn-link'>
                    {{ trans('app.edit') }}
                  </a>
                @endcan

                <blockquote>
                  {!! $order->admin_note !!}
                </blockquote>
              @else
                @can('fulfill', $order)
                  <dir class="spacer20"></dir>
                  <a href="javascript:void(0)" data-link="{{ route('admin.order.order.adminNote', $order) }}" class='ajax-modal-btn btn btn-link'>
                    {{ trans('app.add_admin_note') }}
                  </a>
                @endcan
              @endif
            </div>
            <div class="col-md-6" id="summary-block">
              <table class="table">
                <tr>
                  <td class="text-right">{{ trans('app.total') }}</td>
                  <td class="text-right" width="40%">
                    {{ get_formated_currency($order->total, 2) }}
                  </td>
                </tr>

                <tr>
                  <td class="text-right">
                    <span>{{ trans('app.discount') }}</span>
                  </td>
                  <td class="text-right" width="40%"> &minus;
                    {{ get_formated_currency($order->discount, 2) }}
                  </td>
                </tr>

                <tr>
                  <td class="text-right">
                    <span>{{ trans('app.shipping') }}</span><br />
                    <em class="small">
                      @if ($order->shippingRate)
                        {{ optional($order->shippingRate)->name }}
                        @php
                          $carrier_name = $order->carrier ? $order->carrier->name : ($order->shippingRate ? optional($order->shippingRate->carrier)->name : null);
                        @endphp
                        @if ($carrier_name)
                          <small> {{ trans('app.by') . ' ' . $carrier_name }} </small>
                        @endif
                      @else
                        {{ trans('app.custom_shipping') }}
                      @endif
                    </em>
                  </td>
                  <td class="text-right" width="40%">
                    {{ get_formated_currency($order->shipping, 2) }}
                  </td>
                </tr>

                @if ($order->shippingPackage)
                  <tr>
                    <td class="text-right">
                      <span>{{ trans('app.packaging') }}</span><br />
                      <em class="small">{{ optional($order->shippingPackage)->name }}</em>
                    </td>
                    <td class="text-right" width="40%">
                      {{ get_formated_currency($order->packaging, 2) }}
                    </td>
                  </tr>
                @endif

                @if ($order->handling)
                  <tr>
                    <td class="text-right">{{ trans('app.handling') }}</td>
                    <td class="text-right" width="40%">
                      {{ get_formated_currency($order->handling, 2) }}
                    </td>
                  </tr>
                @endif

                <tr>
                  <td class="text-right">{{ trans('app.taxes') }} <br />
                    <em class="small">
                      @if ($order->shippingZone)
                        {{ optional($order->shippingZone)->name }}
                      @elseif($order->shippingRate)
                        {{ optional($order->shippingRate->shippingZone)->name }}
                      @endif
                      {{ get_formated_decimal($order->taxrate, true, 2) }}%
                    </em>
                  </td>
                  <td class="text-right" width="40%">
                    {{ get_formated_currency($order->taxes, 2) }}
                  </td>
                </tr>

                <tr class="lead">
                  <td class="text-right">{{ trans('app.grand_total') }}</td>
                  <td class="text-right" width="40%">
                    {{ get_formated_currency($order->grand_total, 2) }}
                  </td>
                </tr>
              </table>
            </div>
          </div><!-- /.row -->
        </div> <!-- /.box-body -->
      </div> <!-- /.box -->

      @php
        $refunded_amt = $order->refundedSum();
      @endphp

      @if ($refunded_amt > 0)
        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4><i class="fa fa-warning"></i> {{ trans('app.alert') }}!</h4>
          {!! trans('help.order_refunded', ['amount' => get_formated_currency($refunded_amt, 2), 'total' => get_formated_currency($order->grand_total, 2)]) !!}
        </div>
      @endif

      @can('fulfill', $order)
        <div class="box">
          <div class="box-body">
            <div class="box-tools">
              @if (Auth::user()->canManageOrderPayments())
                {!! Form::open(['route' => ['admin.order.order.togglePaymentStatus', $order], 'method' => 'put', 'class' => 'inline']) !!}
                <button type="submit" class="confirm ajax-silent btn btn-lg btn-danger">{{ $order->isPaid() ? trans('app.mark_as_unpaid') : trans('app.mark_as_paid') }}</button>
                {!! Form::close() !!}

                @if ($order->isPaid())
                  @can('initiate', \App\Models\Refund::class)
                    <a href="javascript:void(0)" data-link="{{ route('admin.support.refund.form', $order) }}" class='ajax-modal-btn btn btn-flat btn-lg btn-default'>
                      {{ trans('app.initiate_refund') }}
                    </a>
                  @endcan
                @endif
              @endif

              <div class="pull-right">
                <a href="javascript:void(0)" data-link="{{ route('admin.order.order.edit', $order) }}" class='ajax-modal-btn btn btn-flat btn-lg btn-default'>
                  {{ trans('app.update_status') }}
                </a>

                @if ($order->isFulfilled())
                  @unless($order->isArchived())
                    @can('archive', $order)
                      {!! Form::open(['route' => ['admin.order.order.archive', $order->id], 'method' => 'delete', 'class' => 'inline']) !!}
                      <button type="submit" class="confirm ajax-silent btn btn-lg btn-default"><i class="fa fa-archive text-muted"></i> {{ trans('app.order_archive') }}</button>
                      {!! Form::close() !!}
                    @endcan
                  @endunless
                @else
                  @unless($order->isCanceled() || $order->cancellation)
                    @if (!$order->cancellationFeeApplicable())
                      @if (Auth::user()->isFromPlatform())
                        <a href="javascript:void(0)" data-link="{{ route('admin.order.cancellation.create', $order) }}" class='ajax-modal-btn btn btn-lg btn-warning'>
                          {{ trans('app.cancel_order') }}
                        </a>
                      @else
                        {!! Form::open(['route' => ['admin.order.order.cancel', $order], 'method' => 'put', 'class' => 'inline']) !!}
                        <button type="submit" class="confirm ajax-silent btn btn-lg btn-warning">{{ trans('app.cancel_order') }}</button>
                        {!! Form::close() !!}
                      @endif
                    @else
                      <a href="javascript:void(0)" data-link="{{ route('admin.order.cancellation.create', $order) }}" class='ajax-modal-btn btn btn-flat btn-lg btn-warning'>
                        {{ trans('app.cancel_order') }}
                      </a>
                    @endif
                  @endunless

                  @if ($order->deliver())
                    <a href="javascript:void(0)" data-link="{{ route('admin.order.order.fulfillment', $order) }}" class='ajax-modal-btn btn btn-flat btn-lg btn-primary'>
                      {{ trans('app.fulfill_order') }}
                    </a>
                  @endif
                @endif
              </div>
            </div>
          </div> <!-- /.box-body -->
        </div> <!-- /.box -->
      @endcan

      @include('admin.partials._activity_logs', ['logger' => $order])
    </div> <!-- /.col-md-8 -->

    <div class="col-md-4 nopadding-left">
      @if (Auth::user()->isFromPlatform())
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-map-marker"></i> {{ trans('app.shop') }}</h3>
            <div class="box-tools pull-right">
              @can('secretLogin', $order->shop->owner)
                <a href="{{ route('admin.user.secretLogin', $order->shop->owner->id) }}" class="btn btn-link">
                  <i class="fa fa-user-secret"></i>
                  {{ trans('app.secret_login_merchant') }}
                </a>
              @endcan
            </div>
          </div> <!-- /.box-header -->
          <div class="box-body">
            <img src="{{ get_storage_file_url(optional($order->shop->image)->path, 'mini') }}" class="" alt="{{ trans('app.logo') }}">
            <p class="indent10">
              @if (Gate::allows('view', $order->shop))
                <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $order->shop->id) }}" class="ajax-modal-btn">
                  {{ $order->shop->name }}
                </a>
              @else
                <span class="lead">{{ $order->shop->name }}</span>
              @endif
            </p>

            <a href="{{ route('show.store', $order->shop->slug) }}" target="_blank" class="small pull-right">
              <i class=" fa fa-external-link"></i> {{ trans('app.store_front') }}
            </a>
          </div>
        </div>
      @endif

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> {{ trans('app.assigned_deliveryboy') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <p>
            <img src="{{ get_avatar_src($order->deliveryBoy, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">

            <span class="admin-user-widget-title indent5">
              {{-- @if (config('system_settings.vendor_can_view_customer_info') && $order->delivery_boy_id)
                <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.show', $order->customer->id) }}" class="ajax-modal-btn">
                  {{ $order->deliveryBoy->getName() }}
                </a>
              @else --}}
              {{ $order->deliveryBoy ? $order->deliveryBoy->getName() : trans('app.no_assigned_yet') }}
              {{-- @endif --}}
            </span>
          </p>

          @if ($order->deliveryBoy)
            <span class="admin-user-widget-text text-muted">
              {{ trans('app.email') . ': ' . $order->deliveryBoy->email }}
            </span>
          @endif
        </div>
      </div>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user-secret"></i> {{ trans('app.customer') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <p>
            <img src="{{ get_avatar_src($order->customer, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">

            <span class="admin-user-widget-title indent5">
              @if (config('system_settings.vendor_can_view_customer_info') && $order->customer_id)
                <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.show', $order->customer->id) }}" class="ajax-modal-btn">
                  {{ $order->customer->getName() }}
                </a>
              @else
                {{ $order->customer->getName() }}
              @endif

              @if ($order->email)
                <br /><small>{{ trans('app.email') . ': ' . $order->email }}</small>
              @endif
            </span>
          </p>

          @if ($order->customer->email)
            <span class="admin-user-widget-text text-muted">
              {{ trans('app.email') . ': ' . $order->customer->email }}
            </span>
          @endif

          <span class="spacer10"></span>

          @if ($order->conversation)
            <a href="{{ route('admin.support.message.show', $order->conversation) }}" class="btn btn-sm btn-info btn-flat">{{ trans('app.view_conversations') }}</a>
          @else
            {{-- <a href="javascript:void(0)" data-link="{{ route('admin.support.message.create', $order->id) }}"  class="ajax-modal-btn btn btn-new btn-sm">{{ trans('app.send_message') }}</a> --}}
            <a href="javascript:void(0)" data-link="{{ route('admin.support.orderConversation.create', $order->id) }}" class="ajax-modal-btn btn btn-new btn-sm">{{ trans('app.send_message') }}</a>
          @endif

          <a href="{{ route('admin.order.order.invoice', $order->id) }}" class="btn btn-sm btn-default btn-flat">{{ trans('app.invoice') }}</a>

          @if ($order->dispute)
            <a href="{{ route('admin.support.dispute.show', $order->dispute) }}" class="btn btn-sm btn-danger btn-flat">{{ trans('app.view_dispute') }}</a>
          @endif

          @if (is_incevio_package_loaded('pharmacy'))
            <fieldset>
              <legend><i class="far fa-stethoscope"></i> {{ trans('pharmacy::lang.prescription') }}</legend>
            </fieldset>

            @if (count($order->attachments))
              @foreach ($order->attachments as $attachment)
                <a href="{{ route('attachment.download', $attachment) }}">
                  <i class="fa fa-file"></i> {{ $attachment->name }}
                </a>
              @endforeach
            @endif
          @endif

          <fieldset>
            <legend>{{ strtoupper(trans('app.shipping_address')) }}</legend>
          </fieldset>

          @if ($order->pickup())
            <fieldset>
              <legend>{{ strtoupper(trans('app.pick_up_address')) }}</legend>
            </fieldset>

            @php
              $warehouseIds = [];
            @endphp

            @foreach ($order->inventories as $key => $inventory)
              @if (!empty($inventory->warehouse))
                @if (!in_array($inventory->warehouse_id, $warehouseIds))
                  @php
                    $warehouseIds[] = $inventory->warehouse_id;
                  @endphp

                  {!! address_str_to_html($inventory->warehouse->address->toString()) !!}

                @endif
              @endif
            @endforeach
          @else
            <fieldset>
              <legend>{{ strtoupper(trans('app.shipping_address')) }}</legend>
            </fieldset>
            {!! address_str_to_html($order->shipping_address) !!}

            <iframe width="100%" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(address_str_to_geocode_str($order->shipping_address)) }}&output=embed"></iframe>

            <fieldset>
              <legend>{{ strtoupper(trans('app.billing_address')) }}</legend>
            </fieldset>

            @if ($order->shipping_address == $order->billing_address)
              <small>
                <i class="fa fa-check-square-o"></i>
                {!! Form::label('same_as_shipping_address', strtoupper(trans('app.same_as_shipping_address')), ['class' => 'indent5']) !!}
              </small>
            @else
              {!! address_str_to_html($order->billing_address) !!}
            @endif
          @endif
        </div>
      </div>

      @if ($order->refunds->count())
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> {{ trans('app.refunds') }}</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div> <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-border">
              <tbody>
                @foreach ($order->refunds as $refund)
                  <tr>
                    <td>{{ $refund->created_at->diffForHumans() }}</td>
                    <td>{{ get_formated_currency($refund->amount, 2) }}</td>
                    <td>{!! $refund->statusName() !!}</td>
                    <td>
                      @if ($refund->isOpen())
                        @can('approve', $refund)
                          <a href="javascript:void(0)" data-link="{{ route('admin.support.refund.response', $refund) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.response') }}" class="fa fa-random"></i></a>&nbsp;
                        @endcan
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> {{ trans('app.shipping') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <span>{{ trans('app.tracking_id') }}: {{ $order->tracking_id }}</span><br />
          <span>{{ trans('app.carrier') }}: <strong>{{ $order->carrier? $order->carrier->name: ($order->shippingRate? optional($order->shippingRate->carrier)->name: '') }}</strong></span><br />
          <span>{{ trans('app.total_weight') }}: <strong>{{ get_formated_weight($order->shipping_weight) }}</strong></span><br />
          @if ($order->carrier && $order->tracking_id)
            @php
              $tracking_url = getTrackingUrl($order->tracking_id, $order->carrier_id);
            @endphp
            <span><a href="{{ $tracking_url }}">{{ trans('app.tracking_url') }}</a>: {{ $tracking_url }}</span>
          @endif
        </div>
      </div>

    </div> <!-- /.col-md-4 -->
  </div> <!-- /.row -->
@endsection
