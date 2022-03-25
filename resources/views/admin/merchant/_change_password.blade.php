<div class="modal-dialog modal-sm">
  <div class="modal-content">
    {!! Form::model($merchant, ['method' => 'PUT', 'route' => ['admin.vendor.merchant.updatePassword', $merchant->id], 'id' => 'change-password-form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.form.form') }}
    </div>
    <div class="modal-body">
      @include('admin.partials._password_fields')
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
