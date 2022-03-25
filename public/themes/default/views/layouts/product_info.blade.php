<div class="product-info">
  @if ($item->product->manufacturer->slug)
    <a href="{{ route('show.brand', $item->product->manufacturer->slug) }}" class="product-info-seller-name">
      <i class="fal fa-crown small"></i> {!! $item->product->manufacturer->name !!}
    </a>
  @else
    <a href="{{ route('show.store', $item->shop->slug) }}" class="product-info-seller-name">
      <i class="far fa-store"></i> {!! $item->shop->getQualifiedName() !!}
    </a>
  @endif

  <h5 class="product-info-title space10" data-name="product_name">{!! $item->title !!}</h5>

  @if ($item->ratings)
    @include('theme::layouts.ratings', ['ratings' => $item->ratings, 'count' => $item->ratings_count])
  @endif

  @include('theme::layouts.pricing', ['item' => $item])

  <div class="row">
    <div class="col-6 nopadding-right">
      <div class="product-info-availability space10">
        <div class="d-none d-sm-inline-block">@lang('theme.availability'):</div>
        <span>{{ $item->availability }}</span>
      </div>
    </div>

    @if (config('system_settings.show_item_conditions'))
      <div class="col-6 nopadding-left">
        <div class="product-info-condition space10">
          <div class="d-none d-sm-inline-block">@lang('theme.condition'):</div>
          <span><b id="item_condition">{!! $item->condition !!}</b></span>

          @if ($item->condition_note)
            <sup>
              <i class="fas fa-question" id="item_condition_note" data-toggle="tooltip" title="{!! $item->condition_note !!}" data-placement="top"></i>
            </sup>
          @endif
        </div>
      </div>
    @endif
  </div><!-- /.row -->

  <div class="row mb-2">
    <div class="col-6 nopadding-right">
      <a href="javascript:void(0)" data-link="{{ route('wishlist.add', $item) }}" class="btn btn-link add-to-wishlist">
        <i class="far fa-heart"></i> @lang('theme.button.add_to_wishlist')
      </a>
    </div>

    <div class="col-6 nopadding-left">
      @if ('quickView.product' == Route::currentRouteName())
        <a href="{{ route('show.store', $item->shop->slug) }}" class="btn btn-link">
          <i class="far fa-list"></i> @lang('theme.more_items_from_this_seller', ['seller' => $item->shop->name])
        </a>
        {{-- @elseif('quickView.product' == Route::currentRouteName()) --}}
        {{-- <a href="{{ route('show.brand', $item->product->manufacturer->slug) }}" class="product-info-seller-name"> @lang('theme.more_items_from_this_seller', ['seller' => $item->product->manufacturer->name])</a> --}}
      @else
        <a href="javascript:void(0);" class="btn btn-link" data-toggle="modal" data-target="{{ Auth::guard('customer')->check() ? '#contactSellerModal' : '#loginModal' }}">
          <i class="far fa-envelope"></i> @lang('theme.button.contact_seller')
        </a>
      @endif
    </div>
  </div><!-- /.row -->
</div><!-- /.product-info -->

@include('theme::layouts.share_btns')
