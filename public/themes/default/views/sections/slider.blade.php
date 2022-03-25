<section class="mb-3">
  <div id="ei-slider" class="ei-slider">
    <ul class="ei-slider-large">
      @foreach ($sliders as $slider)
        <li>
          <a href="{{ $slider['link'] }}">
            <img src="{{ get_storage_file_url($slider['feature_image']['path'] ?? null, 'full') }}" alt="{{ $slider['title'] ?? 'Slider Image ' . $loop->count }}">
          </a>

          @if ($slider['sub_title'] || $slider['title'] || $slider['description'])
            <div class="banner__content-{{ $slider['text_position'] == 'right' ? 'left' : 'right' }}"></div>

            <div class="banner__content-{{ $slider['text_position'] ?? 'right' }}">
              <div class="banner__content-sub-title ">
                <h3 style="color: {{ $slider['sub_title_color'] }}">{!! $slider['sub_title'] !!}</h3>
              </div>
              <div class="banner__content-title">
                <h1 style="color: {{ $slider['title_color'] }}">{!! $slider['title'] !!}</h1>
              </div>
              <div class="banner__content-text">
                <p style="color: {{ $slider['description_color'] }}">{!! $slider['description'] !!}</p>
              </div>
              @if (!empty($slider['link']))
                <div class="banner__content-btn">
                  <a href="{{ $slider['link'] }}">{{ trans('theme.shop_now') }}</a>
                </div>
              @endif
            </div>
          @endif
        </li>
      @endforeach
    </ul><!-- ei-slider-large -->

    <ul class="ei-slider-thumbs">
      <li class="ei-slider-element">Current</li>

      @foreach ($sliders as $slider)
        <li>
          <a href="javascript:void(0)">Slide {{ $loop->count }}</a>
          <img src="{{ get_storage_file_url($slider['images'][0]['path'] ?? ($slider['feature_image']['path'] ?? null),'slider_thumb') }}" alt="thumbnail {{ $loop->count }}" />
        </li>
      @endforeach
    </ul>
  </div>
</section>
