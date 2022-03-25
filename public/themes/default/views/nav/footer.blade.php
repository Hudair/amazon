<footer>
  <div class="footer">
    <div class="container">
      <div class="footer__inner">
        <div class="footer__news">
          <div class="footer__news-inner">
            <div class="row">
              <div class="col-lg-6 col-12">
                <div class="footer__news-content">
                  <div class="footer__news-icon">
                    <img src="{{ theme_asset_url('img/mail.png') }}" alt="">
                  </div>
                  <div class="footer__news-text">
                    <h3>{{ trans('theme.subscription') }}</h3>
                    <p>{{ trans('theme.help.subscribe_to_newsletter') }}</p>
                  </div>
                </div>
              </div> <!-- /.col-lg-6 col-12 -->

              <div class="col-lg-6 col-12">
                <div class="footer__news-form">
                  {!! Form::open(['route' => 'newsletter.subscribe', 'class' => 'form-inline', 'id' => 'form', 'data-toggle' => 'validator']) !!}
                  <div class="footer__news-form-box">
                    {!! Form::email('email', null, ['placeholder' => trans('theme.placeholder.email'), 'required']) !!}
                    <button type="submit">{{ trans('theme.button.subscribe') }}</button>
                  </div>
                  {!! Form::close() !!}
                </div>
              </div> <!-- /.col-lg-6 col-12 -->
            </div> <!-- /.row -->
          </div> <!-- /.footer__news-inner -->
        </div> <!-- /.footer__news -->

        <div class="footer__content">
          <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
              <div class="footer__content-box">
                <div class="footer__content-box-inner">
                  <div class="footer__content-box-logo">
                    <a href="{{ url('/') }}">
                      <img src="{{ get_logo_url('platform', 'full') }}" class="brand-logo" alt="{{ trans('app.logo') }}" title="{{ trans('app.logo') }}" style="max-width: 70%; margin-bottom: 10px;">
                    </a>
                  </div>
                  <div class="footer__content-box-text">
                    <p>{!! config('system_settings.slogan') !!}</p>
                  </div>

                  <div class="footer__content-box-location">
                    <p><i class="fas fa-map-marker-alt"></i> {!! get_platform_address_string() !!}</p>
                  </div>

                  @if (config('system_settings.support_phone'))
                    <div class="footer__content-box-number">
                      <a href="tel: {!! config('system_settings.support_phone') !!}"><i class="fas fa-phone-alt"></i> {!! config('system_settings.support_phone') !!}</a>
                    </div>
                  @endif

                  {{-- <div class="footer__content-box-website">
                    <a href="{{ url('/') }}">{{ config('app.url') }}</a>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="col-lg-2 col-md-4  col-sm-6 col-6">
              <div class="footer__content-box">
                <div class="footer__content-box-inner">
                  <div class="footer__content-box-title">
                    <h3>{{ trans('theme.nav.let_us_help') }}</h3>
                  </div>
                  <div class="footer__content-box-links">
                    <ul>
                      <li>
                        <a href="{{ route('account', 'dashboard') }}" rel="nofollow">{{ trans('theme.nav.your_account') }}</a>
                      </li>
                      <li>
                        <a href="{{ route('account', 'orders') }}" rel="nofollow">{{ trans('theme.nav.your_orders') }}</a>
                      </li>
                      @foreach ($pages->where('position', 'footer_1st_column') as $page)
                        <li>
                          <a href="{{ get_page_url($page->slug) }}" rel="nofollow" target="_blank">
                            {{ $page->title }}
                          </a>
                        </li>
                      @endforeach
                      <li>
                        <a href="{{ route('blog') }}" target="_blank">{{ trans('theme.nav.blog') }}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 col-md-4  col-sm-6 col-6">
              <div class="footer__content-box">
                <div class="footer__content-box-inner">
                  <div class="footer__content-box-title">
                    <h3>{{ trans('theme.nav.make_money') }}</h3>
                  </div>
                  <div class="footer__content-box-links">
                    <ul>
                      <li>
                        <a href="{{ url('/selling') }}">{{ trans('theme.nav.sell_on', ['platform' => get_platform_title()]) }}</a>
                      </li>
                      <li>
                        <a href="{{ url('/selling#pricing') }}">{{ trans('theme.nav.become_merchant') }}</a>
                      </li>
                      <li>
                        <a href="{{ url('/selling#howItWorks') }}">{{ trans('theme.nav.how_it_works') }}</a>
                      </li>
                      @foreach ($pages->where('position', 'footer_2nd_column') as $page)
                        <li>
                          <a href="{{ get_page_url($page->slug) }}" rel="nofollow" target="_blank">
                            {{ $page->title }}
                          </a>
                        </li>
                      @endforeach
                      <li>
                        <a href="{{ url('/selling#faqs') }}" rel="nofollow">{{ trans('theme.nav.faq') }}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 col-md-4  col-sm-6 col-6">
              <div class="footer__content-box">
                <div class="footer__content-box-inner">
                  <div class="footer__content-box-title">
                    <h3>{{ trans('theme.nav.customer_service') }}</h3>
                  </div>
                  <div class="footer__content-box-links">
                    <ul>
                      <li>
                        <a href="{{ route('account', 'disputes') }}">{{ trans('theme.nav.refunds_disputes') }}</a>
                      </li>
                      <li>
                        <a href="{{ route('account', 'orders') }}">{{ trans('theme.nav.contact_seller') }}</a>
                      </li>
                      @foreach ($pages->where('position', 'footer_3rd_column') as $page)
                        <li>
                          <a href="{{ get_page_url($page->slug) }}" rel="nofollow" target="_blank">
                            {{ $page->title }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-4  col-sm-6 col-6">
              <div class="footer__content-box">
                <div class="footer__content-box-inner">
                  <div class="footer__content-box-title">
                    <h3>{{ trans('theme.stay_connected') }}</h3>
                  </div>

                  @if ($social_media_links = get_social_media_links())
                    <div class="footer__content-box-social">
                      <ul>
                        @foreach ($social_media_links as $social_media => $link)
                          <li>
                            <a href="{{ $link }}" target="_blank">
                              <i class="fab fa-{{ $social_media }}"></i>
                            </a>
                          </li>
                        @endforeach
                      </ul>

                      @if ($trust_badge = get_trust_badge_url())
                        <div class="mt-4 mb-2">
                          <img src="{{ $trust_badge }}" />
                        </div>
                      @endif
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div> <!-- /.row -->
        </div> <!-- /.footer__content -->
      </div> <!-- /.footer__inner -->
    </div> <!-- /.container -->
  </div> <!-- /.footer -->
</footer>

<!-- COPYRIGHT AREA -->
@include('theme::nav.copyright')
