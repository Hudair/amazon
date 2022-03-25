<div class="modal-dialog modal-md">
    <div class="modal-content">
        {!! Form::open(['route' => ['admin.package.install', $installable['slug']], 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            {{ trans('app.install') }}
        </div>
        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('license_key', trans('app.license_key').'*', ['class' => 'with-help']) !!}
                {!! Form::text('license_key', Null, ['class' => 'form-control input-lg', 'placeholder' => trans('app.enter_license_key'), 'required']) !!}
                <div class="help-block with-errors"><small class="text-primary"><i class="fa fa-question-circle"></i> {{ trans('help.verify_license_key') }}</small> </div>
            </div>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.verify'), ['class' => 'btn btn-flat btn-lg btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->