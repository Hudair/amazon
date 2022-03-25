<div class="form-group">
 	{!! Form::label('current_password', trans('app.form.current_password').'*' ) !!}
    {!! Form::password('current_password', ['class' => 'form-control', 'id' => 'current_password', 'placeholder' => trans('app.placeholder.current_password'), 'data-minlength' => '6', 'required']) !!}
  <div class="help-block with-errors"></div>
</div>

@include('admin.partials._password_fields')