<section class="my-5">
  <div class="container">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#description_tab" data-toggle="tab">
                {{ trans('theme.profile') }}
              </a></li>
            @if ($shop->config->return_refund)
              <li><a href="#refund_policy_tab" data-toggle="tab">
                  {{ trans('theme.return_and_refund_policy') }}
                </a></li>
            @endif
            <li><a href="#shop_reviews_tab" data-toggle="tab">
                {{ trans('theme.latest_reviews') }}
              </a></li>
            <li><a href="{{ route('shop.products', $shop->slug) }}">
                {{ trans('theme.products') }}
              </a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="description_tab">
              <div class="row">
                <div class="col-sm-3">
                  <img src="{{ get_avatar_src($shop->owner, 'avatar') }}" class="img-rounded" width="100%">
                </div>
                <div class="col-sm-9">
                  <span>
                    <i class="far fa-user mr-2 text-muted"></i> {!! $shop->owner->name !!}
                  </span>
                  <br />
                  <span>
                    <i class="far fa-map-marker-alt mr-2 text-muted"></i> {!! $shop->address->toShortString() !!}
                  </span>
                  <br />
                  @if ($shop->config->support_phone)
                    <span>
                      <i class="far fa-phone-square-alt mr-2 text-muted"></i> {!! $shop->config->support_phone !!}
                    </span>
                    <br />
                  @endif
                  @if ($shop->config->support_email)
                    <span>
                      <i class="far fa-envelope mr-2 text-muted"></i> {!! $shop->config->support_email !!}
                    </span>
                    <br />
                  @endif
                  <span>
                    <i class="far fa-shopping-cart mr-2 text-muted"></i> {{ trans('theme.items_sold') }} {{ \App\Helpers\Statistics::sold_items_count($shop->id) }}
                  </span>
                  <br />
                  <p class="mt-2">
                    <i class="far fa-store mr-2 text-muted"></i> {!! $shop->description !!}
                  </p>
                  <a href="{{ route('shop.products', $shop->slug) }}" class="btn btn-primary my-3">{{ trans('theme.products') }} ({{ $shop->inventories_count }})</a>
                </div>
              </div> <!-- /.row -->
            </div> <!-- /.tab-pane -->

            <div class="tab-pane" id="refund_policy_tab">
              {!! $shop->config->return_refund !!}
            </div> <!-- /.tab-pane -->

            <div class="tab-pane" id="shop_reviews_tab">
              @forelse($shop->feedbacks->sortByDesc('created_at') as $feedback)
                <p>
                  <b>{{ $feedback->customer->nice_name ?? $feedback->customer->name }}</b>

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
              @endforelse
            </div> <!-- /.tab-pane -->
          </div> <!-- /.tab-content -->
        </div> <!-- /.nav-tabs-custom -->
      </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
