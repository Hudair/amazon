<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('title', trans('app.form.title'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_title') }}"></i>
      {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.title')]) !!}
      <div class="help-block with-errors"><small class="text-info"><i class="fa fa-info-circle"></i> {{ trans('help.you_can_use_span_tag') }}</small></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('title_color', trans('app.form.text_color'), ['class' => 'with-help']) !!}
      <div class="input-group my-colorpicker2 colorpicker-element">
        {!! Form::text('title_color', isset($slider) ? null : '#333333', ['class' => 'form-control', 'placeholder' => trans('app.placeholder.color')]) !!}
        <div class="input-group-addon">
          <i style="background-color: rgb(51, 51, 51);"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('sub_title', trans('app.form.sub_title'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_sub_title') }}"></i>
      {!! Form::text('sub_title', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.sub_title')]) !!}
      <div class="help-block with-errors"><small class="text-info"><i class="fa fa-info-circle"></i> {{ trans('help.you_can_use_span_tag') }}</small></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('sub_title_color', trans('app.form.text_color'), ['class' => 'with-help']) !!}
      <div class="input-group my-colorpicker2 colorpicker-element">
        {!! Form::text('sub_title_color', isset($slider) ? null : '#b5b5b5', ['class' => 'form-control', 'placeholder' => trans('app.placeholder.color')]) !!}
        <div class="input-group-addon">
          <i style="background-color: rgb(181, 181, 181);"></i>
        </div>
      </div>
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('description', trans('app.form.description'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_description') }}"></i>
      {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.description')]) !!}
      <div class="help-block with-errors"><small class="text-info"><i class="fa fa-info-circle"></i> {{ trans('help.you_can_use_span_tag') }}</small></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('description_color', trans('app.form.description_color'), ['class' => 'with-help']) !!}
      <div class="input-group my-colorpicker2 colorpicker-element">
        {!! Form::text('description_color', isset($slider) ? null : '#b5b5b5', ['class' => 'form-control', 'placeholder' => trans('app.placeholder.color')]) !!}
        <div class="input-group-addon">
          <i style="background-color: rgb(255,140,0);"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('link', trans('app.form.link'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_link') }}"></i>
      {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.link')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('alt_color', trans('app.form.alternative_color'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_alternative_color') }}"></i>
      <div class="input-group my-colorpicker2 colorpicker-element">
        {!! Form::text('alt_color', isset($slider) ? null : '#FED700', ['class' => 'form-control', 'placeholder' => trans('app.placeholder.color')]) !!}
        <div class="input-group-addon">
          <i style="background-color: rgb(255,140,0);"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('order', trans('app.form.position'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_order') }}"></i>
      {!! Form::number('order', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.position')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('text_position', trans('app.text_position'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_text_position') }}"></i>
      {!! Form::select('text_position', ['right' => trans('app.right'), 'left' => trans('app.left')], isset($slider) ? null : 'right', ['class' => 'form-control']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="exampleInputFile" class="with-help"> {{ trans('app.slider_image') . '*' }}</label>
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slider_image') }}"></i>
      @if (isset($slider) && Storage::exists(optional($slider->featureImage)->path))
        <div>
          <img src="{{ get_storage_file_url(optional($slider->featureImage)->path, 'medium') }}" width="50%" alt="{{ trans('app.slider_image') }}">
          <span class="indent10">
            {!! Form::checkbox('delete_image[feature]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
          </span>
        </div>
        <div class="spacer5"></div>
      @endif

      <div class="row">
        <div class="col-md-9 nopadding-right">
          <input id="uploadFile" placeholder="{{ trans('app.slider_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
        </div>
        <div class="col-md-3 nopadding-left">
          <div class="fileUpload btn btn-primary btn-block btn-flat">
            <span>{{ trans('app.form.select') }}</span>
            <input type="file" name="images[feature]" id="uploadBtn" class="upload" {{ isset($slider) ? '' : 'required' }} />
          </div>
        </div>
      </div>
      <div class="help-block with-errors">{{ trans('help.slider_img_hint') }}</div>
    </div>
  </div>

  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      <label for="thumb" class="with-help"> {{ trans('app.mobile_slider') }}</label>
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.mobile_slider_image') }}"></i>
      @if (isset($slider) && Storage::exists(optional($slider->mobileImage)->path))
        <div class="mb-2">
          <img src="{{ get_storage_file_url(optional($slider->mobileImage)->path, 'medium') }}" width="50%" alt="{{ trans('app.slider_image') }}">
          <span class="indent10">
            {!! Form::checkbox('delete_image[mobile]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
          </span>
        </div>
      @endif
      <span class="spacer5"></span>
      <input type="file" name="images[mobile]" style="display: inline-block;" />
      <div class="help-block with-errors">{{ trans('help.mobile_app_slider_hits') }}</div>
    </div>
  </div>
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>
