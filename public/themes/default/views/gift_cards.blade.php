@extends('theme::layouts.main')

@section('content')
  <!-- CATEGORY COVER IMAGE -->
  {{-- @include('theme::banners.category_cover', ['category' => $category]) --}}

  <!-- CONTENT SECTION -->
  @include('theme::contents.gift_card_shop')
@endsection
