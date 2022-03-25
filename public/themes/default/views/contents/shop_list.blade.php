<!-- CONTENT SECTION -->
<section>
  <div class="container text-center mb-5 mt-0">
    <div class="row thumb-lists">
      @foreach ($shops as $shop)
        <div class="col-6 col-md-2 my-5">
          <span class="vertical-helper"></span>
          <a href="{{ route('show.store', $shop->slug) }}" class="">
            @if (config('system_settings.show_merchant_info_as_vendor'))
              <img src="{{ get_avatar_src($shop->owner, 'logo_lg') }}" class="img-rounded">
              {{-- @include('theme::layouts.ratings', ['ratings' => $shop->ratings, 'count' => $shop->ratings_count]) --}}
              <p class="thumb-list-name">
                {!! $shop->owner->getName() !!}<br />
                {!! $shop->address->toShortString() !!}
              </p>
            @else
              <img src="{{ get_storage_file_url(optional($shop->logoImage)->path, 'logo_lg') }}" class="img-rounded">
              <p class="thumb-list-name">
                {!! $shop->getQualifiedName() !!}<br />
                {!! $shop->address->toShortString() !!}
              </p>
            @endif
          </a>
          <span class="vertical-helper"></span>
        </div>
      @endforeach
    </div><!-- /.row -->

    <div class="row pagenav-wrapper mt-4">
      {{ $shops->links('theme::layouts.pagination') }}
    </div><!-- /.row .pagenav-wrapper -->
  </div><!-- /.container -->
</section>
<!-- END CONTENT SECTION -->
