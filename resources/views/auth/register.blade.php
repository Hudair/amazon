@extends('auth.master')

@section('content')
  <div class="box login-box-body">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.form.register') }}</h3>
    </div> <!-- /.box-header -->
    <div class="box-body">
      {!! Form::open(['route' => 'register', 'id' => config('system_settings.required_card_upfront') ? 'stripe-form' : 'registration-form', 'data-toggle' => 'validator']) !!}

      @if (is_subscription_enabled())
        <div class="form-group has-feedback">
          {{ Form::select('plan', $plans, isset($plan) ? $plan : null, ['id' => 'plans', 'class' => 'form-control input-lg', 'required']) }}
          <i class="glyphicon glyphicon-dashboard form-control-feedback"></i>
          <div class="help-block with-errors">
            @if ((bool) config('system_settings.trial_days'))
              {{ trans('help.charge_after_trial_days', ['days' => config('system_settings.trial_days')]) }}
            @endif
          </div>
        </div>
      @endif

      <div class="form-group has-feedback">
        {!! Form::email('email', null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.placeholder.valid_email'), 'required']) !!}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <div class="help-block with-errors"></div>
      </div>
      <div class="form-group has-feedback">
        {!! Form::password('password', ['class' => 'form-control input-lg', 'id' => 'password', 'placeholder' => trans('app.placeholder.password'), 'data-minlength' => '6', 'required']) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div class="help-block with-errors"></div>
      </div>
      <div class="form-group has-feedback">
        {!! Form::password('password_confirmation', ['class' => 'form-control input-lg', 'placeholder' => trans('app.placeholder.confirm_password'), 'data-match' => '#password', 'required']) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group has-feedback">
        {!! Form::text('phone', null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.placeholder.phone')]) !!}
        <i class="glyphicon glyphicon-phone form-control-feedback"></i>
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group has-feedback">
        {!! Form::text('shop_name', null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.placeholder.shop_name'), 'required']) !!}
        <i class="glyphicon glyphicon-equalizer form-control-feedback"></i>
        <div class="help-block with-errors"></div>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <div class="form-group">
            <label>
              {!! Form::checkbox('agree', null, null, ['class' => 'icheck', 'required']) !!} {!! trans('app.form.i_agree_with_merchant_terms', ['url' => route('page.open', \App\Models\Page::PAGE_TNC_FOR_MERCHANT)]) !!}
            </label>
            <div class="help-block with-errors"></div>
          </div>
        </div>

        <div class="col-xs-4">
          {!! Form::submit(trans('app.form.register'), ['id' => 'card-button', 'class' => 'btn btn-block btn-lg btn-flat btn-primary']) !!}
        </div>
      </div>
      {!! Form::close() !!}

      <a href="{{ route('login') }}" class="btn btn-link">{{ trans('app.form.merchant_login') }}</a>
    </div>
  </div>
  <!-- /.form-box -->
@endsection
