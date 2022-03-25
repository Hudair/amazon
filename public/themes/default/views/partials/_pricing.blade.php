<div class="product-info-price">
  <span class="old-price" style="display: {{ $item->hasOffer() ? '' : 'none' }}">
    {!! get_formated_price($item->sale_price, config('system_settings.decimals', 2)) !!}
  </span>

  <span class="product-info-price-new">
    {!! get_formated_price($item->current_sale_price(), config('system_settings.decimals', 2)) !!}
  </span>

  <ul class="product-info-feature-labels hidden">
    @foreach ($item->getLabels() as $label)
      <li>{!! $label !!}</li>
    @endforeach
  </ul>
</div>
