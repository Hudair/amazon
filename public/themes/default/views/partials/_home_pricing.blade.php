<p class="feature__items-price-new box-price-new">
  {!! get_formated_price($item->current_sale_price(), config('system_settings.decimals', 2)) !!}
</p>
@if ($item->hasOffer())
  <p class="feature__items-price-old box-price-old">
    {!! get_formated_price($item->sale_price, config('system_settings.decimals', 2)) !!}
  </p>
@endif
