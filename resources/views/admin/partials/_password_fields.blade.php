<div class="form-group">
    {!! Form::label('password', trans('app.form.new_password').'*') !!}
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