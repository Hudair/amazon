@if (isset($banners['sidebar']))
  <div class="row sidebar-banner-wrapper">
    @foreach ($banners['sidebar'] as $banner)
      @include('theme::layouts.banner', $banner)
    @endforeach
  </div>
@endif
