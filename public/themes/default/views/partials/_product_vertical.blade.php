@foreach ($products as $item)
  <div class="best-seller__item">
    <div class="best-seller__item-inner">
      <div class="best-seller__item-image">
        <a href="{{ route('show.product', $item->slug) }}">
          <img src="{{ get_inventory_img_src($item, 'small') }}" data-name="product_image" alt="{{ $item->title }}" title="{{ $item->title }}">
        </a>
      </div>
      <div class="best-seller__item-details">
        <div class="best-seller__item-details-inner">
          <div class="best-seller__item-name">
            <a href="{{ route('show.product', $item->slug) }}">{!! \Str::limit($item->title, 80) !!}</a>
          </div>
          <div class="best-seller__item-rating">
            @include('theme::partials._vertical_ratings', ['ratings' => $item->ratings])
            {{-- @include('theme::partials._vertical_ratings', ['ratings' => $item->feedbacks()->avg('rating')]) --}}
          </div>
          <div class="best-seller__item-price">
            @include('theme::partials._home_pricing')
          </div>
          <div class="best-seller__item-utility">
            @include('theme::partials._vertical_hover_buttons')
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
