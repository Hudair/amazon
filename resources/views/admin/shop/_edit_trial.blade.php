<div class="modal-dialog modal-sm">
  <div class="modal-content">
    {!! Form::model($shop, ['method' => 'PUT', 'route' => ['admin.vendor.subscription.updateTrial', $shop], 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.form.form') }}
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('trial_ends_at', trans('app.trial_ends_at')) !!}
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
          {!! Form::text('trial_ends_at', null, ['class' => 'form-control datetimepicker', 'placeholder' => trans('app.trial_ends_at')]) !!}
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          {{ Form::hidden('hide_trial_notice', 0) }}
          {!! Form::checkbox('hide_trial_notice', null, null, ['id' => 'hide_trial_notice', 'class' => 'icheckbox_line']) !!}
          {!! Form::label('hide_trial_notice', trans('app.hide_trial_notice')) !!}
          <span class="input-group-addon" id="">
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.hide_trial_notice_on_vendor_panel') }}"></i>
          </span>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
