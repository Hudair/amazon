<section class="brand-cover-img-wrapper">
  <div class="banner banner-o-hid cover-img-wrapper" style="background-image:url( {{ get_cover_img_src($brand, 'brand') }} );">
    <div class="page-cover-caption">
      {{-- <img src="{{ get_storage_file_url(optional($brand->logoImage)->path, 'thumbnail') }}" class="img-rounded"> --}}
      <h5 class="page-cover-title">
        <a href="{{ route('show.brand', $brand->slug) }}">
          {{ $brand->name }}
        </a>
      </h5>
      {{-- <p class="page-cover-desc">{!! \Str::limit($brand->description, 100) !!}</p> --}}
    </div>
  </div>
</section>
