<div class="row">
  <div class="col-12">
    <div class="my-info-container">
      <div class="my-info-box">
        <div class="me-info-block">
          <div class="my-photo-block">
            <img src="{{ get_storage_file_url(optional($dashboard->image)->path, 'thumbnail') }}" class="center-block" alt="{{ trans('theme.avatar') }}" />
          </div>
          <div class="my-info">
            <div class="name">
              <span>
                {{ $dashboard->getName() }}
              </span>
              <a href="{{ route('account', 'account') }}" class="small indent10"><i class="fas fa-edit" data-toggle="tooltip" data-title="{{ trans('theme.edit_account') }}"></i></a>
            </div>
            <div class="messages">
              <span>
                <i class="fas fa-clock-o"></i>
                {{ trans('theme.member_since') }}: <em>{{ $dashboard->created_at->diffForHumans() }}</em>
              </span>
            </div>
          </div>

          <div class="pull-right">
            <a href="{{ url('/') }}" class="btn btn-primary flat">
              <i class="fas fa-shopping-cart"></i> @lang('theme.button.continue_shopping')
            </a>

            @unless($dashboard->shippingAddress)
              <a href="{{ route('account', 'account') }}#address-tab" class="btn btn-default flat">
                <i class="fas fa-truck"></i> @lang('theme.add_shipping_address')
              </a>
            @endunless
          </div>
        </div>
      </div><!-- .my-info-box -->

      <div class="my-info-details">
        <ul>
          <li>
            <a href="{{ route('account', 'orders') }}">
              <span class="v">{{ $dashboard->orders_count }}</span>
              <span class="d"><i class="fas fa-shopping-cart"></i> @lang('theme.orders')</span>
            </a>
          </li>
          <li>
            <a href="{{ route('account', 'wishlist') }}">
              <span class="v">{{ $dashboard->wishlists_count }}</span>
              <span class="d"><i class="fas fa-heart"></i> @lang('theme.wishlist')</span>
            </a>
          </li>
          <li>
            <a href="{{ route('account', 'messages') }}">
              <span class="v">{{ $dashboard->messages_count }}</span>
              <span class="d"><i class="fas fa-envelope"></i> @lang('theme.unread_messages')</span>
            </a>
          </li>
          <li>
            <a href="{{ route('account', 'coupons') }}">
              <span class="v">{{ $dashboard->coupons_count }}</span>
              <span class="d"><i class="fas fa-tags"></i> @lang('theme.coupons')</span>
            </a>
          </li>
          <li class="last">
            <a href="{{ route('account', 'disputes') }}">
              <span class="v">{{ $dashboard->disputes_count }}</span>
              <span class="d"><i class="fas fa-envelope"></i> @lang('theme.disputes')</span>
            </a>
          </li>
        </ul>
      </div><!-- .my-info-details -->
    </div><!-- .my-info-container -->
  </div><!-- .col-sm-12 -->
</div><!-- .row -->

<div class="row">
  <div class="col-md-6 nopadding-right">
    <table class="table table-bordered">
      <thead>
        <tr class="text-muted">
          <th>{{ trans('theme.date') }}</th>
          <th>
            {{ trans('theme.orders') }}
            <i class="fas fa-question-circle pull-right" data-toggle="tooltip" data-title="{{ trans('theme.item_count') }}"></i>
          </th>
          <th>{{ trans('theme.amount') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dashboard->orders as $order)
          <tr>
            <td>{!! $order->created_at->format('M j') !!}</td>
            <td>
              <img src="{{ get_storage_file_url(optional($order->shop->logoImage)->path, 'tiny_thumb') }}" class="img-circle" alt="{{ $order->shop->name }}" data-toggle="tooltip" data-title="{{ $order->shop->name }}">
              <a href="{{ route('order.detail', $order) }}">
                {!! $order->order_number !!}
              </a>
              <small class="indent10">{!! $order->orderStatus() !!}</small>
              <span class="label label-outline pull-right"> {{ $order->item_count }} </span>
            </td>
            <td>{!! get_formated_price($order->grand_total, 2) !!}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div><!-- .col-sm-6 -->
  <div class="col-md-6 nopadding-left">
    <table class="table table-bordered">
      <thead>
        <tr class="text-muted">
          <th>{{ trans('theme.wishlist') }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dashboard->wishlists as $wish)
          @if ($wish->inventory)
            <tr>
              <td>
                <img class="" src="{{ get_product_img_src($wish->inventory, 'tiny') }}" alt="{!! $wish->inventory->title !!}" title="{!! $wish->inventory->title !!}" />

                <a class="product-link" href="{{ route('show.product', $wish->inventory->slug) }}">{!! \Illuminate\Support\Str::limit($wish->inventory->title, 35) !!}</a>
              </td>
              <td>
                <a class="btn btn-primary btn-xs flat" href="{{ route('direct.checkout', $wish->inventory->slug) }}">
                  <i class="fas fa-rocket"></i> @lang('theme.button.buy_now')
                </a>
              </td>
            </tr>

          @elseif($wish->product)

            <tr>
              <td>
                <img src="{{ get_storage_file_url(optional($wish->product->featuredImage)->path, 'tiny') }}" alt="{!! $wish->product->name !!}" title="{!! $wish->product->name !!}" />

                <a class="product-link" href="{{ route('show.offers', $wish->product->slug) }}" class="btn btn-sm btn-link">{!! \Illuminate\Support\Str::limit($wish->product->name, 35) !!}</a>
              </td>
              <td>
                <a class="btn btn-primary btn-xs flat" href="{{ route('show.offers', $wish->product->slug) }}">
                  @lang('theme.view_more_offers', ['count' => $wish->product->inventories_count])
                </a>
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div><!-- .col-sm-6 -->
</div><!-- .row -->
<div class="clearfix space50"></div>
