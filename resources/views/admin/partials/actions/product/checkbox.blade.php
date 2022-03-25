<td>
  @can('massDelete', \App\Models\Product::class)
    @if (\Auth::user()->isFromPlatform() || ($product->inventories_count == 0 && $product->shop_id == \Auth::user()->shop_id))
      <input id="{{ $product->id }}" type="checkbox" class="massCheck">
    @endif
  @endcan
</td>
