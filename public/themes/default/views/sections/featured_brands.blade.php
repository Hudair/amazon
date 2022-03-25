@if (count($featured_brands))
  <section class="mb-3">
    <div class="feature-brand">
      <div class="container">
        <div class="feature-brand__inner">
          <div class="bundle__header">
            <div class="sell-header sell-header--bold">
              <div class="sell-header__title">
                <h2>{{ trans('theme.featured_brand') }}</h2>
              </div>
              <div class="header-line">
                <span></span>
              </div>
            </div>
          </div>
          <div class="feature-brand-content">
            <div class="row">
              @foreach ($featured_brands as $brand)
                <div class="col-lg-4 col-12">
                  <div class="feature-brand__img mb-3">
                    <a href="{{ route('show.brand', $brand->slug) }}">
                      <img src="{{ get_storage_file_url(optional($brand->featureImage)->path, 'full') }}" alt="{{ $brand->name }}">
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
