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
      {!! Form::label('priority', trans('app.form.priority'), ['class' => 'with-help']) !!}
      {!! Form::number('priority', null, ['class' => 'form-control', 'maxlength' => 3, 'placeholder' => trans('app.placeholder.position')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('iso_code', trans('app.form.iso_code').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_iso_code') }}"></i>
      {!! Form::text('iso_code', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.iso_code'), 'required']) !!}
      <div class="help-block with-errors"><small>https://en.wikipedia.org/wiki/ISO_4217 <a href="https://en.wikipedia.org/wiki/ISO_4217" class="indent5" target="_blank"> <i class="fa fa-external-link"></i></a></small></div>
    </div>
  </div>

  <div class="col-sm-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('iso_numeric', trans('app.iso_numeric').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{!! trans('help.currency_iso_numeric') !!}"></i>
      {!! Form::number('iso_numeric' , null, ['class' => 'form-control', 'size' => '3', 'placeholder' => trans('app.iso_numeric'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('thousands_separator', trans('app.form.thousands_separator').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.currency_thousands_separator') }}"></i>
      {!! Form::select('thousands_separator', [',' => ',', '.' => '.', ' ' => 'Space(&nbsp;)'], Null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.select'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-sm-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('decimal_mark', trans('app.form.decimal_mark').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_decimalpoint') }}"></i>
      {!! Form::select('decimal_mark', [',' => ',', '.' => '.'], Null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.select'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      <div class="input-group">
        {{ Form::hidden('symbol_first', 0) }}
        {!! Form::checkbox('symbol_first', null, null, ['id' => 'symbol_first', 'class' => 'icheckbox_line']) !!}
        {!! Form::label('symbol_first', trans('app.symbol_first')) !!}
        <span class="input-group-addon" id="">
          <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_symbol_first') }}"></i>
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
          <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_active') }}"></i>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-4 nopadding-right">
    <div class="form-group">
      {!! Form::label('symbol', trans('app.form.symbol').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_symbol') }}"></i>
      {!! Form::text('symbol', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.symbol'), 'required']) !!}
      <div class="help-block with-errors"><small>https://en.wikipedia.org/wiki/Currency_symbol</small></div>
    </div>
  </div>

  <div class="col-sm-4 nopadding">
    <div class="form-group">
      {!! Form::label('disambiguate_symbol', trans('app.disambiguate_symbol'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_disambiguate_symbol') }}"></i>
      {!! Form::text('disambiguate_symbol', null, ['class' => 'form-control', 'placeholder' => trans('app.disambiguate_symbol')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-sm-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('html_entity', trans('app.html_entity'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{!! trans('help.currency_html_entity') !!}"></i>
      {!! Form::text('html_entity', null, ['class' => 'form-control', 'placeholder' => trans('app.html_entity')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-4 nopadding-right">
    <div class="form-group">
      {!! Form::label('subunit', trans('app.form.subunit').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_subunit') }}"></i>
      {!! Form::text('subunit', null, ['class' => 'form-control', 'placeholder' => trans('app.subunit'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-sm-4 nopadding">
    <div class="form-group">
      {!! Form::label('smallest_denomination', trans('app.smallest_denomination').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_smallest_denomination') }}"></i>
      {!! Form::number('smallest_denomination', isset($currency) ? $currency->smallest_denomination : 1, ['class' => 'form-control', 'placeholder' => trans('app.smallest_denomination'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-sm-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('subunit_to_unit', trans('app.subunit_to_unit').'*', ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.currency_subunit_to_unit') }}"></i>
      {!! Form::number('subunit_to_unit', isset($currency) ? $currency->subunit_to_unit : 100, ['class' => 'form-control', 'placeholder' => trans('app.subunit_to_unit'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>