@extends('theme::layouts.main')

@section('content')
  <!-- breadcrumb -->
  @include('theme::headers.cart_page')

  <!-- CONTENT SECTION -->
  @include('theme::contents.cart_page')

  <div class="space50"></div>

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')
@endsection

@section('scripts')
  @include('theme::modals.ship_to')
  @include('theme::scripts.cart')
  @include('theme::scripts.dynamic_checkout')
@endsection
