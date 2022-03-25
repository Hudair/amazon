<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('name', trans('app.form.category_name').'*', ['class' => 'with-help']) !!}
      {!! Form::text('name', null, ['class' => 'form-control makeSlug', 'placeholder' => trans('app.placeholder.category_name'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('order', trans('app.form.position'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.display_order') }}"></i>
      {!! Form::text('order', isset($categoryGroup) ? null : 99, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.position')]) !!}
      {{-- {!! Form::number('order', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.position')]) !!} --}}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4 nopadding-right">
    <div class="form-group">
      {!! Form::label('slug', trans('app.form.slug').'*') !!}
      {!! Form::text('slug', null, ['class' => 'form-control slug', 'placeholder' => trans('app.placeholder.slug'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>

	<div class="col-md-4 nopadding-left nopadding-right">
		<div class="form-group">
		  {!! Form::label('icon', trans('app.form.icon')) !!}
			<div class="input-group">
				{!! Form::text('icon', isset($categoryGroup) ? null : 'fa-cube', ['class' => 'form-control iconpicker-input', 'placeholder' => trans('app.placeholder.icon'), 'data-placement' => 'bottomRight']) !!}
        <span class="input-group-addon"><i class="fa fa-cube"></i></span>
      </div>
		  <div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="col-md-4 nopadding-left">
		<div class="form-group">
		  {!! Form::label('active', trans('app.form.status').'*') !!}
		  {!! Form::select('active', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status'), 'required']) !!}
		  <div class="help-block with-errors"></div>
		</div>
	</div>
</div>

<div class="form-group">
  {!! Form::label('description', trans('app.form.description'), ['class' => 'with-help']) !!}
  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.cat_grp_desc') }}"></i>
  {!! Form::textarea('description', null, ['class' => 'form-control summernote-without-toolbar', 'placeholder' => trans('app.placeholder.description'), 'rows' => '2']) !!}
</div>

<div class="row">
  <div class="col-md-4 nopadding-right">
    <div class="form-group">
      {!! Form::label('exampleInputFile', trans('app.background_image'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.cat_grp_img') }}"></i>
      @if(isset($categoryGroup) && Storage::exists(optional($categoryGroup->backgroundImage)->path))
        <label>
          <img src="{{ get_storage_file_url(optional($categoryGroup->backgroundImage)->path, 'small') }}" width="" alt="{{ trans('app.background_image') }}">
          <span style="margin-left: 10px;">
            {!! Form::checkbox('delete_image[background]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
          </span>
        </label>
      @endif
      <div class="row">
        <div class="col-md-7 nopadding-right">
          <input id="uploadFile" placeholder="{{ trans('app.background_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
        </div>
        <div class="col-md-5 nopadding-left">
          <div class="fileUpload btn btn-primary btn-block btn-flat">
              <span>{{ trans('app.form.upload') }}</span>
              <input type="file" name="images[background]" id="uploadBtn" class="upload" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 nopadding-left nopadding-right">
    <div class="form-group">
      {!! Form::label('uploadFile1', trans('app.cover_image'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.cover_img',['page' => trans('app.categories')]) }}"></i>
      @if(isset($categoryGroup) && Storage::exists(optional($categoryGroup->coverImage)->path))
        <label>
          <img src="{{ get_storage_file_url(optional($categoryGroup->coverImage)->path, 'small') }}" width="" alt="{{ trans('app.cover_image') }}">
          <span style="margin-left: 10px;">
            {!! Form::checkbox('delete_image[cover]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
          </span>
        </label>
      @endif
      <div class="row">
        <div class="col-md-7 nopadding-right">
          <input id="uploadFile1" placeholder="{{ trans('app.cover_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
          <div class="help-block with-errors">{{ trans('help.cover_img_size') }}</div>
        </div>
        <div class="col-md-5 nopadding-left">
          <div class="fileUpload btn btn-primary btn-block btn-flat">
              <span>{{ trans('app.form.upload') }}</span>
              <input type="file" name="images[cover]" id="uploadBtn1" class="upload" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('uploadFile2', trans('app.icon_image'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.icon_image',['page' => trans('app.categories')]) }}"></i>
      @if(isset($categoryGroup) && Storage::exists(optional($categoryGroup->logoImage)->path))
        <label>
          <img src="{{ get_storage_file_url(optional($categoryGroup->logoImage)->path, 'small') }}" width="" alt="{{ trans('app.icon_image') }}">
          <span style="margin-left: 10px;">
            {!! Form::checkbox('delete_image[logo]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
          </span>
        </label>
      @endif
      <div class="row">
        <div class="col-md-7 nopadding-right">
          <input id="uploadFile2" placeholder="{{ trans('app.icon_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
          <div class="help-block with-errors">{{ trans('help.icon_size') }}</div>
        </div>
        <div class="col-md-5 nopadding-left">
          <div class="fileUpload btn btn-primary btn-block btn-flat">
              <span>{{ trans('app.form.upload') }}</span>
              <input type="file" name="images[logo]" id="uploadBtn2" class="upload" />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  {!! Form::label('meta_title', trans('app.form.meta_title'), ['class' => 'with-help']) !!}
  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.meta_title') }}"></i>
  {!! Form::text('meta_title', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.meta_title')]) !!}
</div>

<div class="form-group">
  {!! Form::label('meta_description', trans('app.form.meta_description'), ['class' => 'with-help']) !!}
  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.meta_description') }}"></i>
  {!! Form::textarea('meta_description', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.meta_description'), 'rows' => '1']) !!}
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>