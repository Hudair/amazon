@extends('admin.layouts.master')

@section('content')
  <div class="alert alert-danger">
    <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
    {{ trans('messages.import_ignored') }}
  </div>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.import_failed') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Product::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.bulk') }}" class="ajax-modal-btn btn btn-default btn-flat">{{ trans('app.bulk_import') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->

    <div class="box-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>{{ trans('app.image') }}</th>
            <th width="20%">{{ trans('app.title') }}</th>
            <th>{{ trans('app.quantity') }}</th>
            <th>{{ trans('app.price') }}</th>
            <th>{{ trans('app.variants') }}</th>
            <th width="25%">{{ trans('app.listing') }}</th>
            <th width="20%">{{ trans('app.reason') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($failed_rows as $row)
            <tr>
              <td>
                @php
                  $image_links = explode(',', $row['data']['image_links']);
                @endphp
                <img src="{{ count($image_links) ? $image_links[0] : get_placeholder_img('small') }}" class="img-sm">
              </td>
              <td>
                {{ $row['data']['title'] }}<br />
                <strong>{{ trans('app.slug') }}: </strong> {{ $row['data']['slug'] ?: convertToSlugString($row['data']['title'], $row['data']['sku']) }}
              </td>
              <td>{{ $row['data']['stock_quantity'] }}</td>
              <td>
                @if ($row['data']['offer_price'])
                  <dl>
                    {{ get_formated_currency($row['data']['offer_price'], 2) }}
                    <strike>{{ get_formated_currency($row['data']['price'], 2) }}</strike>
                  </dl>
                @else
                  {{ get_formated_currency($row['data']['price'], 2) }}
                @endif
              </td>
              <td>
                @php
                  $variants = array_filter(
                      $row,
                      function ($key) {
                          return strpos($key, 'option_name_') === 0;
                      },
                      ARRAY_FILTER_USE_KEY,
                  );
                @endphp

                <dl>
                  @foreach ($variants as $index => $variant)
                    @if ($row['data']['option_name_' . ($index + 1)] && $row['data']['option_value_' . ($index + 1)])
                      <dt>{{ $row['data']['option_name_' . ($index + 1)] }}: </dt>
                      <dd>{{ $row['data']['option_value_' . ($index + 1)] }}</dd>
                    @endif
                  @endforeach
                </dl>
              </td>
              <td>
                <dl>
                  <dt>{{ trans('app.sku') }}: </dt>
                  <dd>{{ $row['data']['sku'] }}</dd>
                  <dt>{{ trans('app.condition') }}: </dt>
                  <dd>{{ $row['data']['condition'] }}</dd>
                  <dt>{{ trans('app.gtin') }}: </dt>
                  <dd>{{ $row['data']['gtin_type'] . ' ' . $row['data']['gtin'] }}</dd>
                  @if (isset($row['data']['available_from']))
                    <dt>{{ trans('app.available_from') }}: </dt>
                    <dd>{{ $row['data']['available_from'] }}</dd>
                  @endif
                  <dt>{{ trans('app.min_order_quantity') }}: </dt>
                  <dd>{{ $row['data']['min_order_quantity'] ? $row['data']['min_order_quantity'] : 1 }}</dd>
                  <dt>{{ trans('app.free_shipping') }}: </dt>
                  <dd><i class="fa fa-{{ $row['data']['free_shipping'] == 'TRUE' ? 'check' : 'times' }} text-muted"></i></dd>
                  @if ($row['data']['offer_starts'])
                    <dt>{{ trans('app.offer_starts') }}: </dt>
                    <dd>{{ $row['data']['offer_starts'] }}</dd>
                  @endif
                  @if ($row['data']['offer_ends'])
                    <dt>{{ trans('app.offer_ends') }}: </dt>
                    <dd>{{ $row['data']['offer_ends'] }}</dd>
                  @endif
                  <dt>{{ trans('app.active') }}: </dt>
                  <dd><i class="fa fa-{{ $row['data']['active'] == 'TRUE' ? 'check' : 'times' }} text-muted"></i></dd>
                </dl>
              </td>
              <td><span class="label label-danger">{{ $row['reason'] }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
    <div class="box-footer">
      <a href="{{ route('admin.stock.inventory.index') }}" class="btn btn-danger btn-flat">{{ trans('app.dismiss') }}</a>
      <div class="box-tools pull-right">
        {!! Form::open(['route' => 'admin.stock.inventory.downloadFailedRows', 'id' => 'form', 'class' => 'inline-form', 'data-toggle' => 'validator']) !!}
        @foreach ($failed_rows as $row)
          <input type="hidden" name="data[]" value="{{ serialize($row['data']) }}">
        @endforeach
        {!! Form::button(trans('app.download_failed_rows'), ['type' => 'submit', 'class' => 'btn btn-new btn-flat']) !!}
        {!! Form::close() !!}
      </div>
    </div> <!-- /.box-footer -->
  </div> <!-- /.box -->
@endsection
