<section>
  <div class="bundle">
    <div class="container">
      <div class="bundle__inner">
        <div class="bundle__header">
          <div class="sell-header sell-header--bold">
            <div class="sell-header__title">
              <h2>{{ trans('theme.bundle_offer') }}</h2>
            </div>
            <div class="header-line">
              <span></span>
            </div>
            <div class="best-deal__arrow">
              <ul>
                <li><button class="left-arrow slider-arrow slick-arrow bundle-left"><i class="fal fa-chevron-left"></i></button></li>
                <li><button class="right-arrow slider-arrow slick-arrow bundle-right"><i class="fal fa-chevron-right"></i></button></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="bundle__items">
          <div class="bundle__items-inner">

            {{-- <div class="bundle__items-box box">
                            <div class="bundle__items-img box-img">
                                <img src="images/p21.png" alt="">
                            </div>
                            <div class="bundle__items-ratting box-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                </ul>
                            </div>
                            <div class="bundle__items-title box-title">
                                <a href="#">letest gloves fight like pro player</a>
                            </div>
                            <div class="bundle__items-price box-price">
                                <p class="bundle__items-price-new box-price-new">$700</p>
                                <p class="bundle__items-price-old box-price-old">$1400</p>
                            </div>
                        </div> --}}

            @include('theme::partials._product_horizontal', ['products' => $bundle_offer, 'hover' => 1])

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
