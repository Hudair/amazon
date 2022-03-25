<div class="main-menu mobile-mega-menu">
  <nav>
    <div class="main-menu__top pt-0">
      <div class="main-menu__top-inner">
        <div class="main-menu__top-box">
          <!-- <div class="main-menu__top-item"><a href="#"><i class="fal fa-user"></i></a></div> -->
          @auth('customer')
            <div class="main-menu__top-item">
              <a href="{{ route('account', 'dashboard') }}" class="text-center">
                <i class="fal fa-user small"></i>
                <p class="small">{{ trans('theme.account') }}</p>
              </a>
            </div>

            <div class="main-menu__top-item">
              <a href="{{ route('customer.logout') }}" class="text-center">
                <i class="fal fa-sign-out-alt"></i>
                <p class="small">{{ trans('theme.logout') }}</p>
              </a>
            </div>
          @else
            <div class="main-menu__top-item">
              <a href="{{ route('account', 'dashboard') }}" class="text-center">
                <i class="fal fa-sign-in-alt small"></i>
                <p>{{ trans('theme.login') }}</p>
              </a>
            </div>
            @endif
            <!-- <div class="main-menu__top-item"><a href="#"><i class="fal fa-wallet"></i></a></div> -->
            <div class="main-menu__top-item">
              <div class="form-group">
                <select name="lang" id="mobile-lang">
                  @foreach (config('active_locales') as $lang)
                    <option dd-link="{{ route('locale.change', $lang->code) }}" value="{{ $lang->code }}" data-imagesrc="{{ get_flag_img_by_code(array_slice(explode('_', $lang->php_locale_code), -1)[0], true) }}" {{ $lang->code == \App::getLocale() ? 'selected' : '' }}>
                      {{ $lang->code }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            {{-- <div class="main-menu__top-item">
            <div class="form-group">
              <select name="" id="mobile-curency">
                <option value="usd" data-imagesrc="icon/lang3.png">USD</option>
                <option value="jpy" data-imagesrc="icon/lang4.png">JPY</option>
                <option value="eur" data-imagesrc="icon/lang5.png">EUR</option>
                <option value="aud" data-imagesrc="icon/lang6.png">AUD</option>
              </select>
            </div>
          </div> --}}
          </div>
        </div>
      </div>

      <ul class="main-menu-nav">
        @foreach ($all_categories as $catGroup)
          @if ($catGroup->subGroups->count())
            @php
              $categories_count = $catGroup->subGroups->sum('categories_count');
              // $cat_counter = 0;
            @endphp
            <li>
              <a href="{{ route('categoryGrp.browse', $catGroup->slug) }}">{{ $catGroup->name }}</a>
              <ul>
                @foreach ($catGroup->subGroups as $subGroup)
                  @php
                    // $cat_counter = 0; //Reset the counter
                  @endphp
                  <li>
                    <a href="{{ route('categories.browse', $subGroup->slug) }}">{{ $subGroup->name }}</a>
                    <ul>
                      @foreach ($subGroup->categories as $cat)
                        <li>
                          <a href="{{ route('category.browse', $cat->slug) }}">{{ $cat->name }}</a>
                        </li>
                        @php
                          // $cat_counter++;  //Increase the counter value by 1
                        @endphp
                      @endforeach
                    </ul>
                  </li>
                @endforeach
              </ul>
            </li>
          @endif
        @endforeach
      </ul>
    </nav>
  </div>

  <div class="main-menu__bottom">
    <div class="main-menu__bottom-inner">
      <div class="main-menu__bottom-box">
        {{-- <div class="main-menu__bottom-item" >
        <a href="#">
          <i class="fal fa-map-marker-alt small"></i> <span>{{ trans('theme.location') }}</span>
        </a>
      </div> --}}

        <div class="main-menu__bottom-item">
          <a href="{{ route('account', 'wishlist') }}">
            <i class="fal fa-heart small"></i> <span>{{ trans('theme.wishlist') }}</span>
            {{-- <span class="badge">{{ $cart_item_count }}</span> --}}
          </a>
        </div>

        <div class="main-menu__bottom-item">
          <a href="{{ route('brands') }}">
            <i class="fal fa-crown small"></i> <span>{{ trans('theme.brands') }}</span>
          </a>
        </div>

        <div class="main-menu__bottom-item">
          <a href="{{ route('shops') }}">
            <i class="fal fa-store small"></i> <span>{{ trans('theme.vendors') }}</span>
          </a>
        </div>

        <div class="main-menu__bottom-item">
          <a href="{{ url('/selling') }}">
            <i class="fal fa-seedling small"></i> <span>{{ trans('theme.sell') }}</span>
          </a>
        </div>
        {{-- <div class="main-menu__bottom-item">
        <a href="{{route('account', 'account')}}">
          <i class="fal fa-wallet small"></i> <span>{{ trans('wallet::lang.wallet') }}</span>
        </a>
      </div> --}}
        {{-- <div class="main-menu__bottom-item">
        <a href="{{route('account', 'wishlist')}}">
          <i class="fal fa-heart small"></i> <span>{{ trans('theme.wishlist') }}</span>
        </a>
      </div> --}}
        {{-- <div class="main-menu__bottom-item">
        <a href="{{route('account', 'account')}}">
          <i class="fal fa-truck small"></i> <span>{{ trans('theme.orders') }}</span>
        </a>
      </div> --}}
      </div>
    </div>
  </div>
