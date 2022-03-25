<div class="row">
  <div class="col-md-12">
    @include('admin.partials._subscription_notice')

    <!-- Error Message -->
    @if (Session::has('error'))
      <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
  </div>

  <div class="col-md-8 col-md-offset-2">
    @if (Auth::user()->hasExpiredPlan())
      <div class="alert alert-danger">
        <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
        {{ trans('messages.subscription_expired') }}
      </div>
    @endif

    @unless(Auth::user()->isSubscribed())
      <div class="alert alert-info">
        <i class="icon fa fa-rocket"></i>{{ trans('messages.choose_subscription') }}
      </div>
    @endunless

    <div class="panel panel-default">
      <div class="panel-body">
        <fieldset>
          <legend>{{ trans('app.subscription_plans') }}</legend>
          <table class="table no-border">
            <tbody>
              @foreach ($plans as $plan)
                <tr>
                  <td>
                    <span class="lead">{{ $plan->name }}</span>
                    @if (optional($current_plan)->stripe_price == $plan->plan_id)
                      <i class="fa fa-dot-circle-o text-primary indent5" data-toggle="tooltip" title="{{ trans('app.current_plan') }}"></i>
                    @endif
                  </td>
                  <td>
                    <a href="javascript:void(0)" data-link="{{ route('admin.account.subscription.features', $plan->plan_id) }}" class="ajax-modal-btn btn btn-link">
                      <i class="fa fa-star-o"></i> {{ trans('app.features') }}
                    </a>
                  </td>

                  <td class="lead">
                    {{ get_formated_currency($plan->cost) . trans('app.per_month') }}
                  </td>

                  @if (\Auth::user()->isMerchant())
                    <td class="pull-right">
                      @if (optional($current_plan)->stripe_price == $plan->plan_id)
                        @if (Auth::user()->isOnGracePeriod())
                          <a href="{{ route('admin.account.subscription.resume') }}" class="confirm btn btn-lg btn-primary">
                            <i class="fa fa-play"></i> {{ trans('app.resume_subscription') }}
                          </a>
                        @elseif($current_plan->provider == 'stripe')
                          {!! Form::open(['route' => ['admin.account.subscription.cancel', $current_plan], 'method' => 'delete', 'class' => 'inline']) !!}
                          <button type="submit" class="confirm ajax-silent btn btn-lg btn-danger">
                            <i class="fa fa-times-circle-o"></i> {{ trans('app.cancel') }}
                          </button>
                          {!! Form::close() !!}
                        @else
                          <button class="btn btn-lg btn-new disabled">
                            <i class="fa fa-check-circle-o"></i> {{ trans('app.current_plan') }}
                          </button>
                        @endif
                      @else
                        <a href="{{ route('admin.account.subscribe', $plan->plan_id) }}" class="confirm btn btn-lg btn-default">
                          <i class="fa fa-leaf"></i> {{ trans('app.select_this_plan') }}
                        </a>
                      @endif
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
          @if ((bool) config('system_settings.trial_days'))
            <span class="spacer10"></span>
            <span class="text-info">
              <i class="icon fa fa-info-circle"></i>
              {!! trans('messages.plan_comes_with_trial', ['days' => config('system_settings.trial_days')]) !!}
            </span>
          @endif
        </fieldset>
      </div>
    </div>

    @if (Auth::user()->isMerchant())
      {{-- @if (config('system.subscription.billing') == 'stripe') --}}
      @unless(\App\Models\SystemConfig::isBillingThroughWallet())
        <div class="alert alert-info">
          <strong><i class="icon fa fa-credit-card"></i></strong>
          {{ trans('messages.no_billing_info') }}
        </div>

        @if (\App\Models\SystemConfig::isPaymentConfigured('stripe'))
          <div class="panel panel-default">
            <div class="panel-body">
              {!! Form::model($profile, ['method' => 'PUT', 'route' => ['admin.account.card.update'], 'id' => 'stripe-form', 'data-toggle' => 'validator']) !!}

              @include('auth.stripe_form')

              <div class="pull-right">
                {!! Form::submit(trans('app.update'), ['class' => 'btn btn-lg btn-new', 'id' => 'card-button', 'data-secret' => $intent->client_secret]) !!}
              </div>
              {!! Form::close() !!}
            </div>
          </div>
        @endif
      @endunless

      <div class="panel panel-default">
        <div class="panel-body">
          <fieldset>
            <legend>{{ trans('app.invoices') }} <i class="fa fa-files"></i> </legend>
            @include('admin.account._invoices', ['billable' => Auth::user()->shop])
          </fieldset>
        </div>
      </div>
    @else
      <div class="alert alert-danger">
        <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
        {{ trans('messages.only_merchant_can_change_plan') }}
      </div>
    @endif

    <fieldset>
      <legend>{{ trans('app.history') }} <i class="fa fa-history"></i> </legend>
      @include('admin.account._activity_logs', ['logger' => Auth::user()->shop])
    </fieldset>
  </div>
</div>
