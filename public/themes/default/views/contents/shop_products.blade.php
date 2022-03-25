<div class="clearfix space20"></div>
<section>
  <div class="container mb-5">
    <div class="row">
      <div class="col-md-12">
        @if ($products->count())
          @include('theme::contents.product_list')
        @else
          <div class="lead text-center my-5">
            <div class="mb-4">{{ trans('theme.no_product_found') }}</div>
            <div class="mb-4 text-center">
              <a href="{{ url('categories') }}" class="btn btn-primary btn-sm flat">
                {{ trans('theme.button.choose_from_categories') }}
              </a>
            </div>
          </div>
        @endif
      </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
