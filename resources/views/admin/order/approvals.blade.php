@extends('admin.layouts.master')

{{-- @section('buttons')
	@can('create', \App\Models\Order::class)
		<a href="javascript:void(0)" data-link="{{ route('admin.order.order.searchCutomer') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_order') }}</a>
	@endcan
@endsection --}}

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.cancellations') }}</h3>
      <div class="box-tools pull-right">
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.order_number') }}</th>
            <th>{{ trans('app.shop') }}</th>
            <th>{{ trans('app.customer') }}</th>
            <th>{{ trans('app.grand_total') }}</th>
            <th>{{ trans('app.payment') }}</th>
            <th>{{ trans('app.requested_items') }}</th>
            <th>{{ trans('app.requested_at') }}</th>
            <th>{{ trans('app.status') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cancellations as $cancellation)
            @if ($cancellation->isOpen())
              <tr>
                <td>
                  <a href="{{ route('admin.order.order.show', $cancellation->order) }}">
                    {{ $cancellation->order->order_number }}
                  </a>
                  <span class="indent5">{!! $cancellation->order->orderStatus() !!}</span>
                  @if ($cancellation->order->disputed)
                    <span class="label label-danger indent5">{{ trans('app.statuses.disputed') }}</span>
                  @endif
                </td>
                <td>
                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $cancellation->shop_id) }}" class="ajax-modal-btn">{{ $cancellation->shop->getName() }}</a>
                </td>
                <td>
                  <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.show', $cancellation->customer_id) }}" class="ajax-modal-btn modal-btn">{{ $cancellation->customer->getName() }}</a>
                </td>
                <td>{{ get_formated_currency($cancellation->order->grand_total, 2) }}</td>
                <td>{!! $cancellation->order->paymentStatusName() !!}</td>
                <td>{{ $cancellation->items_count . '/' . $cancellation->order->quantity }}</td>
                <td>{{ $cancellation->created_at->diffForHumans() }}</td>
                <td>{!! $cancellation->statusName() !!}</td>
                <td class="row-options">
                  @can('cancel', $cancellation->order)
                    <a href="javascript:void(0)" data-link="{{ route('admin.order.cancellation.create', $cancellation->order) }}" class='ajax-modal-btn btn btn-default btn-sm'>
                      {{ trans('app.approve') }}
                    </a>
                  @endcan
                </td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
