<section class="mt-1 mb-0">
  <div class="container">
    <div class="featured-categories owl-carousel hide">
      @foreach ($featured_category as $item)
        <div class="featured-category">
          <a href="{{ route('category.browse', $item->slug) }}">
            <figure>
              <img src="{{ get_storage_file_url(optional($item->featureImage)->path, 'full') }}" alt="{{ $item->name }}">
            </figure>

            <div class="featured-category-content py-3">
              <h3 class="mb-3">{{ $item->name }}</h3>
              <span> {{ trans('theme.listings_count', ['count' => $item->listings_count]) }}</span>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
