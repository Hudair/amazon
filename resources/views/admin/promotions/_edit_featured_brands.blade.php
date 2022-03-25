<div class="modal-dialog modal-md">
  <div class="modal-content">
    {!! Form::open(['route' => 'admin.update.featuredBrands', 'method' => 'PUT', 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.form.featured_brands') }}
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('featured_brands[]', trans('app.form.brands')) !!}
        {!! Form::select('featured_brands[]', $brands, array_keys($featured_brands), ['class' => 'form-control select2-normal', 'multiple' => 'multiple']) !!}
        <div class="help-block with-errors"></div>
      </div>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
