<div class="modal-dialog modal-md">
  <div class="modal-content">
    {!! Form::open(['route' => 'admin.promotion.tagline.update', 'method' => 'PUT', 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.promotional_tagline') }}
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('text', trans('app.text')) !!}
        {!! Form::text('text', $tagline['text'] ?? null, ['class' => 'form-control', 'placeholder' => trans('app.tagline_text')]) !!}
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group">
        {!! Form::label('action_url', trans('app.form.action_url')) !!}
        {!! Form::text('action_url', $tagline['action_url'] ?? null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.action_url')]) !!}
        <div class="help-block with-errors"></div>
      </div>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
