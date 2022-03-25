<div class="feature__items-wishlist box-cart">
  <div class="feature__items-wishlist box-cart">
    <a href="javascript:void(0)" data-link="{{ route('cart.addItem', $item->slug) }}" class="btn-primary sc-add-to-cart" tabindex="0">
      <i class="fal fa-shopping-cart"></i>
      <span class="d-none d-sm-inline-block">{{ trans('theme.add_to_cart') }}</span>
    </a>
  </div>
</div>
