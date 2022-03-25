<section>
  <div class="container full-width">
    <div class="row">
      @if ($products->count())
        <div class="col-md-3 bg-light">
          @include('theme::contents.product_list_sidebar_filters')
        </div><!-- /.col-sm-2 -->

        <div class="col-md-9" style="padding-left: 15px;">
          @include('theme::contents.product_list', ['colum' => 4])
        </div><!-- /.col-sm-10 -->
      @else
        <div class="col-sm-12">
          <div class="clearfix space50"></div>
          <p class="lead text-center space50">
            <span class="space50">@lang('theme.no_product_found')</span><br />
          <div class="space50 text-center">
            <a href="{{ url('categories') }}" class="btn btn-primary btn-sm flat">@lang('theme.button.choose_from_categories')</a>
          </div>
          </p>
          <div class="clearfix space50"></div>
        </div><!-- /.col-sm-12 -->
      @endif
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
