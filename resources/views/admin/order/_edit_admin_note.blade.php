<div class="modal-dialog modal-sm">
    <div class="modal-content">
        {!! Form::model($order, ['method' => 'PUT', 'route' => ['admin.order.order.saveAdminNote', $order->id], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        	{{ trans('app.update') }}
        </div>
        <div class="modal-body">
			<div class="form-group">
                {!! Form::label('admin_note', trans('app.form.admin_note'), ['class' => 'with-help']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.admin_note') }}"></i>
                {!! Form::textarea('admin_note', (isset($order->admin_note)) ? $order->admin_note : null, ['class' => 'form-control summernote-without-toolbar', 'rows' => '2', 'placeholder' => trans('app.placeholder.admin_note')]) !!}
			</div>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.form.update'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->