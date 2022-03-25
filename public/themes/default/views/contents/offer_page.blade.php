<div class="container">
  <div class="offered-product-widget space30">
    <span class="offered-product-widget-img">
      <img src="{{ get_storage_file_url(optional($product->featuredImage)->path, 'medium') }}" alt="{!! $product->name !!}" title="{!! $product->name !!}" />
    </span>

    <div class="offered-product-widget-content">
      <h2>{!! $product->name !!}</h2>
      <span class="offered-product-widget-text text-muted space20">
        @if ($product->manufacturer->slug)
          {{ trans('theme.by') . ' ' }}
          <a href="{{ route('show.brand', $product->manufacturer->slug) }}" class="product-info-seller-name">{!! $product->manufacturer->name !!}</a>
        @endif
      </span>
      <span class="offered-product-widget-text">
        <span class="text-muted">{{ $product->gtin_type }}:</span> {{ $product->gtin }}
      </span>
    </div>
  </div>
</div>

<div class="container">
  <table class="table" id="buyer-payment-detail-table">
    <thead>
      <tr>
        <th width="12%">@lang('theme.price')</th>
        <th width="23%">@lang('theme.condition')</th>
        <th>@lang('theme.attributes')</th>
        <th>@lang('theme.seller')</th>
        <th width="15%">@lang('theme.options')</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($product->inventories->sortBy(function ($item) {
        return $item->current_sale_price();
    })
    as $offer)
        <tr class="sc-product-item">
          <td class="vertical-center text-center">
            @include('theme::layouts.pricing', ['item' => $offer])
          </td>
          <td class="vertical-center">
            <strong>{{ $offer->condition }}</strong>
            <p class="small">
              {{ $offer->condition_note }}
            </p>
          </td>
          <td>
            <a href="{{ route('show.product', $offer->slug) }}" class="product-info-title">{{ $offer->title }}</a>
            <span class="small">
              @include('theme::layouts.ratings', ['ratings' => $offer->ratings, 'count' => $offer->ratings_count])
            </span>
            <ul class="list-inline">
              @foreach ($offer->attributeValues as $attributeValue)
                <li class="small">
                  <span class="text-muted small">{{ $attributeValue->attribute->name }}: </span>
                  {{ $attributeValue->value }}
                </li>
              @endforeach
            </ul>
          </td>
          <td class="seller-info">
            <div class="space10">
              <img src="{{ get_storage_file_url(optional($offer->shop->image)->path, 'thumbnail') }}" class="seller-info-logo img-sm" alt="{{ trans('theme.logo') }}">

              <a href="{{ route('show.store', $offer->shop->slug) }}" class="seller-info-name">
                {!! $offer->shop->getQualifiedName() !!}
              </a>
            </div>
            <span class="small">
              @include('theme::layouts.ratings', ['ratings' => $offer->shop->ratings, 'count' => $offer->shop->ratings_count])
            </span>
          </td>
          <td>
            <a class="btn btn-default flat btn-block btn-sm itemQuickView" href="{{ route('quickView.product', $offer->slug) }}">
              <i class="far fa-external-link" data-toggle="tooltip" title="@lang('theme.button.quick_view')"></i>
              <span>@lang('theme.button.quick_view')</span>
            </a>

            <a class="btn btn-primary flat btn-block sc-add-to-cart" data-link="{{ route('cart.addItem', $offer->slug) }}">
              <i class="fas fa-shopping-cart"></i> @lang('theme.button.add_to_cart')
            </a>

            <a href="{{ route('direct.checkout', $offer->slug) }}" class="btn btn-block btn-warning flat">
              <i class="fas fa-rocket"></i> @lang('theme.button.buy_now')
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="clearfix space20"></div>
