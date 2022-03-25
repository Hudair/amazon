@extends('admin.layouts.master')

@section('content')

  <div class="row">
    <div class="col-sm-12">
      <div id="filter-panel">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-2 nopadding-right">
                <div class="form-group">
                  <label>{{ trans('app.customer') }}</label>
                  <select style="width: 100%" onchange="fireEventOnFilter(this.value)" id="customerId" name="customer_id" class="form-control searchCustomer"></select>
                </div>
              </div>
              <div class="col-md-2 nopadding-right nopadding-left">
                <div class="form-group">
                  <label>{{ trans('app.shops') }}</label>
                  <select style="width: 100%" name="shop_id" onchange="fireEventOnFilter(this.value)" id="shopId" class="form-control searchMerchant"></select>
                </div>
              </div>
              <div class="col-md-2 nopadding-right nopadding-left">
                <div class="form-group">
                  <label>{{ trans('app.order_number') }}</label>
                  <input type="text" id="orderNumber" onkeyup="fireEventOnFilter(this.value)" name="order_number" value="{{ request()->get('order_number') }}" class="form-control" placeholder="{{ trans('app.order_number') }}">
                </div>
              </div>
              <div class="col-md-2 nopadding-right nopadding-left">
                <div class="form-group">
                  <label>{{ trans('app.order_status') }}</label>
                  <select id="orderStatus" onchange="fireEventOnFilter(this.value)" class="form-control" name="order_status">
                    <option value="" @if (request()->get('order_status') == 'all') selected @endif>{{ trans('app.all') }}</option>
                    <option value="STATUS_WAITING_FOR_PAYMENT" @if (request()->get('order_status') == 'STATUS_WAITING_FOR_PAYMENT') selected @endif>{{ trans('app.waiting_for_payment') }}</option>
                    <option value="STATUS_CONFIRMED" @if (request()->get('order_status') == 'STATUS_CONFIRMED') selected @endif>{{ trans('app.confirmed') }}</option>
                    <option value="STATUS_FULFILLED" @if (request()->get('order_status') == 'STATUS_FULFILLED') selected @endif>{{ trans('app.fulfilled') }}</option>
                    <option value="STATUS_AWAITING_DELIVERY" @if (request()->get('order_status') == 'STATUS_AWAITING_DELIVERY') selected @endif>{{ trans('app.awaiting_delivery') }}</option>
                    <option value="STATUS_DELIVERED" @if (request()->get('order_status') == 'STATUS_DELIVERED') selected @endif>{{ trans('app.delivered') }}</option>
                    <option value="STATUS_CANCELED" @if (request()->get('order_status') == 'STATUS_CANCELED') selected @endif>{{ trans('app.canceled') }}</option>
                    <option value="STATUS_PAYMENT_ERROR" @if (request()->get('order_status') == 'STATUS_PAYMENT_ERROR') selected @endif>{{ trans('app.payment_error') }}</option>
                    <option value="STATUS_RETURNED" @if (request()->get('order_status') == 'STATUS_RETURNED') selected @endif>{{ trans('app.returns') }}</option>
                    <option value="STATUS_DISPUTED" @if (request()->get('order_status') == 'STATUS_DISPUTED') selected @endif>{{ trans('app.disputed') }}</option>
                  </select>
                </div>
              </div>

              <div class="col-md-2 nopadding-right nopadding-left">
                <div class="form-group">
                  <label>{{ trans('app.payment_status') }}</label>
                  <select id="paymentStatus" onchange="fireEventOnFilter(this.value)" class="form-control" name="payment_status">
                    <option value="" @if (request()->get('order_status') == 'all') selected @endif>{{ trans('app.all') }}</option>
                    <option value="PAYMENT_STATUS_UNPAID" @if (request()->get('order_status') == 'PAYMENT_STATUS_UNPAID') selected @endif>{{ trans('app.unpaid') }}</option>
                    <option value="PAYMENT_STATUS_PENDING" @if (request()->get('order_status') == 'PAYMENT_STATUS_PENDING') selected @endif>{{ trans('app.pending') }}</option>
                    <option value="PAYMENT_STATUS_PAID" @if (request()->get('order_status') == 'PAYMENT_STATUS_PAID') selected @endif>{{ trans('app.paid') }}</option>
                    {{-- <option value="PAYMENT_STATUS_INITIATED_REFUND" @if (request()->get('order_status') == 'PAYMENT_STATUS_INITIATED_REFUND') selected @endif >{{trans('app.initial_refund')}}</option>
                                    <option value="PAYMENT_STATUS_PARTIALLY_REFUNDED" @if (request()->get('order_status') == 'PAYMENT_STATUS_PARTIALLY_REFUNDED') selected @endif >{{trans('app.partially_refund')}}</option> --}}
                    <option value="PAYMENT_STATUS_REFUNDED" @if (request()->get('order_status') == 'PAYMENT_STATUS_REFUNDED') selected @endif>{{ trans('app.refunded') }}</option>
                  </select>
                </div>
              </div>

              <div class="col-md-2 nopadding-left">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <button onclick="clearAllFilter()" type="button" class="btn btn-default pull-right" name="search" value="1"><i class="fa fa-caret-left"></i> {{ trans('app.clear') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="box margin-top-2">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.orders') }}</h3>

      {{-- <div class="dropdown">
            <a  style="height: 17px;padding: 1px;width: 35px;margin-left: 1%; margin-top: -3px;"
                class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="fa fa-download"></i>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#">HTML</a></li>
                <li><a href="#">CSS</a></li>
                <li><a href="#">JavaScript</a></li>
            </ul>
        </div> --}}

      {{-- <a style="height: 17px;padding: 1px;width: 30px;margin-left: 1%; margin-top: -3px;" id="downloadOrder"
           download="order-report.jpg" href="" class="btn btn-primary bg-flat-color-1" title="order report download">
            <i class="fa fa-download"></i>
        </a> --}}

      <div class="box-tools pull-right">
        @include('admin.partials.reports.timeframe')
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <div class="rg-card-simple equal-height">
        <canvas id="salesReport" style="height: 300px; min-height: 300px; max-height: 300px"></canvas>
      </div>

      <span class="spacer20"></span>

      <table class="table table-hover table-no-sort table-responsive" style="overflow-x: scroll">
        <thead>
          <tr>
            <th>{{ trans('app.order_date') }}</th>
            <th>{{ trans('app.delivery_date') }}</th>
            <th>{{ trans('app.order_number') }}</th>
            <th>{{ trans('app.customer') }}</th>
            @if (!Auth::user()->isMerchant())
              <th>{{ trans('app.shop') }}</th>
            @endif
            <th>{{ trans('app.quantity') }}</th>
            <th>{{ trans('app.total') }}</th>

          </tr>
        </thead>
        <tbody>
          @if (count($data) > 0)
            @foreach ($data as $key => $item)
              <tr>
                <td>{{ $item->date }}</td>
                <td>{{ $item->delivery_date }}</td>
                <td>
                  @can('view', $item)
                    <a href="{{ route('admin.order.order.show', $item->id) }}" title="view order">
                      {{ $item->order_number }}
                    </a>
                  @else
                    {{ $item->order_number }}
                  @endcan
                </td>
                <td>{{ $item->customer }}</td>
                <td>{{ $item->shop }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ get_formated_price($item->grand_total) }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection

@section('page-script')
  @include('plugins.report-orders')
@endsection
