
@if(config('system.subscription.enabled'))

    @if(config('system.subscription.billing') == 'stripe' && ! \App\Models\SystemConfig::isPaymentConfigured('stripe'))

      <div class="alert alert-danger">
          <h4><i class="fa fa-exclamation-triangle"></i> {{ trans('app.misconfigured') . '!!!' }}</h4>
          {!! trans('messages.misconfigured_subscription_stripe') !!}
      </div>

    @elseif(config('system.subscription.billing') == 'wallet')

      @unless(is_incevio_package_loaded(['wallet','subscription']))
        <div class="alert alert-danger">
            <h4><i class="fa fa-exclamation-triangle"></i> {{ trans('app.misconfigured') . '!!!' }}</h4>
            {!! trans('messages.misconfigured_subscription_wallet') !!}
        </div>
      @endunless

    @endif
@endif

