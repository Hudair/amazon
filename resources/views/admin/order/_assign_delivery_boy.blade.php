<div class="modal-dialog modal-sm">
  <div class="modal-content">
    {!! Form::open(['route' => ['admin.order.deliveryboy.assign', $order], 'method' => 'post', 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.assign_deliveryboy') }}
    </div>
    <div class="modal-body">
      {!! Form::select('delivery_boy_id', $deliveryboys, $order->delivery_boy_id, ['class' => 'form-control select2', 'placeholder' => trans('app.placeholder.select'), 'required']) !!}
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.form.proceed'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
