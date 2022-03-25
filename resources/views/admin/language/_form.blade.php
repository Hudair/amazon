<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('language', trans('app.language').'*', ['class' => 'with-help']) !!}
      {!! Form::text('language', null, ['class' => 'form-control', 'placeholder' => trans('app.language'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('order', trans('app.form.position'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.language_order') }}"></i>
      {!! Form::number('order' , null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.position')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('code', trans('app.code').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.locale_code') }}"></i>
      {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => trans('app.code'), 'required']) !!}
      <div class="help-block with-errors">{!! trans('help.locale_code_exmaple') !!}</div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('php_locale_code', trans('app.php_locale_code').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.php_locale_code') }}"></i>
      {!! Form::text('php_locale_code', null, ['class' => 'form-control', 'placeholder' => trans('app.php_locale_code'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      <div class="input-group">
        {{ Form::hidden('rtl', 0) }}
        {!! Form::checkbox('rtl', null, null, ['id' => 'rtl', 'class' => 'icheckbox_line']) !!}
        {!! Form::label('rtl', trans('app.rtl')) !!}
        <span class="input-group-addon" id="">
          <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.rtl') }}"></i>
        </span>
      </div>
    </div>
  </div>

  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      <div class="input-group">
        {{ Form::hidden('active', 0) }}
        {!! Form::checkbox('active', null, null, ['id' => 'active', 'class' => 'icheckbox_line']) !!}
        {!! Form::label('active', trans('app.active')) !!}
        <span class="input-group-addon" id="">
          <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.locale_active') }}"></i>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-info">
    {{ trans('help.new_language_info') }}
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>