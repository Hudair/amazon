@if (Auth::user()->isSubscribed() && !Auth::user()->shop->hide_trial_notice)
  @php
    $subscription = Auth::user()->getCurrentPlan();
  @endphp

  @if (Auth::user()->isOnTrial())
    <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
      {{ trans('messages.trial_ends_at', ['ends' => \Carbon\Carbon::now()->diffInDays($subscription->trial_ends_at)]) }}
    </div>
  @elseif(Auth::user()->isOnGracePeriod())
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
      {{ trans('messages.resume_subscription', ['ends' => \Carbon\Carbon::now()->diffInDays($subscription->ends_at)]) }}

      @if (Auth::user()->isMerchant())
        <span class="pull-right">
          <a href="{{ route('admin.account.subscription.resume') }}" class="confirm btn bg-navy"><i class="fa fa-rocket"></i> {{ trans('app.resume_subscription') }}</a>
        </span>
      @endif
    </div>
  @elseif($subscription && $subscription->provider == 'wallet' && $subscription->active())
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
      {!! trans('messages.next_billing_date', ['date' => $subscription->ends_at->toRfc7231String()]) !!}

      @if (Auth::user()->isMerchant())
        <span class="pull-right">
          <a href="{{ route('merchant.wallet.deposit.form') }}" class="btn btn-sm bg-navy">{{ trans('wallet::lang.deposit_fund') }}</a>
        </span>
      @endif
    </div>
  @elseif(Auth::user()->isOnGenericTrial())
    <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
      {{ trans('messages.generic_trial_ends_at', ['ends' => \Carbon\Carbon::now()->diffInDays(Auth::user()->shop->trial_ends_at)]) }}
      @unless(Request::is('admin/account/billing'))
        <span class="pull-right">
          <a href="{{ route('admin.account.billing') }}" class="btn bg-navy"><i class="fa fa-rocket"></i> {{ trans('app.choose_plan') }}</a>
        </span>
      @endunless
    </div>
  @endif
@elseif(Auth::user()->hasExpiredPlan())
  <div class="alert alert-danger">
    <i class="icon fa fa-info-circle"></i><strong>{{ trans('app.notice') }}</strong>
    {{ trans('messages.trial_expired') }}
    @unless(Request::is('admin/account/billing'))
      <span class="pull-right">
        <a href="{{ route('admin.account.billing') }}" class="btn bg-navy"><i class="fa fa-rocket"></i> {{ trans('app.choose_plan') }}</a>
      </span>
    @endunless
  </div>
@endif
