<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-body" style="padding: 0px;">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 5px; right: 10px; z-index: 9;">×</button>

      <div class="col-md-3 nopadding" style="margin-top: 10px;">
        @if ($product->featuredImage)
          <img src="{{ get_storage_file_url(optional($product->featuredImage)->path, 'medium') }}" class="thumbnail" width="100%" alt="{{ trans('app.featured_image') }}">
        @else
          <img src="{{ get_storage_file_url(optional($product->image)->path, 'medium') }}" class="thumbnail" width="100%" alt="{{ trans('app.featured_image') }}">
        @endif
      </div>
      <div class="col-md-9 nopadding">
        <table class="table no-border">
          <tr>
            <th class="text-right">{{ trans('app.name') }}:</th>
            <td style="width: 75%;"><span class="lead">{{ $product->name }}</span></td>
          </tr>

          @if ($product->brand)
            <tr>
              <th class="text-right">{{ trans('app.brand') }}: </th>
              <td style="width: 75%;">{{ $product->brand }}</td>
            </tr>
          @endif

          @if ($product->model_number)
            <tr>
              <th class="text-right">{{ trans('app.model_number') }}:</th>
              <td style="width: 75%;">{{ $product->model_number }}</td>
            </tr>
          @endif

          <tr>
            <th class="text-right">{{ trans('app.status') }}: </th>
            <td style="width: 75%;">{{ $product->active ? trans('app.active') : trans('app.inactive') }}</td>
          </tr>
          <tr>
            <th class="text-right">{{ trans('app.available_from') }}:</th>
            <td style="width: 75%;">{{ $product->created_at->toFormattedDateString() }}</td>
          </tr>
          <tr>
            <th class="text-right">{{ trans('app.updated_at') }}:</th>
            <td style="width: 75%;">{{ $product->updated_at->toDayDateTimeString() }}</td>
          </tr>
        </table>
      </div>
      <div class="clearfix"></div>
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs nav-justified">
          <li class="active"><a href="#basic_info_tab" data-toggle="tab">
              {{ trans('app.basic_info') }}
            </a></li>
          <li><a href="#description_tab" data-toggle="tab">
              {{ trans('app.description') }}
            </a></li>
          <li><a href="#listings_tab" data-toggle="tab">
              {{ trans('app.listings') }}
            </a></li>
          <li><a href="#seo_tab" data-toggle="tab">
              {{ trans('app.seo') }}
            </a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="basic_info_tab">
            <table class="table">
              <tr>
                <th>{{ trans('app.requires_shipping') }}:</th>
                <td>{{ $product->requires_shipping ? trans('app.yes') : trans('app.no') }}</td>
              </tr>

              {{-- <tr>
								<th>{{ trans('app.downloadable') }}:</th>
								<td>{{ $product->downloadable ? trans('app.yes') : trans('app.no') }}</td>
							</tr> --}}

              {{-- <tr>
                <th>{{ trans('app.has_variant') }}:</th>
                <td>{{ $product->hasAttributes() ? trans('app.yes') : trans('app.no') }}</td>
              </tr> --}}

              @if ($product->manufacturer)
                <tr>
                  <th>{{ trans('app.manufacturer') }}: </th>
                  <td>{{ $product->manufacturer->name }}</td>
                </tr>
              @endif

              @if ($product->origin)
                <tr>
                  <th>{{ trans('app.origin') }}: </th>
                  <td>{{ $product->origin->name }}</td>
                </tr>
              @endif

              @if ($product->gtin_type && $product->gtin)
                <tr>
                  <th>{{ $product->gtin_type }}: </th>
                  <td>{{ $product->gtin }}</td>
                </tr>
              @endif

              @if ($product->mpn)
                <tr>
                  <th>{{ trans('app.mpn') }}: </th>
                  <td>{{ $product->mpn }}</td>
                </tr>
              @endif

              <tr>
                <th>{{ trans('app.categories') }}: </th>
                <td>
                  @foreach ($product->categories as $category)
                    <span class="label label-outline">{{ $category->name }}</span>
                  @endforeach
                </td>
              </tr>

              @if ($product->min_price && $product->min_price != 0)
                <tr>
                  <th>{{ trans('app.min_price') }}: </th>
                  <td>{{ get_formated_currency($product->min_price, 2) }}</td>
                </tr>
              @endif
              @if ($product->max_price && $product->max_price != 0)
                <tr>
                  <th>{{ trans('app.max_price') }}: </th>
                  <td>{{ get_formated_currency($product->max_price, 2) }}</td>
                </tr>
              @endif
            </table>
          </div> <!-- /.tab-pane -->
          <div class="tab-pane" id="description_tab">
            <div class="box-body">
              @if ($product->description)
                {!! htmlspecialchars_decode($product->description) !!}
              @else
                <p>{{ trans('app.description_not_available') }} </p>
              @endif
            </div>
          </div> <!-- /.tab-pane -->
          <div class="tab-pane" id="listings_tab">
            <table class="table">
              <thead>
                <tr>
                  <th>{{ trans('app.vendor') }}</th>
                  <th>{{ trans('app.stock_quantity') }} </th>
                  <th>{{ trans('app.condition') }} </th>
                  <th>{{ trans('app.price') }} </th>
                  <th>{{ trans('app.options') }} </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($product->inventories as $listing)
                  <tr>
                    <td>
                      {{ $listing->shop->name ?? '' }}

                      @if ($listing->shop->isVerified())
                        <img src="{{ get_verified_badge() }}" class="verified-badge img-xs" data-toggle="tooltip" data-placement="top" title="{{ trans('help.verified_seller') }}" alt="verified-badge">
                      @endif

                      @if ($listing->shop->isDown())
                        <span class="label label-default indent10">{{ trans('app.maintenance_mode') }}</span>
                      @endif
                    </td>
                    <td>
                      {{ $listing->stock_quantity > 0 ? $listing->stock_quantity : trans('app.out_of_stock') }}
                    </td>
                    <td>
                      {{ $listing->condition }}
                      <small class="text-muted" data-toggle="tooltip" data-placement="top" title="{{ $listing->condition_note }}"><sup><i class="fa fa-question-circle"></i></sup></small>
                    </td>
                    <td>
                      @if ($listing->hasOffer())
                        @php
                          $offer_price_help = trans('help.offer_starting_time') . ': ' . $listing->offer_start->diffForHumans() . ' ' . trans('app.and') . ' ' . trans('help.offer_ending_time') . ': ' . $listing->offer_end->diffForHumans();
                        @endphp

                        <del class="text-muted">{{ get_formated_currency($listing->sale_price, 2) }}</del>
                        <small class="text-muted" data-toggle="tooltip" data-placement="top" title="{{ $offer_price_help }}"><sup><i class="fa fa-question-circle"></i></sup></small><br />
                      @endif
                      {{ get_formated_currency($listing->current_sale_price(), 2) }}
                    </td>
                    <td>
                      <a href="{{ route('show.product', $listing->slug) }}" target="_blank">
                        {{ trans('app.view_detail') }}
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> <!-- /.tab-pane -->
          <div class="tab-pane" id="seo_tab">
            <table class="table">
              @if ($product->slug)
                <tr>
                  <th>{{ trans('app.slug') }}: </th>
                  <td>{{ $product->slug }}</td>
                </tr>
              @endif
              @if ($product->meta_title)
                <tr>
                  <th>{{ trans('app.meta_title') }}: </th>
                  <td>{{ $product->meta_title }}</td>
                </tr>
              @endif
              @if ($product->meta_description)
                <tr>
                  <th>{{ trans('app.meta_description') }}: </th>
                  <td>{{ $product->meta_description }}</td>
                </tr>
              @endif
              @if ($product->meta_keywords)
                <tr>
                  <th>{{ trans('app.meta_keywords') }}: </th>
                  <td>{{ $product->meta_keywords }}</td>
                </tr>
              @endif
              @if ($product->tags)
                <tr>
                  <th>{{ trans('app.tags') }}: </th>
                  <td>
                    @foreach ($product->tags as $tag)
                      <span class="label label-outline">{{ $tag->name }}</span>
                    @endforeach
                  </td>
                </tr>
              @endif
            </table>
          </div> <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
    </div>
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
