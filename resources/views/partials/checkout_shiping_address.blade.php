@if (isset($one_checkout_form))
  @include('partials.address_form')
@else
  @include('partials.address_form', ['countries' => $business_areas->pluck('name', 'id')])
@endif

<div class="form-group">
  {!! Form::email('email', null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.email'), 'maxlength' => '100', 'autocomplete' => 'new-customer-email', 'required']) !!}
  <div class="help-block with-errors"></div>
</div>

<div class="checkbox">
  <label>
    {!! Form::checkbox('create-account', null, null, ['id' => 'create-account-checkbox', 'class' => 'i-check']) !!} {!! trans('theme.create_account') !!}
  </label>
</div>

<div id="create-account" class="space30" style="display: none;">
  <div class="row">
    <div class="col-md-6 nopadding-right">
      <div class="form-group">
        {!! Form::password('password', ['class' => 'form-control flat', 'id' => 'acc-password', 'placeholder' => trans('theme.placeholder.password'), 'autocomplete' => 'new-customer-password', 'data-minlength' => '6']) !!}
        <div class="help-block with-errors"></div>
      </div>
    </div>

    <div class="col-md-6 nopadding-left">
      <div class="form-group">
        {!! Form::password('password_confirmation', ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.confirm_password'), 'autocomplete' => 'new-customer-password', 'data-match' => '#acc-password']) !!}
        <div class="help-block with-errors"></div>
      </div>
    </div>
  </div>

  @if (config('system_settings.ask_customer_for_email_subscription'))
    <div class="checkbox">
      <label>
        {!! Form::checkbox('accepts_marketing', null, null, ['class' => 'i-check']) !!} {!! trans('theme.input_label.subscribe_to_the_newsletter') !!}
      </label>
    </div>
  @endif

  <p class="text-info small">
    <i class="fas fa-info-circle"></i>
    {!! trans('theme.help.create_account_on_checkout', ['link' => get_page_url(\App\Models\Page::PAGE_TNC_FOR_CUSTOMER)]) !!}
  </p>
</div>

{{-- <small class="help-block text-muted pull-right">* {{ trans('theme.help.required_fields') }}</small> --}}
