<p id="allCheckoutDisable" class="text-warning small mb-3 hidden"><i class="fas fa-exclamation-triangle"></i> {{ trans('checkout::lang.checkout_all_not_possible') }}</p>

<a href="{{ route(config('checkout.routes.checkout')) }}" id="allCheckoutBtn" class="btn btn-black btn-lg flat">
  <i class="fa fa-cart-plus"></i> {{ trans('checkout::lang.checkout_all') }}
</a>

<p id="allCheckoutHelp" class="text-info small mt-3"><i class="fas fa-info-circle"></i> {{ trans('checkout::lang.help_checkout_all') }}</p>
