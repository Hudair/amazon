<div class="modal-dialog modal-md">
  <div class="modal-content">
    {!! Form::open(['route' => 'admin.promotion.trendingNow.update', 'method' => 'PUT', 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.form.trending_now_categories') }}
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('trending_categories', trans('app.form.categories')) !!}
        {{-- <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.trending_now_category_help') }}"></i> --}}
        {!! Form::select('trending_categories[]', $categories, array_keys($trending_categories), ['class' => 'form-control select2-normal', 'multiple' => 'multiple']) !!}
        <small class="text-muted">{{ trans('help.trending_now_category_help') }}</small>
        <div class="help-block with-errors"></div>
      </div>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
