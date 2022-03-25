@extends('theme::layouts.main')

@section('content')
  <!-- HEADER SECTION -->
  @include('theme::headers.order_detail')

  <!-- CONTENT SECTION -->
  @include('theme::contents.feedback_form')

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection
