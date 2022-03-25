<div class="modal-dialog modal-sm">
    <div class="modal-content">
        {!! Form::open(['route' => ['admin.package.save'], 'id' => 'form', 'files' => true, 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <i class="fa fa-upload"></i>
            {{ trans('app.upload_package') }}
        </div>
        <div class="modal-body">
            <span class="spacer20"></span>
            <div class="row">
                <div class="col-md-9 nopadding-right">
                    <input id="uploadFile" placeholder="{{ trans('app.zip_archive') }}" class="form-control" disabled="disabled" style="height: 28px;" />
                </div>
                <div class="col-md-3 nopadding-left">
                    <div class="fileUpload btn btn-primary btn-block btn-flat">
                      <span>{{ trans('app.form.select') }}</span>
                      <input type="file" name="zip_archive" id="uploadBtn" class="upload" />
                    </div>
                </div>
            </div>

            <span class="spacer20"></span>

            <p class="text-info">
                <i class="fa fa-info-circle"></i>
                {!! trans('help.upload_package_zip_archive') !!}
            </p>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.form.upload'), ['class' => 'btn btn-flat btn-lg btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->