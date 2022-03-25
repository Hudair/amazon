@extends('theme::layouts.main')

@section('content')
  <!-- CONTENT SECTION -->
  @include('theme::contents.offer_page')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
