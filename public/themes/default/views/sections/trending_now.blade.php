@if (count($trending_categories))
  <section>
    <div class="feature">
      <div class="container">
        <div class="feature__inner">
          <div class="feature__header">
            <div class="sell-header sell-header--bold">
              <div class="sell-header__title">
                <h2>{!! trans('theme.trending_now') !!}</h2>
              </div>

              <div class="header-line">
                <span></span>
              </div>

              <div class="feature__tabs">
                <ul>
                  @foreach ($trending_categories as $trendingCat)
                    <li class="{{ $loop->first ? 'active' : '' }}">
                      <a href="#trending-{{ $trendingCat->slug }}">
                        {!! $trendingCat->name !!}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>

              <div class="header-line">
                <span></span>
              </div>

              <div class="best-deal__arrow">
                {{-- <ul>
                  <li><button class="left-arrow slider-arrow slick-arrow feature-left1"><i class="fal fa-chevron-left"></i></button></li>
                  <li><button class="right-arrow slider-arrow slick-arrow feature-right1"><i class="fal fa-chevron-right"></i></button></li>
                </ul> --}}
              </div>
            </div>
          </div>

          <div class="feature__items">
            @foreach ($trending_categories as $trendingCat)
              <div class="feature__items-inner" id="trending-{{ $trendingCat->slug }}">
                @include('theme::partials._product_horizontal', ['products' => $trendingCat->listings])
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
