@extends('theme::layouts.main')

@section('content')
  <!-- SHOP COVER IMAGE -->
  @include('theme::banners.shop_cover', ['shop' => $shop])

  <!-- CONTENT SECTION -->
  @if (\Route::currentRouteName() == 'shop.products')
    @include('theme::headers.shop_page')

    @include('theme::contents.shop_products')
  @else
    @include('theme::contents.shop_page')
  @endif

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')

  <!-- MODALS -->
  {{-- @include('theme::modals.shopReviews') --}}

@endsection

@section('scripts')
  @if (is_chat_enabled($shop))
    @include('theme::scripts.chatbox', ['shop' => $shop, 'agent' => $shop->owner, 'agent_status' => trans('theme.online')])
  @endif
@endsection
