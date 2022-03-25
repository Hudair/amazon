@extends('theme::layouts.main')

@section('content')
  <!-- HEADER SECTION -->
  @include('theme::headers.order_detail')

  <!-- CONTENT SECTION -->
  @include('checkout::thankyou')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
