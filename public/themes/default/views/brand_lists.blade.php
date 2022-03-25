@extends('theme::layouts.main')

@section('content')
  <!-- BRAND COVER IMAGE -->
  <section class="brand-cover-img-wrapper">
    <div class="banner banner-o-hid cover-img-wrapper" style="background-image:url( {{ asset('images/placeholders/brand_cover.jpg') }} );">
      <div class="page-cover-caption">
        <h5 class="page-cover-title">{{ trans('theme.all_brands') }}</h5>
        {{-- <p class="page-cover-desc">{!! trans('theme.all_brands') !!}</p> --}}
      </div>
    </div>
  </section>

  <!-- CONTENT SECTION -->
  @include('theme::contents.brand_list')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
