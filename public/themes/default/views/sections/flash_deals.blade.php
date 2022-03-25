@if (isset($flashdeals))
  <section id="flash-deal">
    <div class="flash-deal {{ empty($flashdeals['listings']) ? 'pt-0' : 'pt-2' }}">
      <div class="container">
        <div class="flash-deal__inner">
          @unless(empty($flashdeals['listings']))
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

                    @include('theme::partials._product_horizontal', ['products' => $flashdeals['listings']])

                  </div>
                </div>
              </div>
            </div>
          @endunless

          <!-- Feathered flash deal start -->
          <div class="flash-deal__product-main">
            <div class="row">
              @unless(empty($flashdeals['featured']))
                @foreach ($flashdeals['featured'] as $item)
                  <div class="col-12 col-md-6 my-3">
                    <div class="flash-deal__product" style="{{ empty($flashdeals['products']) ? 'margin-top: 0' : '' }}">
                      <div class="flash-deal__product-inner">
                        <a class="flash-deal__product-name" href="{{ route('show.product', $item->slug) }}">
                          <h3>{!! \Str::limit($item->title, 100) !!}</h3>
                        </a>

                        <div class="flash-deal__product-image">
                          <div class="flash-deal__product-badge">
                            <span>{!! $item->condition !!}</span>
                          </div>

                          <a href="{{ route('show.product', $item->slug) }}">
                            <img src="{{ get_inventory_img_src($item, 'medium') }}" data-name="product_image" alt="{!! $item->title !!}" title="{!! $item->title !!}">
                          </a>

                          {{-- <div class="flash-deal__product-utility">
                                                    @include('theme::partials._vertical_hover_buttons')
                                                </div> --}}
                        </div>

                        <div class="flash-deal__product-details">
                          {{-- <a class="flash-deal__product-name" href="{{route('show.product', $item->slug)}}">
                                                    <h3>{{ $item->title }}</h3>
                                                </a> --}}

                          <div class="flash-deal__product-price">
                            <span class="currant-price">{!! get_formated_price($item->current_sale_price(), config('system_settings.decimals', 2)) !!}</span>

                            @if ($item->hasOffer())
                              <span class="old-price">{!! get_formated_price($item->sale_price, config('system_settings.decimals', 2)) !!}</span>

                              <span class="offer">
                                {{ trans('theme.percent_off', ['value' => $item->discount_percentage()]) }}

                                {{-- -{{ round(((($item->sale_price - $item->current_sale_price()) / $item->sale_price) * 100), 2) }}% --}}
                              </span>
                            @endif
                          </div>
                          <div class="flash-deal__product-description">
                            <p>{!! \Str::limit($item->description, 120) !!}</p>
                          </div>
                          <div class="flash-deal__product-rating">
                            @include('theme::partials._vertical_ratings', ['ratings' => $item->ratings])
                            {{-- @include('theme::partials._vertical_ratings', ['ratings' => $item->feedbacks->avg('rating')]) --}}
                          </div>
                          <div class="flash-deal__product-availability">
                            <span>{{ trans('theme.availability') }}:</span>
                            <p>{{ trans('theme.stock', ['stock' => $item->stock_quantity]) }}</p>
                          </div>
                          <div class="flash-deal__product-sell-time">
                            <h3>
                              <span>
                                <span class="deal-counter-days"></span><br> {{ trans('theme.flash_deal_days') }}
                              </span>
                              <span class="spacing">:</span>
                              <span>
                                <span class="deal-counter-hours"></span><br>
                                {{ trans('theme.hrs') }}
                              </span>
                              <span class="spacing">:</span>
                              <span>
                                <span class="deal-counter-minutes"></span><br>
                                {{ trans('theme.mins') }}
                              </span>
                              <span class="spacing">:</span>
                              <span>
                                <span class="deal-counter-seconds"></span><br>
                                {{ trans('theme.sec') }}
                              </span>
                            </h3>
                          </div>
                        </div>

                        <div class="flash-deal__product-utility">
                          @include('theme::partials._vertical_hover_buttons')
                        </div>
                      </div> <!-- Product inner End-->
                    </div> <!-- Product End-->
                  </div>
                @endforeach
              @endunless
            </div>
          </div> <!-- Feathered flash deal end -->
        </div>
      </div>
    </div>
  </section>
@endif
