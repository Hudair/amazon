<div class="form-group">
  {!! Form::label('reply', trans('theme.reply') . '*') !!}
  {!! Form::textarea('reply', null, ['class' => 'form-control', 'rows' => '4', 'placeholder' => trans('theme.placeholder.message'), 'required']) !!}
  <div class="help-block with-errors"></div>
</div>

<div class="form-group my-3">
  {!! Form::label('attachment', trans('theme.attachment')) !!}
  <input type="file" name="attachments[]" id="uploadBtn" class="upload" multiple />
  <div class="help-block with-errors"></div>
</div>
