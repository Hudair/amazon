<div class="modal-dialog modal-md">
  <div class="modal-content">
    {!! Form::open(['route' => 'admin.promotion.dealOfTheDay.update', 'method' => 'PUT', 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.form.deal_of_the_day') }}
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('item_id', trans('app.form.deal_of_the_day')) !!}
        {!! Form::select('item_id', isset($item) ? [$item->id => $item->title . ' | ' . $item->sku . ' | ' . get_formated_currency($item->current_sale_price())] : [], isset($item) ? $item->id : null, ['class' => 'form-control searchInventoryForSelect', 'style' => 'width: 100%']) !!}
        <div class="help-block with-errors"></div>
      </div>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
