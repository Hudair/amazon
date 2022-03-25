@extends('theme::layouts.main')

@section('content')
  <!-- HEADER SECTION -->
  @include('theme::headers.search_results')

  <!-- CONTENT SECTION -->
  @include('theme::contents.search_results')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
