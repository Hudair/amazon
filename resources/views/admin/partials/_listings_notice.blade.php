@if(Auth::user()->shop->isDown())

    @unless(Request::is('admin/setting/general*'))
      <div class="alert alert-error alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
          {!! trans('messages.listings_not_visible', ['reason' => trans('messages.youe_shop_in_maintenance_mode')]) !!}
          @if(Auth::user()->isMerchant())
            <span class="pull-right">
                <a href="{{ route('admin.setting.config.general') }}" class="btn bg-navy"><i class="fa fa-rocket"></i>  {{ trans('app.take_action') }}</a>
            </span>
          @endif
      </div>
    @endunless

@elseif(! Auth::user()->shop->active)

    <div class="alert alert-error alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
        {!! trans('messages.your_shop_in_hold') !!}
    </div>

@elseif(! Auth::user()->shop->hasPaymentMethods() && vendor_get_paid_directly())

    @unless(Request::is('admin/setting/paymentMethod*'))
      <div class="alert alert-error alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
          {!! trans('messages.listings_not_visible', ['reason' => trans('messages.no_active_payment_method')]) !!}
          @if(Auth::user()->isMerchant())
            <span class="pull-right">
                <a href="{{ route('admin.setting.config.paymentMethod.index') }}" class="btn bg-navy"><i class="fa fa-rocket"></i>  {{ trans('app.take_action') }}</a>
            </span>
          @endif
      </div>
    @endunless

@elseif(! Auth::user()->shop->hasAddress())

    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
        {!! trans('messages.no_address_for_invoice') !!}
        @if(Auth::user()->isMerchant() && ! Request::is('admin/setting/general*'))
          <span class="pull-right">
              <a href="{{ route('admin.setting.config.general') }}" class="btn bg-navy"><i class="fa fa-rocket"></i>  {{ trans('app.take_action') }}</a>
          </span>
        @endif
    </div>

@elseif(! Auth::user()->shop->hasShippingZones())

    @unless(Request::is('admin/shipping/shippingZone*'))
      <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
          {!! trans('messages.no_active_shipping_zone') !!}
          @if(Auth::user()->isMerchant())
            <span class="pull-right">
                <a href="{{ route('admin.shipping.shippingZone.index') }}" class="btn bg-navy"><i class="fa fa-rocket"></i>  {{ trans('app.take_action') }}</a>
            </span>
          @endif
      </div>
    @endunless

@endif