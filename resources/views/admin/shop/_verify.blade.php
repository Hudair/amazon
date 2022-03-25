<div class="modal-dialog modal-sm">
    <div class="modal-content">
        {!! Form::model($shop, ['method' => 'PUT', 'route' => ['admin.vendor.shop.update', $shop->id], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                {{ trans('app.verification') }}
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <div class="input-group">
                    {{ Form::hidden('id_verified', 0) }}
                    {!! Form::checkbox('id_verified', null, $shop->id_verified, ['id' => 'id_verified', 'class' => 'icheckbox_line']) !!}
                    {!! Form::label('id_verified', trans('app.id_verified')) !!}
                    <span class="input-group-addon" id="">
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.mark_id_verified') }}"></i>
                    </span>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    {{ Form::hidden('address_verified', 0) }}
                    {!! Form::checkbox('address_verified', null, $shop->address_verified, ['id' => 'address_verified', 'class' => 'icheckbox_line']) !!}
                    {!! Form::label('address_verified', trans('app.address_verified')) !!}
                    <span class="input-group-addon" id="">
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.mark_address_verified') }}"></i>
                    </span>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    {{ Form::hidden('phone_verified', 0) }}
                    {!! Form::checkbox('phone_verified', null, $shop->phone_verified, ['id' => 'phone_verified', 'class' => 'icheckbox_line']) !!}
                    {!! Form::label('phone_verified', trans('app.phone_verified')) !!}
                    <span class="input-group-addon" id="">
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.mark_phone_verified') }}"></i>
                    </span>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    {{ Form::hidden('active', 0) }}
                    {!! Form::checkbox('active', null, $shop->active, ['id' => 'active', 'class' => 'icheckbox_line']) !!}
                    {!! Form::label('active', trans('app.active')) !!}
                    <span class="input-group-addon" id="">
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.shop_status') }}"></i>
                    </span>
                  </div>
                </div>
            </div>

            <div class="form-group">
              <label>
                <span style="margin-left: 10px;">
                  {{ Form::hidden('remove_from_pending_verification_list', Null) }}
                  {!! Form::checkbox('remove_from_pending_verification_list', 1, 1, ['class' => 'icheck']) !!} {{ trans('app.remove_from_pending_verification_list') }}
                </span>
              </label>
            </div>

            <div class="modal-footer">
                {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
            </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->