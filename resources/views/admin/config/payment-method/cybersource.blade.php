<div class="modal-dialog modal-sm">
  <div class="modal-content">
    {!! Form::model($cybersource, ['method' => 'PUT', 'route' => ['admin.setting.cybersource.update', $cybersource], 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        {{ trans('app.form.config') . ' cybersource' }}
    </div>
    <div class="modal-body">
        <div class="form-group">
          {!! Form::label('sandbox', trans('app.form.environment') . '*', ['class' => 'with-help']) !!}
          <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.config_payment_environment') }}"></i>
          {!! Form::select('sandbox', ['1' => trans('app.test'), '0' => trans('app.live')], null, ['class' => 'form-control select2-normal', 'required']) !!}
          <div class="help-block with-errors"></div>
        </div>

        <div class="form-group">
          {!! Form::label('merchant_id', trans('app.cybersource_merchant_id') . '*', ['class' => 'with-help']) !!}
          {{-- <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.config_cybersource_merchant_id') }}"></i> --}}
          {!! Form::text('merchant_id', Null, ['class' => 'form-control', 'placeholder' => trans('app.cybersource_merchant_id'), 'required']) !!}
          <div class="help-block with-errors"></div>
        </div>

        <div class="form-group">
          {!! Form::label('api_key_id', trans('app.cybersource_api_key_id') . '*', ['class' => 'with-help']) !!}
          {{-- <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.config_cybersource_api_key_id') }}"></i> --}}
          {!! Form::text('api_key_id', Null, ['class' => 'form-control', 'placeholder' => trans('app.cybersource_api_key_id'), 'required']) !!}
          <div class="help-block with-errors"></div>
        </div>

        <div class="form-group">
          {!! Form::label('secret', trans('app.cybersource_secret') . '*', ['class' => 'with-help']) !!}
          {{-- <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.config_cybersource_secret') }}"></i> --}}
          {!! Form::text('secret', Null, ['class' => 'form-control', 'placeholder' => trans('app.cybersource_secret'), 'required']) !!}
          <div class="help-block with-errors"></div>
        </div>

    </div>
    <div class="modal-footer">
        {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->