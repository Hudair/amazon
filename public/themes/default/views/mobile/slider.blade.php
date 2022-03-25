<section class="mb-2">
  <div id="ei-slider" class="ei-slider">
    <ul class="ei-slider-large">
      @foreach ($sliders as $slider)
        @if (isset($slider['mobile_image']['path']))
          <li>
            <a href="{{ $slider['link'] }}">
              <img src="{{ get_storage_file_url($slider['mobile_image']['path'], 'full') }}" alt="{{ $slider['title'] ?? 'Slider Image ' . $loop->count }}">
            </a>
          </li>
        @endif
      @endforeach
    </ul> <!-- ei-slider-large -->

    {{-- Need this section to avoid js error --}}
    <ul class="ei-slider-thumbs">
      <li class="ei-slider-element">Current</li>

      @foreach ($sliders as $slider)
        @if (isset($slider['mobile_image']['path']))
          <li></li>
        @endif
      @endforeach
    </ul>
  </div>
</section>
