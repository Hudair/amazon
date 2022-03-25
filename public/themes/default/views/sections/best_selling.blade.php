<section>
  <div class="recent">
    <div class="container">
      <div class="recent__inner">
        <div class="recent__header">
          <div class="sell-header sell-header--bold">
            <div class="sell-header__title">
              <h2>@lang('theme.best_selling_now')</h2>
            </div>
            <div class="header-line">
              <span></span>
            </div>
            <div class="best-deal__arrow">
              <ul>
                <li><button class="left-arrow slider-arrow slick-arrow recent-left"><i class="fal fa-chevron-left"></i></button></li>
                <li><button class="right-arrow slider-arrow slick-arrow recent-right"><i class="fal fa-chevron-right"></i></button></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="recent__items">
          <div class="recent__items-inner">

            {{-- <div class="recent__items-box box">
                            <div class="recent__items-img box-img">
                                <img src="images/p1.png" alt="">
                            </div>
                            <div class="recent__items-ratting box-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                </ul>
                            </div>
                            <div class="recent__items-title box-title">
                                <a href="#">letest gloves fight like pro player</a>
                            </div>
                            <div class="recent__items-price box-price">
                                <p class="recent__items-price-new box-price-new">$700</p>
                                <p class="recent__items-price-old box-price-old">$1400</p>
                            </div>
                        </div> --}}
            @include('theme::partials._product_horizontal', ['products' => $best_selling, 'hover' => 1])


          </div>
        </div>
      </div>
    </div>
  </div>
</section>
