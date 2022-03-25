<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content flat">
    <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
    <div class="row sc-product-item">
      <div class="col-md-5 col-sm-6 pl-1 pt-1">
        @include('theme::layouts.jqzoom', ['item' => $item])
      </div>
      <div class="col-md-7 col-sm-6">
        <div class="product-single">
          @include('theme::layouts.product_info', ['zoomID' => 'quickViewZoom', 'item' => $item])

          <div class="sep"></div>

          <div class="row product-attribute">
            <div class="col-12">
              @if ($item->key_features)
                <div class="section-title space10">
                  {!! trans('theme.section_headings.key_features') !!}
                </div>
                <ul class="key_feature_list">
                  @foreach (unserialize($item->key_features) as $key_feature)
                    <li>{!! $key_feature !!}</li>
                  @endforeach
                </ul>
              @endif

              <div class="clearfix space10"></div>

              <a href="{{ route('show.product', $item->slug) }}" class="btn btn-default flat space10">
                @lang('theme.button.view_product_details')
              </a>
            </div><!-- /.col-sm-9 .col-6 -->
          </div><!-- /.row -->

          <div class="sep"></div>

          <a href="javascript:void(0);" data-link="{{ route('cart.addItem', $item->slug) }}" class="btn btn-primary flat sc-add-to-cart" data-dismiss="modal">
            <i class="fas fa-shopping-bag"></i> @lang('theme.button.add_to_cart')
          </a>

          <a href="{{ route('direct.checkout', $item->slug) }}" class="btn btn-warning flat" id="buy-now-btn"><i class="fas fa-rocket"></i>
            @lang('theme.button.buy_now')
          </a>

          @if ($item->product->inventories_count > 1)
            <a href="{{ route('show.offers', $item->product->slug) }}" class="btn btn-sm btn-link">
              @lang('theme.view_more_offers', ['count' => $item->product->inventories_count])
            </a>
          @endif
        </div><!-- /.product-single -->

        <div class="space50"></div>
      </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
