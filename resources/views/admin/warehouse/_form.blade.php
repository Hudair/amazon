<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('name', trans('app.form.name') . '*') !!}
      {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.warehouse_name'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-4 nopadding-left">
    <div class="form-group">
      {!! Form::label('active', trans('app.form.status').'*') !!}
      {!! Form::select('active', ['1' => trans('app.active'), '0' => trans('app.inactive')], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('email', trans('app.form.email_address') ) !!}
      {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.valid_email')]) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('incharge', trans('app.form.incharge')) !!}
      {!! Form::select('incharge', $staffs , null, ['class' => 'form-control select2', 'placeholder' => trans('app.placeholder.incharge')]) !!}
    </div>
  </div>
</div>

@unless(isset($warehouse))
  @include('address._form')
@endunless


<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('opening_time', trans('app.form.opening_time')) !!}
      {!! Form::text('opening_time', isset($warehouse) ? $warehouse->opening_time : null, ['class' => 'form-control timepicker', 'placeholder' => date('H:i a'), 'required']) !!}
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('close_time', trans('app.form.close_time')) !!}
      {!! Form::text('close_time', isset($warehouse) ? $warehouse->close_time : null, ['class' => 'form-control timepicker', 'placeholder' => date('H:i a'), 'required']) !!}
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      @php
        $businessDays = isset($warehouse) ? explode(',', $warehouse->business_days) : [];
      @endphp
      {!! Form::label('business_day', trans('app.form.business_days')) !!}
      {!! Form::select('business_day[]', ['Sat' => 'Saturday','Sun' => 'Sunday', 'Mon' => 'Monday', 'Tues' => 'Tuesday', 'Wed' => 'Wednesday', 'Thurs' => 'Thursday','Fri' => 'Friday'], array_values($businessDays), ['class' => 'form-control select2-normal', 'multiple'=>'multiple', 'required']) !!}
    </div>
  </div>
</div>

<div class="form-group">
  {!! Form::label('description', trans('app.form.description')) !!}
  {!! Form::textarea('description', null, ['class' => 'form-control summernote', 'placeholder' => trans('app.placeholder.description')]) !!}
</div>

<div class="form-group">
	<label for="exampleInputFile">{{ trans('app.form.logo') }}</label>
  @if(isset($warehouse) && $warehouse->image)
  <label>
    <img src="{{ get_storage_file_url($warehouse->image->path, 'small') }}" alt="{{ trans('app.image') }}">
    <span style="margin-left: 10px;">
      {!! Form::checkbox('delete_image', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
    </span>
  </label>
  @endif

  <div class="row">
    <div class="col-md-9 nopadding-right">
			<input id="uploadFile" placeholder="{{ trans('app.placeholder.logo') }}" class="form-control" disabled="disabled" style="height: 28px;" />
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