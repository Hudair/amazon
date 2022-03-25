<div class="col-md-{{ $banner['columns'] }}">
  <div class="banner banner-o-hid outline-effect animated zoomIn" style="background-color: {{ $banner['bg_color'] }}; background-image:url( {{ isset($banner['images'][0]['path']) && Storage::exists($banner['images'][0]['path']) ? get_storage_file_url($banner['images'][0]['path'], 'full') : '' }} );">

    <a class="banner-link btn btn-link" href="{{ $banner['link'] }}"></a>

    <div class="banner-caption">
      <h5 class="banner-title">{{ $banner['title'] }}</h5>
      <p class="banner-desc">{{ $banner['description'] }}</p>
      <p class="banner-link-btn">{!! $banner['link_label'] ? $banner['link_label'] . ' <i class="fas fa-caret-right"></i>' : '' !!}</p>
    </div>

    @if (Storage::exists($banner['featured_image']['path']))
      <img class="banner-img" src="{{ get_storage_file_url($banner['featured_image']['path'], 'full') }}" alt="{{ $banner['title'] or 'Banner Image' }}" title="{{ $banner['title'] or 'Banner Image' }}">
    @endif
  </div>
</div>
