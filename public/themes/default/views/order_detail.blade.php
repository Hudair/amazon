@extends('theme::layouts.main')

@section('content')
  <!-- HEADER SECTION -->
  @include('theme::headers.order_detail')

  <!-- CONTENT SECTION -->
  @include('theme::contents.order_detail')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
