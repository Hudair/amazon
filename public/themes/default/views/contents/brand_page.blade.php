<section class="my-5">
  <div class="container">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#description_tab" data-toggle="tab">
                {{ trans('theme.profile') }}
              </a></li>
            {{-- <li><a href="#product_videos_tab" data-toggle="tab">
	                        {{ trans('theme.product_videos') }}
	                      </a></li> --}}
            <li><a href="{{ route('brand.products', $brand->slug) }}">
                {{ trans('theme.products') }}
              </a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="description_tab">
              <div class="row">
                <div class="col-sm-3">
                  <img src="{{ get_storage_file_url(optional($brand->logoImage)->path, 'avatar') }}" class="img-rounded" width="100%">
                </div>
                <div class="col-sm-9">
                  <span>
                    <i class="far fa-crown mr-1 text-muted"></i> {!! $brand->name !!}
                  </span>
                  <br />
                  <span>
                    <i class="far fa-map-marker-alt mr-2 text-muted"></i> {!! optional($brand->country)->name !!}
                  </span>
                  <br />
                  <p class="mt-2">
                    <i class="far fa-store mr-2 text-muted"></i> {!! $brand->description !!}
                  </p>
                  <a href="{{ route('brand.products', $brand->slug) }}" class="btn btn-primary my-3">{{ trans('theme.products') }}</a>
                </div>
              </div> <!-- /.row -->
            </div> <!-- /.tab-pane -->

            <div class="tab-pane" id="product_videos_tab">
              {{-- @forelse($brand->feedbacks->sortByDesc('created_at') as $feedback)
	                            <p>
	                                <b>{{ $feedback->customer->nice_name ?? $feedback->customer->name  }}</b>

	                                <span class="pull-right small">
	                                    <b class="text-success">@lang('theme.verified_purchase')</b>
	                                    <span class="text-muted"> | {{ $feedback->created_at->diffForHumans() }}</span>
	                                </span>
	                            </p>

	                            <p>{{ $feedback->comment }}</p>

	                            @include('theme::layouts.ratings', ['ratings' => $feedback->rating])

	                            @unless($loop->last)
	                                <div class="sep"></div>
	                            @endunless
	                        @empty
	                            <div class="space20"></div>
	                            <p class="lead text-center text-muted">@lang('theme.no_reviews')</p>
	                        @endforelse --}}
            </div> <!-- /.tab-pane -->
          </div> <!-- /.tab-content -->
        </div> <!-- /.nav-tabs-custom -->
      </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
