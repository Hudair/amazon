@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">
        {{ trans('app.preview') }} <small>({{ trans('app.total_number_of_rows', ['value' => count($rows)]) }})</small>
      </h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Product::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.bulk') }}" class="ajax-modal-btn btn btn-default btn-flat">{{ trans('app.bulk_import') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->

    {!! Form::open(['route' => 'admin.stock.inventory.import', 'id' => 'form', 'class' => 'inline-form', 'data-toggle' => 'validator']) !!}

    <div class="box-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>{{ trans('app.image') }}</th>
            <th width="20%">{{ trans('app.title') }}</th>
            <th>{{ trans('app.gtin') }}</th>
            <th>{{ trans('app.price') }}</th>
            <th>{{ trans('app.variants') }}</th>
            <th>{{ trans('app.listing') }}</th>
            <th>{{ trans('app.seo') }}</th>
          </tr>
        </thead>
        <tbody>
          @php
            $serializeData = [];
          @endphp
          @foreach ($rows as $row)
            @php
              $slug = $row['slug'] ?: convertToSlugString($row['title'], $row['sku']);
              $image_links = explode(',', $row['image_links']);
            @endphp

            {{ Form::hidden('data[]', serialize($row)) }}

            <tr>
              <td>
                <img src="{{ count($image_links) ? $image_links[0] : get_placeholder_img('small') }}" class="img-sm">
              </td>
              <td>{{ $row['title'] }}</td>
              <td>{{ $row['gtin_type'] . ' ' . $row['gtin'] }}</td>
              <td>
                @if ($row['offer_price'])
                  <dl>
                    {{ get_formated_currency((float) $row['offer_price'], 2) }}
                    <del>{{ get_formated_currency((float) $row['price'], 2) }}</del>
                    <p class="small">({{ $row['offer_starts'] . ' - ' . $row['offer_ends'] }})</p>
                  </dl>
                @else
                  {{ get_formated_currency((float) $row['price'], 2) }}
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
                    @if ($row['option_name_' . $loop->iteration] && $row['option_value_' . $loop->iteration])
                      <dt>{{ $row['option_name_' . $loop->iteration] }}: </dt>
                      <dd>{{ $row['option_value_' . $loop->iteration] }}</dd>
                    @endif
                  @endforeach
                </dl>
              </td>
              <td>
                <dl>
                  <dt>{{ trans('app.sku') }}: </dt>
                  <dd>{{ $row['sku'] }}</dd>
                  <dt>{{ trans('app.quantity') }}: </dt>
                  <dd>{{ $row['stock_quantity'] }}</dd>
                  <dt>{{ trans('app.condition') }}: </dt>
                  <dd>
                    {{ $row['condition'] }}
                    @if ($row['condition_note'])
                      <small>{{ $row['condition_note'] }}</small>
                    @endif
                  </dd>

                  @if ($row['brand'])
                    <dt>{{ trans('app.brand') }}: </dt>
                    <dd>{{ $row['brand'] }}</dd>
                  @endif
                  @if (isset($row['available_from']))
                    <dt>{{ trans('app.available_from') }}: </dt>
                    <dd>{{ $row['available_from'] }}</dd>
                  @endif
                  <dt>{{ trans('app.min_order_quantity') }}: </dt>
                  <dd>{{ $row['min_order_quantity'] ? $row['min_order_quantity'] : 1 }}</dd>
                  <dt>{{ trans('app.free_shipping') }}: </dt>
                  <dd>
                    <i class="fa fa-{{ $row['free_shipping'] == 'TRUE' ? 'check' : 'times' }} text-muted"></i>
                  </dd>
                  @if ($row['offer_starts'])
                    <dt>{{ trans('app.offer_starts') }}: </dt>
                    <dd>{{ $row['offer_starts'] }}</dd>
                  @endif
                  @if ($row['offer_ends'])
                    <dt>{{ trans('app.offer_ends') }}: </dt>
                    <dd>{{ $row['offer_ends'] }}</dd>
                  @endif
                  @if ($row['shipping_weight'])
                    <dt>{{ trans('app.shipping_weight') }}: </dt>
                    <dd>{{ get_formated_weight($row['shipping_weight']) }}</dd>
                  @endif
                  <dt>{{ trans('app.active') }}: </dt>
                  <dd><i class="fa fa-{{ $row['active'] == 'TRUE' ? 'check' : 'times' }} text-muted"></i></dd>
                </dl>
              </td>
              <td>
                <dl>
                  <dt>{{ trans('app.slug') }}: </dt>
                  <dd>{{ $slug }}</dd>
                  @if ($row['tags'])
                    <dt>{{ trans('app.tags') }}: </dt>
                    <dd>{{ $row['tags'] }}</dd>
                  @endif
                  @if ($row['meta_title'])
                    <dt>{{ trans('app.meta_title') }}: </dt>
                    <dd>{{ $row['meta_title'] }}</dd>
                  @endif
                  @if ($row['meta_description'])
                    <dt>{{ trans('app.meta_description') }}: </dt>
                    <dd>{{ $row['meta_description'] }}</dd>
                  @endif
                </dl>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->

    <div class="box-footer">
      <a href="{{ route('admin.stock.inventory.index') }}" class="btn btn-default btn-flat">{{ trans('app.cancel') }}</a>
      <small class="indent20">{{ trans('app.total_number_of_rows', ['value' => count($rows)]) }}</small>
      <div class="box-tools pull-right">
        {!! Form::button(trans('app.looks_good'), ['type' => 'submit', 'class' => 'confirm btn btn-new btn-flat']) !!}
      </div>
    </div> <!-- /.box-footer -->

    {!! Form::close() !!}
  </div> <!-- /.box -->
@endsection
