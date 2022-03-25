@if ($deal_of_the_day)
  <section>
    <div class="best-deal">
      <div class="container">
        <div class="best-deal__inner">
          <div class="row">
            <div class="col-lg-{{ $featured_items ? '8' : '12' }}">
              <div class="best-deal__col">
                <div class="best-deal__header">
                  <div class="sell-header">
                    <div class="sell-header__title">
                      <h2>{{ trans('theme.deal_of_the_day') }}</h2>
                    </div>
                    <div class="header-line">
                      <span></span>
                    </div>
                  </div>
                </div>

                <div class="week-deal">
                  <div class="week-deal__label">{{ trans('theme.hot') }}</div>
                  <div class="week-deal__inner">
                    <div class="week-deal__slider deal-slider">
                      @foreach ($deal_of_the_day->images as $img)
                        <div class="week-deal__slider-item">
                          <img src="{{ get_storage_file_url($img->path, 'medium') }}" alt="{{ $deal_of_the_day->title }}">
                        </div>
                      @endforeach
                    </div>
                    <div class="week-deal__details">
                      <div class="week-deal__details-name">
                        <a href="{{ route('show.product', $deal_of_the_day->slug) }}">{!! strip_tags($deal_of_the_day->title) !!}</a>
                      </div>

                      <div class="week-deal__details-price">
                        <p>
                          <span class="regular-price">
                            {!! get_formated_price($deal_of_the_day->current_sale_price(), config('system_settings.decimals', 2)) !!}
                          </span>

                          @if ($deal_of_the_day->hasOffer())
                            <span class="old-price">
                              {!! get_formated_price($deal_of_the_day->sale_price, config('system_settings.decimals', 2)) !!}
                            </span>
                          @endif
                        </p>
                      </div>

                      <div class="best-seller__item-rating">
                        @include('theme::partials._vertical_ratings', ['ratings' => $deal_of_the_day->ratings])
                      </div>

                      <div class="week-deal__details-description">
                        <p>{{ substr(strip_tags($deal_of_the_day->description), 0, 100) }}</p>
                      </div>

                      <div class="week-deal__details-list">
                        <ul>
                          @if ($feature = unserialize($deal_of_the_day->key_features))
                            @for ($i = 0; $i < 3; $i++)
                              <li><i class="fal fa-check"></i> <span>{{ !empty($feature[$i]) ? $feature[$i] : null }}</span></li>
                            @endfor
                          @endif
                        </ul>
                      </div>

                      <div class="week-deal-btns mt-4">
                        <a href="javascript:void(0)" data-link="{{ route('cart.addItem', $deal_of_the_day->slug) }}" class="sc-add-to-cart" tabindex="0">
                          <i class="fal fa-shopping-cart"></i>
                          <span class="d-none d-sm-inline-block">{{ trans('theme.add_to_cart') }}</span>
                        </a>

                        <a href="javascript:void(0)" data-link="{{ route('wishlist.add', $deal_of_the_day) }}" class="add-to-wishlist">
                          <i class="far fa-heart"></i> {{ trans('theme.button.add_to_wishlist') }}
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> <!-- .col-lg-* -->

            @if ($featured_items)
              <div class="col-lg-4">
                <div class="best-deal__col">
                  <div class="best-deal__header">
                    <div class="sell-header">
                      <div class="sell-header__title">
                        <h2>{{ trans('theme.featured') }}</h2>
                      </div>
                      <div class="header-line">
                        <span></span>
                      </div>
                      <div class="best-deal__arrow">
                        <ul>
                          <li>
                            <button class="left-arrow slider-arrow best-seller-left">
                              <i class="fal fa-chevron-left"></i>
                            </button>
                          </li>
                          <li>
                            <button class="right-arrow slider-arrow best-seller-right">
                              <i class="fal fa-chevron-right"></i>
                            </button>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="best-seller">
                    <div class="best-seller__slider best-seller-slider">
                      @include('theme::partials._product_vertical', ['products' => $featured_items])
                    </div>
                  </div>
                </div>
              </div> <!-- .col-lg-4 -->
            @endif
          </div> <!-- .row -->
        </div>
      </div>
    </div> <!-- .best-deal -->
  </section>
@elseif($featured_items)
  <section>
    <div class="neckbands">
      <div class="container">
        <div class="neckbands__inner">
          <div class="neckbands__header">
            <div class="sell-header sell-header--bold">
              <div class="sell-header__title">
                <h2>{{ trans('theme.featured') }}</h2>
              </div>
              <div class="header-line">
                <span></span>
              </div>
              <div class="best-deal__arrow">
                <ul>
                  <li><button class="left-arrow slider-arrow slick-arrow neckbands-left"><i class="fal fa-chevron-left"></i></button></li>
                  <li><button class="right-arrow slider-arrow slick-arrow neckbands-right"><i class="fal fa-chevron-right"></i></button></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="neckbands__items">
            <div class="neckbands__items-inner">
              @include('theme::partials._product_horizontal', ['products' => $featured_items])
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
