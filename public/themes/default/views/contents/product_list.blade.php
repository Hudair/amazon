@include('theme::contents.product_list_top_filter')

<div class="row product-list">
  @foreach ($products as $item)
    <div class="col-12 col-sm-4 col-md-{{ $colum ?? '3' }} p-0">
      <div class="product product-grid-view sc-product-item">
        <ul class="product-info-labels">
          @if ($item->shop->isVerified() && Route::current()->getName() != 'show.store')
            <li>@lang('theme.from_verified_seller')</li>
          @endif

          @foreach ($item->getLabels() as $label)
            <li>{!! $label !!}</li>
          @endforeach
        </ul>

        <div class="product-img-wrap">
          <img class="product-img-primary" src="{{ get_product_img_src($item, 'medium') }}" alt="{{ $item->title }}" title="{{ $item->title }}" />

          <img class="product-img-alt" src="{{ get_product_img_src($item, 'medium', 'alt') }}" alt="{{ $item->title }}" title="{{ $item->title }}" />

          <a class="product-link" href="{{ route('show.product', $item->slug) }}"></a>
        </div>

        <div class="product-actions btn-group">
          <a class="btn btn-default flat add-to-wishlist" href="javascript:void(0)" data-link="{{ route('wishlist.add', $item) }}">
            <i class="far fa-heart" data-toggle="tooltip" title="@lang('theme.button.add_to_wishlist')"></i> <span>@lang('theme.button.add_to_wishlist')</span>
          </a>

          <a class="btn btn-default flat itemQuickView" href="{{ route('quickView.product', $item->slug) }}">
            <i class="far fa-external-link" data-toggle="tooltip" title="@lang('theme.button.quick_view')"></i> <span>@lang('theme.button.quick_view')</span>
          </a>

          <a class="btn btn-primary flat sc-add-to-cart" data-link="{{ route('cart.addItem', $item->slug) }}">
            <i class="far fa-shopping-cart"></i> @lang('theme.button.add_to_cart')
          </a>
        </div>

        <div class="product-info">
          @include('theme::layouts.ratings', ['ratings' => $item->ratings, 'count' => $item->ratings_count])
          {{-- @include('theme::layouts.ratings', ['ratings' => $item->feedbacks->avg('rating'), 'count' => $item->feedbacks_count]) --}}

          <a href="{{ route('show.product', $item->slug) }}" class="product-info-title" data-name="product_name">{{ $item->title }}</a>

          <div class="product-info-availability">
            @lang('theme.availability'): <span>{{ $item->stock_quantity > 0 ? trans('theme.in_stock') : trans('theme.out_of_stock') }}</span>
          </div>

          @include('theme::layouts.pricing', ['item' => $item])

          <div class="product-info-desc"> {!! $item->description !!} </div>

          <ul class="product-info-feature-list">
            @if (config('system_settings.show_item_conditions'))
              <li>{!! $item->condition !!}</li>
            @endif
            {{-- <li>{{ $item->manufacturer->name }}</li> --}}
          </ul>
        </div><!-- /.product-info -->
      </div><!-- /.product -->
    </div><!-- /.col-md-* -->

    @if ($loop->iteration % 4 == 0)
      <div class="clearfix"></div>
    @endif
  @endforeach
</div><!-- /.row .product-list -->

<div class="sep"></div>

<div class="row pagenav-wrapper text-center mb-5 mt-3">
  {{ $products->appends(request()->input())->links('theme::layouts.pagination') }}
</div><!-- /.row .pagenav-wrapper -->
