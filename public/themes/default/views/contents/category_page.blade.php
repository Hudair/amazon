<section>
  <div class="container full-width mb-4">
    <div class="row">
      <div class="col-md-3 bg-light">
        @include('theme::contents.product_list_sidebar_filters')
      </div><!-- /.col-sm-2 -->

      <div class="col-md-9" style="padding-left: 15px;">
        @if ($products->count())

          @include('theme::contents.product_list', ['colum' => 4])

          @if (config('system_settings.show_seo_info_to_frontend'))
            <div class="clearfix space20"></div>
            <span class="lead">{!! $category->meta_title !!}</span>
            <p>{!! $category->meta_description !!}</p>
            <div class="clearfix space20"></div>
          @endif
        @else
          <p class="lead text-center mt-5">
            {{ trans('theme.no_product_found') }}
          </p>
          <div class="my-3 text-center">
            <a href="{{ url('categories') }}" class="btn btn-primary flat">{{ trans('theme.button.shop_from_other_categories') }}</a>
          </div>
        @endif
      </div><!-- /.col-sm-10 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
