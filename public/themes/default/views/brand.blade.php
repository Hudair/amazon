@extends('theme::layouts.main')

@section('content')
  <!-- BRAND COVER IMAGE -->
  @include('theme::banners.brand_cover', ['brand' => $brand])

  <!-- CONTENT SECTION -->
  @if (\Route::currentRouteName() == 'brand.products')
    @include('theme::headers.brand_page')

    @include('theme::contents.brand_products')
  @else
    @include('theme::contents.brand_page')
  @endif

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
