@if (isset($recently_viewed_items) && count($recently_viewed_items))
  <section>
    <div class="best-under">
      <div class="container">
        <div class="best-under__inner">
          <div class="best-under__header">
            <div class="sell-header sell-header--bold">
              <div class="sell-header__title">
                <h2>@lang('theme.recently_viewed')</h2>
              </div>
              <div class="header-line">
                <span></span>
              </div>
              <div class="best-deal__arrow">
                <ul>
                  <li>
                    <button class="left-arrow slider-arrow slick-arrow best-under-left"><i class="fal fa-chevron-left"></i></button>
                  </li>
                  <li>
                    <button class="right-arrow slider-arrow slick-arrow best-under-right"><i class="fal fa-chevron-right"></i></button>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="best-under__items">
            <div class="best-under__items-inner">
              {{-- <div class="best-under__items-box box">
                <div class="best-under__items-img box-img">
                  <a href="#"><img src="images/bfu1.png" alt=""></a>
                </div>
              </div> --}}
              @include('theme::partials._product_horizontal', ['products' => $recently_viewed_items, 'title' => 1, 'pricing' => 1, 'hover' => 1, 'ratings' => 1])
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
