{{-- Stripe --}}
<div id="cc-form" class="cc-form" style="display: none;">
  <hr class="style4 muted">
  <div class="stripe-errors alert alert-danger flat small hide">{{ trans('messages.trouble_validating_card') }}</div>
  <div class="form-group form-group-cc-name">
    {!! Form::text('name', null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.cardholder_name'), 'data-error' => trans('theme.help.enter_cardholder_name'), 'data-stripe' => 'name']) !!}
    <div class="help-block with-errors"></div>
  </div>
  <div class="form-group form-group-cc-number">
    <input type="text" class='form-control flat' placeholder="@lang('theme.placeholder.card_number')" data-stripe='number' />
    <div class="help-block with-errors"></div>
  </div>
  <div class="form-group form-group-cc-cvc">
    <input type="text" class='form-control flat' placeholder="@lang('theme.placeholder.card_cvc')" data-stripe='cvc' />
    <div class="help-block with-errors"></div>
  </div>

  <div class="row">
    <div class="col-md-6 nopadding-right">
      <div class="form-group has-feedback">
        {{ Form::selectMonth('exp-month', null, ['id' => 'exp-month', 'class' => 'form-control flat', 'data-stripe' => 'exp-month', 'placeholder' => trans('theme.placeholder.card_exp_month'), 'data-error' => trans('theme.help.card_exp_month')], '%m') }}
        <div class="help-block with-errors"></div>
      </div>
    </div>

    <div class="col-md-6 nopadding-left">
      <div class="form-group has-feedback">
        {{ Form::selectYear('exp-year', date('Y'), date('Y') + 10, null, ['id' => 'exp-year', 'class' => 'form-control flat', 'data-stripe' => 'exp-year', 'placeholder' => trans('theme.placeholder.card_exp_year'), 'data-error' => trans('theme.help.card_exp_year')]) }}
        <div class="help-block with-errors"></div>
      </div>
    </div>
  </div>

  <div class="checkbox">
    <label>
      {!! Form::checkbox('remember_the_card', null, null, ['id' => 'remember-the-card', 'class' => 'i-check']) !!} {!! trans('theme.remember_card_for_future_use') !!}
    </label>
  </div>
</div> <!-- /#cc-form -->
