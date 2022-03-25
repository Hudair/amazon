@extends('theme::layouts.main')

@section('content')
  <!-- CONTENT SECTION -->
  @include('theme::contents.categories_page')

  <!-- Recently Viewed -->
  @include('theme::sections.recent_views')
@endsection
