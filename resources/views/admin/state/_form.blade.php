<div class="row">
  <div class="col-sm-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('name', trans('app.form.name').'*', ['class' => 'with-help']) !!}
      {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.name'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-sm-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('active', trans('app.form.status') . '*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle pull-right" data-toggle="tooltip" data-placement="left" title="{{ trans('help.active_business_zone') }}"></i>
      {!! Form::select('active', ['1' => trans('app.active'), '0' => trans('app.inactive')], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('iso_code', trans('app.iso_code').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.region_iso_code') }}"></i>
      {!! Form::text('iso_code', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.iso_code'), isset($state) && $state->iso_code ? 'disabled' : 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-sm-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('calling_code', trans('app.calling_code'), ['class' => 'with-help']) !!}
      {!! Form::text('calling_code', null, ['class' => 'form-control', 'placeholder' => trans('app.calling_code')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>