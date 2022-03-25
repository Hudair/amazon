@if (isset($banners['place_two']))
  <div class="space20"></div>
  <div class="row featured">
    @foreach ($banners['place_two'] as $banner)
      @include('theme::layouts.banner', $banner)
    @endforeach
  </div><!-- /.row -->
@endif
