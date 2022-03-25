<div class="clearfix">
  @php
    $pImg = get_product_img_src($item, 'large');
  @endphp
  <a href="{{ $pImg }}" id="{{ $zoomID ?? 'jqzoom' }}" data-rel="gal-1">
    <img class="product-img" data-name="product_image" src="{{ $pImg }}" alt="{{ $item->title }}" title="{{ $item->title }}" />
  </a>
</div>

<ul class="jqzoom-thumbs mt-2">
  @php
    $item_images = $item->images->count() ? $item->images : $item->product->images;
    
    if (isset($variants)) {
        // Remove images of current items from the variants imgs
        $other_images = $variants
            ->pluck('images')
            ->flatten(1)
            ->filter(function ($value, $key) use ($item) {
                return $value->imageable_id != $item->id;
            });
        $item_images = $item_images->concat($other_images);
    }
  @endphp

  @foreach ($item_images as $img)
    @continue(!$img->path)

    @php
      $fImg = get_storage_file_url($img->path, 'full');
    @endphp

    <li>
      <a class="d-flex flex-wrap align-items-center {{ $img->path == optional($item->image)->path ? 'zoomThumbActive' : '' }}" href="javascript:void(0)" data-rel="{gallery:'gal-1', smallimage: '{{ $fImg }}', largeimage: '{{ $fImg }}'}">
        <img src="{{ get_storage_file_url($img->path, 'thumbnail') }}" alt="Thumb" title="{{ $item->title }}" />
      </a>
    </li>
  @endforeach
</ul> <!-- /.jqzoom-thumbs -->
