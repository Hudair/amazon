<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon flat"><i class="fa fa-user"></i></span>
    {!! Form::text('address_title', Null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.address_title'), 'required']) !!}
  </div>
  <div class="help-block with-errors"></div>
</div>

<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::select('country_id', $countries , isset($address) ? Null : ( isset($cart) ? $cart->ship_to_country_id : config('system_settings.address_default_country') ), ['id' => 'address_country_id', 'class' => 'form-control flat', 'placeholder' => trans('theme.country'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::text('zip_code', Null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.zip_code'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="form-group">
  {!! Form::text('address_line_1', Null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.address_line_1'), 'required']) !!}
  <div class="help-block with-errors"></div>
</div>

<div class="form-group">
  {!! Form::text('address_line_2', Null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.address_line_2')]) !!}
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::text('city', Null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.city'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::select('state_id', isset($states) ? $states : [], isset($cart) ? $cart->ship_to_state_id : ( isset($address) ? Null : config('system_settings.address_default_state') ), ['id' => 'address_state_id', 'class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.state'), empty($states) ? '' : 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="form-group">
  {!! Form::text('phone', Null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.phone_number')]) !!}
</div>