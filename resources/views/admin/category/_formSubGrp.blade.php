<div class="form-group">
  {!! Form::label('category_group_id', trans('app.form.category_group').'*') !!}
  {!! Form::select('category_group_id', $catGroups , null, ['class' => 'form-control select2', 'placeholder' => trans('app.placeholder.category_group'), 'required']) !!}
  <div class="help-block with-errors"></div>
</div>
<div class="form-group">
  {!! Form::label('name', trans('app.form.category_sub_grp_name').'*') !!}
  {!! Form::text('name', null, ['class' => 'form-control makeSlug', 'placeholder' => trans('app.placeholder.category_sub_grp_name'), 'required']) !!}
  <div class="help-block with-errors"></div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('active', trans('app.form.status').'*', ['class' => 'with-help']) !!}
      {!! Form::select('active', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('order', trans('app.form.position'), ['class' => 'with-help']) !!}
      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.display_order') }}"></i>
      {!! Form::number('order' , null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.position')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="form-group">
  {!! Form::label('slug', trans('app.form.slug').'*', ['class' => 'with-help']) !!}
  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.slug') }}"></i>
  {!! Form::text('slug', null, ['class' => 'form-control slug', 'placeholder' => trans('app.placeholder.slug'), 'required']) !!}
  <div class="help-block with-errors"></div>
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

<div class="form-group">
  {!! Form::label('exampleInputFile', trans('app.form.cover_img'), ['class' => 'with-help']) !!}
  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.cover_img', ['page' => trans('app.category')]) }}"></i>
  @if(isset($categorySubGroup) && Storage::exists(optional($categorySubGroup->image)->path))
    <img src="{{ get_storage_file_url(optional($categorySubGroup->image)->path, 'small') }}" width="" alt="{{ trans('app.cover_image') }}">
    <span style="margin-left: 10px;">
      {!! Form::checkbox('delete_image[cover]', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
    </span>
  @endif
  <div class="row">
      <div class="col-md-9 nopadding-right">
        <input id="uploadFile" placeholder="{{ trans('app.placeholder.category_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
        <div class="help-block with-errors">{{ trans('help.cover_img_size') }}</div>
      </div>
      <div class="col-md-3 nopadding-left">
        <div class="fileUpload btn btn-primary btn-block btn-flat">
            <span>{{ trans('app.form.upload') }} </span>
            <input type="file" name="images[cover]" id="uploadBtn" class="upload" />
        </div>
      </div>
    </div>
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>