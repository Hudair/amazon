@extends('theme::layouts.main')

@section('content')
  <!-- BRAND COVER IMAGE -->
  <section class="brand-cover-img-wrapper">
    <div class="banner banner-o-hid cover-img-wrapper" style="background-image:url( {{ asset('images/placeholders/shop_cover.jpg') }} );">
      <div class="page-cover-caption">
        <h5 class="page-cover-title">{{ trans('theme.all_shops') }}</h5>
        {{-- <p class="page-cover-desc">{!! trans('theme.all_shops') !!}</p> --}}
      </div>
    </div>
  </section>

  <!-- CONTENT SECTION -->
  @include('theme::contents.shop_list')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
