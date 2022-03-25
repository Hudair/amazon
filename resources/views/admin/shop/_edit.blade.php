<div class="modal-dialog modal-lg">
  <div class="modal-content">
    {!! Form::model($shop, ['method' => 'PUT', 'route' => ['admin.vendor.shop.update', $shop->id], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.form.form') }}
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-9 nopadding-right">
          <div class="form-group">
            {!! Form::label('name', trans('app.form.name') . '*', ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_name') }}"></i>
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.shop_name'), 'required']) !!}
            <div class="help-block with-errors"></div>
          </div>
        </div>

        <div class="col-md-3 nopadding-left">
          <div class="form-group">
            {!! Form::label('active', trans('app.form.status'), ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_status') }}"></i>
            {!! Form::select('active', ['1' => trans('app.active'), '0' => trans('app.inactive')], null, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.status')]) !!}
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 nopadding-right">
          <div class="form-group">
            {!! Form::label('custom_subscription_fee', trans('subscription::lang.custom_subscription_fee'), ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{{ trans('subscription::lang.custom_subscription_fee_help_text') }}"></i>
            <div class="input-group">
              @if (get_currency_prefix())
                <span class="input-group-addon" id="basic-addon1">
                  {{ get_currency_prefix() }}
                </span>
              @endif

              {!! Form::number('custom_subscription_fee', null, ['class' => 'form-control', 'step' => 'any', 'min' => '0', 'placeholder' => trans('subscription::lang.bill_amount'), is_incevio_package_loaded('subscription') ? '' : 'disabled']) !!}

              @if (get_currency_suffix())
                <span class="input-group-addon" id="basic-addon1">
                  {{ get_currency_suffix() }}
                </span>
              @endif
            </div>
            <div class="help-block with-errors">
              @unless(is_incevio_package_loaded('subscription'))
                <small class="text-danger">
                  <i class="fa fa-ban"></i>
                  {{ trans('help.option_dependence_module', ['dependency' => 'Subscription']) }}
                </small>
              @endunless
            </div>
          </div>
        </div>

        <div class="col-md-6 nopadding-left">
          <div class="form-group">
            {!! Form::label('commission_rate', trans('dynamicCommission::lang.custom_commission_rate'), ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{{ trans('dynamicCommission::lang.custom_commission_rate_help_text') }}"></i>
            <div class="input-group">
              {!! Form::number('commission_rate', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('dynamicCommission::lang.custom_commission_rate'), is_incevio_package_loaded('dynamicCommission') ? '' : 'disabled']) !!}
              <span class="input-group-addon"><i class="fa fa-percent"></i></span>
            </div>
            <div class="help-block with-errors">
              @unless(is_incevio_package_loaded('dynamicCommission'))
                <small class="text-danger">
                  <i class="fa fa-ban"></i>
                  {{ trans('help.option_dependence_module', ['dependency' => 'Dynamic Commission']) }}
                </small>
              @endunless
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 nopadding-right">
          <div class="form-group">
            {!! Form::label('legal_name', trans('app.form.legal_name') . '*', ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_legal_name') }}"></i>
            {!! Form::text('legal_name', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.shop_legal_name'), 'required']) !!}
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="col-md-6 nopadding-left">
          <div class="form-group">
            {!! Form::label('timezone_id', trans('app.form.timezone'), ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_timezone') }}"></i>
            {!! Form::select('timezone_id', $timezones, isset($shop) ? null : config('system_settings.timezone_id'), ['class' => 'form-control select2', 'placeholder' => trans('app.placeholder.timezone')]) !!}
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 nopadding-right">
          <div class="form-group">
            {!! Form::label('email', trans('app.form.email_address') . '*', ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_email') }}"></i>
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.valid_email'), 'required']) !!}
            <div class="help-block with-errors"></div>
          </div>
        </div>

        <div class="col-md-6 nopadding-left">
          <div class="form-group">
            {!! Form::label('external_url', trans('app.form.external_url'), ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_external_url') }}"></i>
            {!! Form::text('external_url', null, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.external_url')]) !!}
          </div>
        </div>
      </div>

      <div class="form-group">
        {!! Form::label('description', trans('app.form.description'), ['class' => 'with-help']) !!}
        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.shop_description') }}"></i>
        {!! Form::textarea('description', null, ['class' => 'form-control summernote', 'placeholder' => trans('app.placeholder.description')]) !!}
      </div>

      <div class="row">
        <div class="col-md-6 nopadding-right">
          <div class="form-group">
            {!! Form::label('exampleInputFile', trans('app.form.logo'), ['class' => 'with-help']) !!}
            @if (isset($shop) && Storage::exists(optional($shop->logoImage)->path))
              <label>
                <img src="{{ get_storage_file_url(optional($shop->logoImage)->path, 'small') }}" alt="{!! $shop->name !!}">
                <span style="margin-left: 10px;">
                  {!! Form::checkbox('delete_logo', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_logo') }}
                </span>
              </label>
            @endif
            <div class="row">
              <div class="col-md-9 nopadding-right">
                <input id="uploadFile" placeholder="{{ trans('app.placeholder.logo') }}" class="form-control" disabled="disabled" style="height: 28px;" />
                <div class="help-block with-errors">{{ trans('help.logo_img_size') }}</div>
              </div>
              <div class="col-md-3 nopadding-left">
                <div class="fileUpload btn btn-primary btn-block btn-flat">
                  <span>{{ trans('app.form.upload') }}</span>
                  <input type="file" name="logo" id="uploadBtn" class="upload" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 nopadding-left">
          <div class="form-group">
            {!! Form::label('exampleInputFile', trans('app.form.cover_img'), ['class' => 'with-help']) !!}
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.cover_img', ['page' => trans('app.shop')]) }}"></i>
            @if (isset($shop) && Storage::exists(optional($shop->coverImage)->path))
              <label>
                <img src="{{ get_storage_file_url(optional($shop->coverImage)->path, 'small') }}" alt="{!! $shop->name !!}">
                <span style="margin-left: 10px;">
                  {!! Form::checkbox('delete_cover_image', 1, null, ['class' => 'icheck']) !!} {{ trans('app.form.delete_image') }}
                </span>
              </label>
            @endif
            <div class="row">
              <div class="col-md-9 nopadding-right">
                <input id="uploadFile1" placeholder="{{ trans('app.placeholder.cover_image') }}" class="form-control" disabled="disabled" style="height: 28px;" />
                <div class="help-block with-errors">{{ trans('help.cover_img_size') }}</div>
              </div>
              <div class="col-md-3 nopadding-left">
                <div class="fileUpload btn btn-primary btn-block btn-flat">
                  <span>{{ trans('app.form.upload') }} </span>
                  <input type="file" name="cover_image" id="uploadBtn1" class="upload" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <p class="help-block">* {{ trans('app.form.required_fields') }}</p>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
