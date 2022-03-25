@extends('theme::layouts.main')

@section('content')
  <!-- CATEGORY COVER IMAGE -->
  @include('theme::banners.category_cover', ['category' => $category])

  <!-- HEADER SECTION -->
  @include('theme::headers.category_page', ['category' => $category])

  <!-- CONTENT SECTION -->
  @include('theme::contents.category_page')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')

  <!-- bottom Banner -->
  @include('theme::banners.bottom')
@endsection
