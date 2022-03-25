@extends('theme::layouts.main')

@section('content')
  <!-- HEADER SECTION -->
  @include('theme::headers.product_page', ['product' => $item])

  <!-- CONTENT SECTION -->
  @include('theme::contents.product_page')

  <div class="clearfix space50"></div>

  <!-- RELATED ITEMS -->
  <section>
    <div class="feature">
      <div class="container">
        <div class="feature__inner">
          <div class="feature__header">
            <div class="sell-header sell-header--bold">
              <div class="sell-header__title">
                <h2>{!! trans('theme.related_items') !!}</h2>
              </div>
              <div class="header-line">
                <span></span>
              </div>
              <div class="header-line">
                <span></span>
              </div>
              <div class="best-deal__arrow">
              </div>
            </div>
          </div>

          <div class="feature__items">
            <div class="feature__items-inner">

              @include('theme::partials._product_horizontal', ['products' => $related])

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="clearfix space20"></div>

  <!-- BROWSING ITEMS -->
  @include('theme::sections.recent_views')

  <!-- MODALS -->
  @include('theme::modals.shopReviews', ['shop' => $item->shop])

  @if (Auth::guard('customer')->check())
    @include('theme::modals.contact_seller')
  @endif
@endsection

@section('scripts')
  @if (is_chat_enabled($item->shop))
    @include('theme::scripts.chatbox', ['shop' => $item->shop, 'agent' => $item->shop->owner, 'agent_status' => trans('theme.online')])
  @endif

  @include('theme::modals.ship_to')
  @include('theme::scripts.product_page')
@endsection
