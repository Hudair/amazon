<div class="sell-header">
  <div class="sell-header__title">
    <h2 class="font-weight-bold">{{ trans('theme.flash_deal') }}</h2>
  </div>
  <div class="sell-header__sell">
    <h3>{{ trans('theme.offer_end_in') }} :</h3>
    <div class="sell-header__sell-time">
      <p>
        <span class="deal-counter-days"></span> {{ trans('theme.flash_deal_days') }} : <span class="deal-counter-hours"></span> {{ trans('theme.hrs') }} : <span class="deal-counter-minutes"></span> {{ trans('theme.mins') }} : <span class="deal-counter-seconds"></span> {{ trans('theme.sec') }}
      </p>
    </div>
  </div>
  <div class="header-line">
    <span></span>
  </div>
  <div class="best-deal__arrow">
    <ul>
      <li>
        <button class="left-arrow slider-arrow slick-arrow flashdeal-left"><i class="fal fa-chevron-left"></i></button>
      </li>

      <li>
        <button class="right-arrow slider-arrow slick-arrow flashdeal-right"><i class="fal fa-chevron-right"></i></button>
      </li>
    </ul>
  </div>
</div>

<div class="flashdeal">
  <div class="recent__inner">
    <div class="recent__items">
      <div class="flashdeal__items-inner">
        @include('theme::partials._product_horizontal', ['products' => $deals])
      </div>
    </div>
  </div>
</div>
