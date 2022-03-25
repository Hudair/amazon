@extends('admin.layouts.master')

@section('content')

  <div class="row">
    <div class="col-sm-12">
      <div id="filter-panel">
        <div class="panel panel-default">
          <div class="panel-body">
            <form action="{{ route('admin.sales.payments') }}/" method="get">
              <div class="row">
                <div class="col-md-2 nopadding-right">
                  <div class="form-group">
                    <label>{{ trans('app.payment_status') }}</label>
                    <select id="paymentMethod" class="form-control" name="payment_status" onchange="fireEventOnFilter(this.value)">
                      <option value="">select</option>
                      @foreach ($paymentMethods as $payment)
                        <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2 nopadding-right nopadding-left">
                  <div class="form-group">
                    <label>{{ trans('app.payment_status') }}</label>
                    <select id="paymentStatus" onchange="fireEventOnFilter(this.value)" class="form-control" name="payment_status">
                      <option value="" @if (request()->get('order_status') == 'all') selected @endif>{{ trans('app.all') }}</option>
                      <option value="PAYMENT_STATUS_PENDING" @if (request()->get('order_status') == 'PAYMENT_STATUS_PENDING') selected @endif>{{ trans('app.pending') }}</option>
                      <option value="PAYMENT_STATUS_PAID" @if (request()->get('order_status') == 'PAYMENT_STATUS_PAID') selected @endif>{{ trans('app.paid') }}</option>
                      <option value="PAYMENT_STATUS_REFUNDED" @if (request()->get('order_status') == 'PAYMENT_STATUS_REFUNDED') selected @endif>{{ trans('app.refunded') }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2 nopadding-right nopadding-left">
                  &nbsp;
                </div>
                <div class="col-md-2 nopadding-right nopadding-left">
                  &nbsp;
                </div>
                <div class="col-md-2 nopadding-right nopadding-left">
                  &nbsp;
                </div>
                <div class="col-md-2 nopadding-left">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <button onclick="clearAllFilter()" type="button" class="btn btn-default pull-right" name="search" value="1"><i class="fa fa-caret-left"></i> {{ trans('app.clear') }}</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="box margin-top-2">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.payments') }}</h3>
      <div class="box-tools pull-right">
        @include('admin.partials.reports.timeframe')
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <div class="rg-card-simple equal-height">
        <canvas id="salesReport" style="height: 300px; min-height: 300px; max-height: 300px; width: 100%"></canvas>
      </div>

      <span class="spacer30"></span>

      <div class="col-md-6">
        <div class="rg-card-simple equal-height">
          <canvas id="paymentMethodChart" style="height: 300px; min-height: 300px; max-height: 300px"></canvas>
        </div>
      </div>

      <div class="col-md-6">
        <div class="rg-card-simple equal-height">
          <canvas id="paymentStatusChart" style="height: 300px; min-height: 300px; max-height: 300px"></canvas>
        </div>
      </div>

      {{-- <table class="table table-hover table-no-sort table-responsive" style="overflow-x: scroll">
            <thead>
            <tr>
                <th>{{ trans('app.date') }}</th>
                <th>{{ trans('app.order_number') }}</th>
                <th>{{ trans('app.customer') }}</th>
                <th>{{ trans('app.shop') }}</th>
                <th>{{ trans('app.payment_status') }}</th>
                <th>{{ trans('app.items') }}</th>
                <th>{{ trans('app.total') }}</th>
                <th>{{ trans('app.grand_total') }}</th>

            </tr>
            </thead>
            <tbody>
            @if (count($data) > 0)
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{$item->date}}</td>
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
                    <td>{{ get_payment_status_name($item->payment_status)}}</td>
                    <td>{{ $item->item }}</td>
                    <td>{{ get_formated_price($item->total) }}</td>
                    <td>{{ get_formated_price($item->grand_total) }}</td>
                </tr>
            @endforeach
            @endif
            </tbody>
        </table> --}}
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection

@section('page-script')
  @include('plugins.report-payment')


@endsection
