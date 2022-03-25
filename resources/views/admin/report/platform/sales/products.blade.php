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
                  <label>{{ trans('app.products') }}</label>
                  <select onchange="fireEventOnFilter(this.value)" id="productId" style="width: 100%" name="product_id" class="form-control searchProductForSelect"></select>
                </div>
              </div>
              <div class="col-md-2 nopadding-right nopadding-left">
                <div class="form-group">
                  <label>{{ trans('app.shops') }}</label>
                  <select style="width: 100%" onchange="fireEventOnFilter(this.value)" id="shopId" name="shop_id" class="form-control searchMerchant"></select>
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
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="row">
    <div class="col-md-12">
        <div class="rg-card-simple equal-height">
            <section>
                <div class="row">
                    <canvas id="salesReport" style="height: 300px; min-height: 300px; max-height: 300px"></canvas>
                </div>
            </section>
        </div>
    </div>
</div> --}}

  <div class="box margin-top-2">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.products') }}</h3>
      <div class="box-tools pull-right">
        @include('admin.partials.reports.timeframe')
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort table-responsive" style="overflow-x: scroll">
        <thead>
          <tr>
            <th>{{ trans('app.product') }}</th>
            <th>{{ trans('app.model_number') }}</th>
            <th>{{ trans('app.gtin') }}</th>
            <th>{{ trans('app.quantity') }}</th>
            <th>{{ trans('app.unique_purchase') }}</th>
            <th>{{ trans('app.average_price') }}</th>
            <th>{{ trans('app.revenue') }}</th>
          </tr>
        </thead>
        <tbody>
          @if (count($data) > 0)
            @foreach ($data as $key => $item)
              <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->model_number }}</td>
                <td>
                  <span class="label label-outline">{{ $item->gtin_type }}</span> {{ $item->gtin }}
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->uniquePurchase }}</td>
                <td>{{ get_formated_price($item->avgPrice) }}</td>
                <td>{{ get_formated_price($item->totalSale) }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection

@section('page-script')

  @include('plugins.report-products')

@endsection
