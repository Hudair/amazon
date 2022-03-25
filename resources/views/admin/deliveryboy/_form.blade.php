<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('name', trans('app.first_name') . '*') !!}
      {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => trans('app.first_name'), 'required']) !!}
      <input type="hidden" name="shop_id" value="{{ auth()->user()->merchantId() }}">
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('name', trans('app.last_name') . '*') !!}
      {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => trans('app.last_name'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('nice_name', trans('app.form.nice_name')) !!}
      {!! Form::text('nice_name', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.nice_name')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('active', trans('app.form.status') . '*') !!}
      {!! Form::select('status', ['1' => trans('app.active'), '0' => trans('app.inactive')], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('email', trans('app.form.email_address') . '*') !!}
      {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.valid_email'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('phone_number', 'Phone Number' . '*') !!}
      {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => trans('app.phone'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

@if (!isset($deliveryboy))
  <div class="form-group">
    {!! Form::label('password', trans('app.form.password') . '*') !!}
    <div class="row">
      <div class="col-md-6 nopadding-right">
        {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder' => trans('app.placeholder.password'), 'data-minlength' => '6', 'required']) !!}
        <div class="help-block with-errors"></div>
      </div>
      <div class="col-md-6 nopadding-left">
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('app.placeholder.confirm_password'), 'data-match' => '#password', 'required']) !!}
        <div class="help-block with-errors"></div>
      </div>
    </div>
  </div>
@endif

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('sex', trans('app.form.sex')) !!}
      {!! Form::select('sex', ['app.male' => trans('app.male'), 'app.female' => trans('app.female'), 'app.other' => trans('app.other')], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.sex')]) !!}
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('dob', trans('app.form.dob')) !!}
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        {!! Form::text('dob', null, ['class' => 'form-control datepicker', 'placeholder' => trans('app.placeholder.dob')]) !!}
      </div>
    </div>
  </div>
</div>

{{-- @unless(isset($deliveryboy))
    @include('address._form')
  @endunless --}}

<div class="form-group">
  {{-- <label for="exampleInputFile">{{ trans('app.form.avatar') }}</label>
    @if (!isset($deliveryboy) && !$deliveryboy->avatar)
      <label>
        <img src="{{ get_storage_file_url($deliveryboy->avatar->path, 'small') }}" alt="{{ trans('app.avatar') }}">
        <span style="margin-left: 10px;">
          {!! Form::checkbox('delete_image', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_avatar') }}
        </span>
      </label>
    @endif --}}

  <div class="row">
    <div class="col-md-9 nopadding-right">
      <input id="uploadFile" placeholder="{{ trans('app.placeholder.avatar') }}" class="form-control" disabled="disabled" style="height: 28px;" />
    </div>
    <div class="col-md-3 nopadding-left">
      <div class="fileUpload btn btn-primary btn-block btn-flat">
        <span>{{ trans('app.form.upload') }}</span>
        <input type="file" name="image" id="uploadBtn" class="upload" />
      </div>
    </div>
  </div>
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>
