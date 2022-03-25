@extends('admin.layouts.master')

@php
$can_update = Gate::allows('update', $system) ?: null;
@endphp

@section('content')
  <div class="box">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#basic_settings_tab" data-toggle="tab">
            <i class="fa fa-cubes hidden-sm"></i>
            {{ trans('app.basic_settings') }}
          </a></li>
        <li><a href="#formats_tab" data-toggle="tab">
            <i class="fa fa-cog hidden-sm"></i>
            {{ trans('app.config_formats') }}
          </a></li>
        <li><a href="#payment_method_tab" data-toggle="tab">
            <i class="fa fa-credit-card hidden-sm"></i>
            {{ trans('app.payment_methods') }}
          </a></li>
        <li><a href="#support_tab" data-toggle="tab">
            <i class="fa fa-phone hidden-sm"></i>
            {{ trans('app.support') }}
          </a></li>
        <li><a href="#reports_tab" data-toggle="tab">
            <i class="fa fa-line-chart hidden-sm"></i>
            {{ trans('app.reports') }}
          </a></li>
        <li><a href="#notifications_tab" data-toggle="tab">
            <i class="fa fa-bell-o hidden-sm"></i>
            {{ trans('app.notifications') }}
          </a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="basic_settings_tab">
          <div class="row">
            {!! Form::model($system, ['method' => 'PUT', 'route' => ['admin.setting.system.update'], 'files' => true, 'id' => 'form2', 'class' => 'form-horizontal ajax-form', 'data-toggle' => 'validator']) !!}
            <div class="col-sm-6">
              <fieldset>
                <legend>{{ trans('app.config_subscription_section') }}
                  <small>{{ is_subscription_enabled() ? '' : '(' . trans('app.disabled') . ')' }}</small>
                </legend>

                <div class="form-group">
                  {!! Form::label('trial_days', trans('app.config_trial_days') . ':', ['class' => 'with-help col-sm-6 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_trial_days') }}"></i>
                  <div class="col-sm-5 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        {!! Form::number('trial_days', $system->trial_days, ['class' => 'form-control', 'max' => '730', 'placeholder' => trans('app.placeholder.trial_days'), is_subscription_enabled() ? '' : 'disabled']) !!}
                        <span class="input-group-addon">{{ trans('app.form.days') }}</span>
                      </div>
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->trial_days }}</span>
                    @endif
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('required_card_upfront', trans('app.required_card_upfront') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.required_card_upfront') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'required_card_upfront') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->required_card_upfront ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->required_card_upfront ? 'true' : 'false' }}" autocomplete="off" {{ is_subscription_enabled() ? '' : 'disabled' }}>
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->required_card_upfront ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
              </fieldset>

              <fieldset>
                <legend>{{ trans('app.vendors') }} </legend>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('vendor_needs_approval', trans('app.vendor_needs_approval') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.vendor_needs_approval') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'vendor_needs_approval') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->vendor_needs_approval ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->vendor_needs_approval ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->vendor_needs_approval ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('can_use_own_catalog_only', trans('app.can_use_own_catalog_only') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_can_use_own_catalog_only') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'can_use_own_catalog_only') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->can_use_own_catalog_only ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->can_use_own_catalog_only ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->can_use_own_catalog_only ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('show_merchant_info_as_vendor', trans('app.show_merchant_info_as_vendor') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.show_merchant_info_as_vendor') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'show_merchant_info_as_vendor') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->show_merchant_info_as_vendor ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->show_merchant_info_as_vendor ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->show_merchant_info_as_vendor ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('vendor_can_view_customer_info', trans('app.vendor_can_view_customer_info') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.vendor_can_view_customer_info') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'vendor_can_view_customer_info') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->vendor_can_view_customer_info ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->vendor_can_view_customer_info ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->vendor_can_view_customer_info ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('enable_chat', trans('app.enable_live_chat') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.enable_live_chat_on_platform') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'enable_chat') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->enable_chat ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->enable_chat ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->vendor_can_view_customer_info ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('vendor_order_cancellation_fee', trans('app.vendor_order_cancellation_fee') . ':', ['class' => 'with-help col-sm-6 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_vendor_order_cancellation_fee') }}"></i>
                  <div class="col-sm-5 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        @if (get_currency_prefix())
                          <span class="input-group-addon" id="basic-addon1">
                            {{ get_currency_prefix() }}
                          </span>
                        @endif

                        {!! Form::number('vendor_order_cancellation_fee', $system->vendor_order_cancellation_fee, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('app.cancellation_fee'), can_set_cancellation_fee() ? '' : 'disabled']) !!}

                        @if (get_currency_suffix())
                          <span class="input-group-addon" id="basic-addon1">
                            {{ get_currency_suffix() }}
                          </span>
                        @endif
                      </div>
                      <div class="help-block with-errors">
                        @if (!is_incevio_package_loaded(['wallet']))
                          <small class="text-danger">
                            <i class="fa fa-ban"></i>
                            {{ trans('help.option_dependence_module', ['dependency' => 'wallet']) }}
                          </small>
                        @elseif(vendor_get_paid_directly())
                          {{ trans('help.disabled_when_vendor_get_paid_directly') }}
                        @endif
                      </div>
                    @else
                      <span>{{ $system->vendor_order_cancellation_fee }}</span>
                    @endif
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <legend>{{ trans('app.config_customer_section') }}</legend>

                <div class="form-group">
                  {!! Form::label('can_cancel_order_within', trans('app.can_cancel_order_within') . ':', ['class' => 'with-help col-sm-6 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_can_cancel_order_within') }}"></i>
                  <div class="col-sm-5 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        {!! Form::number('can_cancel_order_within', $system->can_cancel_order_within, ['class' => 'form-control', 'placeholder' => trans_choice('app.minutes', 30)]) !!}
                        <span class="input-group-addon">{{ trans_choice('app.minutes', 30) }}</span>
                      </div>
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->can_cancel_order_within }}</span>
                    @endif
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('ask_customer_for_email_subscription', trans('app.ask_customer_for_email_subscription') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.ask_customer_for_email_subscription') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'ask_customer_for_email_subscription') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->ask_customer_for_email_subscription ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->ask_customer_for_email_subscription ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->ask_customer_for_email_subscription ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div><!-- /.row -->

                <div class="row">
                  <div class="col-sm-7">
                    <div class="form-group text-right">
                      {!! Form::label('social_auth', trans('app.show_social_auth') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.social_auth') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'social_auth') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->social_auth ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->social_auth ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->social_auth ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('allow_guest_checkout', trans('app.allow_guest_checkout') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.allow_guest_checkout') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'allow_guest_checkout') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->allow_guest_checkout ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->allow_guest_checkout ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->allow_guest_checkout ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div><!-- /.row -->
              </fieldset>
            </div>

            <div class="col-sm-6">
              <fieldset>
                <legend><i class="fa fa-cubes hidden-sm"></i> {{ trans('app.inventory') }}</legend>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('show_item_conditions', trans('app.show_item_conditions') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.show_item_conditions') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'show_item_conditions') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->show_item_conditions ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->show_item_conditions ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->show_item_conditions ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="form-group">
                  {!! Form::label('max_img_size_limit_kb', trans('app.max_img_size_limit_kb') . ':', ['class' => 'with-help col-sm-6 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_max_img_size_limit_kb') }}"></i>
                  <div class="col-sm-5 nopadding-left">
                    @if ($can_update)
                      {!! Form::number('max_img_size_limit_kb', $system->max_img_size_limit_kb, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.max_img_size_limit_kb')]) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->max_img_size_limit_kb }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('max_number_of_inventory_imgs', trans('app.max_number_of_inventory_imgs') . ':', ['class' => 'with-help col-sm-6 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_max_number_of_inventory_imgs') }}"></i>
                  <div class="col-sm-5 nopadding-left">
                    @if ($can_update)
                      {!! Form::number('max_number_of_inventory_imgs', $system->max_number_of_inventory_imgs, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.max_number_of_inventory_imgs')]) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->max_number_of_inventory_imgs }}</span>
                    @endif
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <legend>{{ trans('app.units') }}</legend>
                <div class="form-group">
                  {!! Form::label('weight_unit', '*' . trans('app.weight_unit') . ':', ['class' => 'with-help col-sm-5 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.system_weight_unit') }}"></i>
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      {!! Form::select('weight_unit', ['g' => 'Gram(g)', 'kg' => 'Kilogram(kg)', 'lb' => 'Pound(lb)', 'oz' => 'Ounce(oz)'], $system->weight_unit, ['class' => 'form-control select2-normal', 'required']) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->weight_unit }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('length_unit', '*' . trans('app.length_unit') . ':', ['class' => 'with-help col-sm-5 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.system_length_unit') }}"></i>
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      {!! Form::select('length_unit', ['meter' => 'Meter(M)', 'cm' => 'Centemeter(cm)', 'in' => 'Inch(in)'], $system->length_unit, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.select'), 'required']) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->length_unit }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('valume_unit', '*' . trans('app.valume_unit') . ':', ['class' => 'with-help col-sm-5 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.system_valume_unit') }}"></i>
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      {!! Form::select('valume_unit', ['liter' => 'Liter(L)', 'gal' => 'gallon(gal)'], $system->valume_unit, ['class' => 'form-control select2-normal', 'required']) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->valume_unit }}</span>
                    @endif
                  </div>
                </div>
              </fieldset>


              <fieldset>
                <legend><i class="fa fa-laptop hidden-sm"></i> {{ trans('app.views') }}</legend>
                <div class="form-group">
                  {!! Form::label('pagination', trans('app.pagination') . ':', ['class' => 'with-help col-sm-6 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.system_pagination') }}"></i>
                  <div class="col-sm-5 nopadding-left">
                    @if ($can_update)
                      {!! Form::number('pagination', $system->pagination, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.pagination')]) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->pagination }}</span>
                    @endif
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('show_seo_info_to_frontend', trans('app.show_seo_info_to_frontend') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_show_seo_info_to_frontend') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'show_seo_info_to_frontend') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->show_seo_info_to_frontend ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->show_seo_info_to_frontend ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->show_seo_info_to_frontend ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
              </fieldset>

              <fieldset>
                <legend>{{ trans('app.address') }}</legend>
                <div class="form-group">
                  {!! Form::label('address_default_country', trans('app.config_address_default_country') . ':', ['class' => 'with-help col-sm-5 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_address_default_country') }}"></i>
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      {!! Form::select('address_default_country', $countries, $system->address_default_country, ['id' => 'country_id', 'class' => 'form-control select2', 'placeholder' => trans('app.placeholder.country')]) !!}
                    @else
                      <span>{{ get_value_from($system->address_default_country, 'countries', 'name') }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('address_default_state', trans('app.config_address_default_state') . ':', ['class' => 'with-help col-sm-5 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_address_default_state') }}"></i>
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      {!! Form::select('address_default_state', $states, $system->address_default_state, ['id' => 'state_id', 'class' => 'form-control select2-tag', 'placeholder' => trans('app.placeholder.state')]) !!}
                    @else
                      <span>{{ get_value_from($system->address_default_state, 'states', 'name') }}</span>
                    @endif
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('show_address_title', trans('app.show_address_title') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_show_address_title') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'show_address_title') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->show_address_title ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->show_address_title ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->show_address_title ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('address_show_country', trans('app.address_show_country') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_address_show_country') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'address_show_country') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->address_show_country ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->address_show_country ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->address_show_country ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('address_show_map', trans('app.address_show_map') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_address_show_map') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'address_show_map') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->address_show_map ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->address_show_map ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->address_show_map ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
              </fieldset>
            </div>

            @if ($can_update)
              <div class="col-sm-6">
                <p class="help-block">* {{ trans('app.form.required_fields') }}</p>
              </div>
              <div class="col-sm-6">
                {!! Form::submit(trans('app.update'), ['class' => 'btn btn-lg btn-flat btn-new']) !!}
              </div>
            @endif
            {!! Form::close() !!}
          </div> <!-- /.row -->
        </div> <!-- /.tab-pane -->

        <div class="tab-pane" id="formats_tab">
          <div class="row">
            {!! Form::model($system, ['method' => 'PUT', 'route' => ['admin.setting.system.update'], 'files' => true, 'id' => 'form2', 'class' => 'form-horizontal ajax-form', 'data-toggle' => 'validator']) !!}
            <div class="col-sm-6">
              {{-- <fieldset>
					    			<legend>{{ trans('app.config_date_and_time') }}</legend>
									<div class="form-group">
								        {!! Form::label('date_format', '*' . trans('app.date_format'). ':', ['class' => 'with-help col-sm-7 control-label']) !!}
									  	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.system_date_format') }}"></i>
									  	<div class="col-sm-4 nopadding-left">
									  		@if ($can_update)
											    {!! Form::select('date_format', ['YYYY-MM-DD' => 'YYYY-MM-DD', 'DD-MM-YYYY' => 'DD-MM-YYYY', 'MM-DD-YYYY' => 'MM-DD-YYYY'], $system->date_format, ['class' => 'form-control select2-normal', 'required']) !!}
										      	<div class="help-block with-errors"></div>
											@else
												<span>{{ $system->date_format }}</span>
											@endif
									  	</div>
									</div>

									<div class="form-group">
								        {!! Form::label('date_separator', '*' . trans('app.date_separator'). ':', ['class' => 'with-help col-sm-7 control-label']) !!}
									  	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_date_separator') }}"></i>
									  	<div class="col-sm-4 nopadding-left">
									  		@if ($can_update)
											    {!! Form::select('date_separator', ['.' => '.', '-' => '-', '/' => '/'], $system->date_separator, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.select'), 'required']) !!}
										      	<div class="help-block with-errors"></div>
											@else
												<span>{{ $system->date_separator }}</span>
											@endif
									  	</div>
									</div>

									<div class="form-group">
								        {!! Form::label('time_format', '*' . trans('app.time_format'). ':', ['class' => 'with-help col-sm-7 control-label']) !!}
									  	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.system_time_format') }}"></i>
									  	<div class="col-sm-4 nopadding-left">
									  		@if ($can_update)
											    {!! Form::select('time_format', ['12h' => '12h', '24h' => '24h'], $system->time_format, ['class' => 'form-control select2-normal', 'required']) !!}
										      	<div class="help-block with-errors"></div>
											@else
												<span>{{ $system->time_format }}</span>
											@endif
									  	</div>
									</div>

									<div class="form-group">
								        {!! Form::label('time_separator', '*' . trans('app.time_separator'). ':', ['class' => 'with-help col-sm-7 control-label']) !!}
									  	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_time_separator') }}"></i>
									  	<div class="col-sm-4 nopadding-left">
									  		@if ($can_update)
											    {!! Form::select('time_separator', ['.' => '.', ':' => ':'], $system->time_separator, ['class' => 'form-control select2-normal', 'placeholder' => trans('app.placeholder.select'), 'required']) !!}
										      	<div class="help-block with-errors"></div>
											@else
												<span>{{ $system->time_separator }}</span>
											@endif
									  	</div>
									</div>
					    		</fieldset> --}}

              <fieldset>
                <legend>{{ trans('app.config_currency') }}</legend>
                <div class="form-group">
                  {!! Form::label('decimals', '*' . trans('app.decimals') . ':', ['class' => 'with-help col-sm-7 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_decimals') }}"></i>
                  <div class="col-sm-4 nopadding-left">
                    @if ($can_update)
                      {!! Form::select('decimals', ['0' => '0', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'], $system->decimals, ['class' => 'form-control select2-normal', 'required']) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->decimals }}</span>
                    @endif
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('show_currency_symbol', trans('app.show_currency_symbol') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_show_currency_symbol') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'show_currency_symbol') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->show_currency_symbol ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->show_currency_symbol ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->show_currency_symbol ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
                <div class="row">
                  <div class="col-sm-7 text-right">
                    <div class="form-group">
                      {!! Form::label('show_space_after_symbol', trans('app.show_space_after_symbol') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_show_space_after_symbol') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal text-center">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'show_space_after_symbol') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->show_space_after_symbol ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->show_space_after_symbol ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->show_space_after_symbol ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
              </fieldset>
            </div>

            <div class="col-sm-6">
              <fieldset>
                <legend>{{ trans('app.config_promotions') }}</legend>
                <div class="form-group">
                  {!! Form::label('coupon_code_size', '*' . trans('app.coupon_code_size') . ':', ['class' => 'with-help col-sm-7 control-label']) !!}
                  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_coupon_code_size') }}"></i>
                  <div class="col-sm-4 nopadding-left">
                    @if ($can_update)
                      {!! Form::number('coupon_code_size', $system->coupon_code_size, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.coupon_code_size'), 'required']) !!}
                      <div class="help-block with-errors"></div>
                    @else
                      <span>{{ $system->coupon_code_size }}</span>
                    @endif
                  </div>
                </div>

                {{-- <div class="form-group">
								        {!! Form::label('gift_card_pin_size', '*' . trans('app.config_gift_card_pin_size'). ':', ['class' => 'with-help col-sm-7 control-label']) !!}
									  	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_gift_card_pin_size') }}"></i>
									  	<div class="col-sm-4 nopadding-left">
									  		@if ($can_update)
									    	    {!! Form::number('gift_card_pin_size', $system->gift_card_pin_size, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.gift_card_pin_size'), 'required']) !!}
										      	<div class="help-block with-errors"></div>
											@else
												<span>{{ $system->gift_card_pin_size }}</span>
											@endif
									  	</div>
									</div>

									<div class="form-group">
								        {!! Form::label('gift_card_serial_number_size', '*' . trans('app.gift_card_serial_number_size'). ':', ['class' => 'with-help col-sm-7 control-label']) !!}
									  	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.config_gift_card_serial_number_size') }}"></i>
									  	<div class="col-sm-4 nopadding-left">
									  		@if ($can_update)
									    	    {!! Form::number('gift_card_serial_number_size', $system->gift_card_serial_number_size, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.gift_card_serial_number_size'), 'required']) !!}
										      	<div class="help-block with-errors"></div>
											@else
												<span>{{ $system->gift_card_serial_number_size }}</span>
											@endif
									  	</div>
									</div> --}}
              </fieldset>
            </div>
            <div class="col-sm-12">
              @if ($can_update)
                <p class="help-block">* {{ trans('app.form.required_fields') }}</p>
                <div class="col-md-offset-3">
                  {!! Form::submit(trans('app.update'), ['class' => 'btn btn-lg btn-flat btn-new']) !!}
                </div>
              @endif
            </div>
            {!! Form::close() !!}
          </div>
        </div> <!-- /.tab-pane -->

        <div class="tab-pane" id="payment_method_tab">
          <div class="jumbotron" style="padding: 20px; margin-bottom: 10px;">
            <p class="text-center">{{ trans('help.config_enable_payment_method') }}</p>
          </div>
          @foreach ($payment_method_types as $type_id => $type)
            @php
              $payment_providers = $payment_methods->where('type', $type_id);
              $logo_path = sys_image_path('payment-method-types') . "{$type_id}.svg";
            @endphp

            <div class="row">
              <span class="spacer10"></span>
              <div class="col-sm-6">
                @if (File::exists($logo_path))
                  <img src="{{ asset($logo_path) }}" width="100" height="25" alt="{{ $type }}">
                  <span class="spacer10"></span>
                @else
                  <p class="lead">{{ $type }}</p>
                @endif
                <p>{!! get_payment_method_type($type_id)['admin_description'] !!}</p>

                @if (!vendor_get_paid_directly() && $type_id == \App\Models\PaymentMethod::TYPE_MANUAL)
                  <div class="spacer20"></div>
                  <div class="alert alert-info">
                    <strong class="text-uppercase">
                      <i class="fa fa-info-circle"></i> {{ trans('app.important') }} :
                    </strong>
                    <span>{!! trans('messages.manual_payment_configure_help') !!}</span>
                  </div>
                @endif
              </div>

              <div class="col-sm-6">
                @foreach ($payment_providers as $payment_provider)
                  <!-- Skip the wallet because wallet setting has option to activatte -->
                  @continue($payment_provider->code == 'zcart-wallet')

                  @php
                    $logo_path = sys_image_path('payment-methods') . "{$payment_provider->code}.png";
                  @endphp
                  <ul class="list-group">
                    <li class="list-group-item">
                      @if (File::exists($logo_path))
                        <img src="{{ asset($logo_path) }}" class="open-img-md" alt="{{ $type }}">
                      @else
                        <p class="list-group-item-heading inline lead">
                          {{ $payment_provider->name }}
                        </p>
                      @endif

                      <div class="handle inline pull-right no-margin">
                        <span class="spacer10"></span>
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.paymentMethod.toggle', $payment_provider->id) }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $payment_provider->enabled == 1 ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $payment_provider->enabled == 1 ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>

                      <span class="spacer10"></span>

                      <p class="list-group-item-text">
                        {!! $payment_provider->admin_description !!}
                      </p>

                      @if (vendor_get_paid_directly() && !$payment_provider->split_money)
                        <div class="spacer20"></div>
                        <div class="alert alert-info small">
                          <strong class="text-uppercase">
                            <i class="fa fa-info-circle"></i> {{ trans('app.important') }} :
                          </strong>
                          <span>{!! trans('messages.cant_charge_application_fee') !!}</span>
                        </div>
                      @endif

                      {{-- Check if not miscofigured --}}
                      @if (!vendor_get_paid_directly() && !\App\Models\SystemConfig::isPaymentConfigured($payment_provider->code))
                        <div class="spacer20"></div>
                        <div class="alert alert-danger">
                          <strong class="text-uppercase">
                            <i class="fa fa-exclamation-triangle"></i> {{ trans('app.alert') }} :
                          </strong>
                          <span>{!! trans('messages.misconfigured_payment', ['payment' => $payment_provider->name]) !!}</span>
                        </div>
                      @endif

                      <span class="spacer15"></span>

                      @if ($payment_provider->admin_help_doc_link)
                        <a href="{{ $payment_provider->admin_help_doc_link }}" class="btn btn-default" target="_blank"> {{ trans('app.documentation') }}</a>
                        <span class="spacer15"></span>
                      @endif
                    </li>
                  </ul>
                @endforeach
              </div>
            </div>

            @unless($loop->last)
              <hr>
            @endunless
          @endforeach
        </div> <!-- /.tab-pane -->

        <div class="tab-pane" id="support_tab">
          {!! Form::model($system, ['method' => 'PUT', 'route' => ['admin.setting.system.update'], 'files' => true, 'id' => 'form2', 'class' => 'form-horizontal ajax-form', 'data-toggle' => 'validator']) !!}
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                {!! Form::label('support_phone', trans('app.support_phone') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.support_phone') }}"></i>
                <div class="col-sm-6 nopadding-left">
                  @if ($can_update)
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                      {!! Form::number('support_phone', $system->support_phone, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.support_phone')]) !!}
                    </div>
                  @else
                    <span>{{ $system->support_phone }}</span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('support_phone_toll_free', trans('app.support_phone_toll_free') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.support_phone_toll_free') }}"></i>
                <div class="col-sm-6 nopadding-left">
                  @if ($can_update)
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
                      {!! Form::number('support_phone_toll_free', $system->support_phone_toll_free, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.support_phone_toll_free')]) !!}
                    </div>
                  @else
                    <span>{{ $system->support_phone_toll_free }}</span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('support_email', '*' . trans('app.support_email') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.support_email') }}"></i>
                <div class="col-sm-6 nopadding-left">
                  @if ($can_update)
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                      {!! Form::email('support_email', $system->support_email, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.support_email'), 'required']) !!}
                    </div>
                    <div class="help-block with-errors"></div>
                  @else
                    <span>{{ $system->support_email }}</span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('default_sender_email_address', '*' . trans('app.default_sender_email_address') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.default_sender_email_address') }}"></i>
                <div class="col-sm-6 nopadding-left">
                  @if ($can_update)
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-at"></i></span>
                      {!! Form::email('default_sender_email_address', $system->default_sender_email_address, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.default_sender_email_address'), 'required']) !!}
                    </div>
                    <div class="help-block with-errors"></div>
                  @else
                    <span>{{ $system->default_sender_email_address }}</span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('default_email_sender_name', '*' . trans('app.default_email_sender_name') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.default_email_sender_name') }}"></i>
                <div class="col-sm-6 nopadding-left">
                  @if ($can_update)
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                      {!! Form::text('default_email_sender_name', $system->default_email_sender_name, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.default_email_sender_name'), 'required']) !!}
                    </div>
                    <div class="help-block with-errors"></div>
                  @else
                    <span>{{ $system->default_email_sender_name }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <fieldset>
              <div class="col-sm-12">
                <legend class="col-sm-9">{{ trans('app.social_links') }}</legend>
              </div>
              <div class="col-sm-12">

                <div class="form-group">
                  {!! Form::label('google_plus_link', trans('app.google_plus_link') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-google-plus-official"></i></span>
                        {!! Form::text('google_plus_link', $system->google_plus_link, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.google_plus_link')]) !!}
                      </div>
                    @else
                      <span>{{ $system->google_plus_link }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  {!! Form::label('facebook_link', trans('app.facebook_link') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                        {!! Form::text('facebook_link', $system->facebook_link, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.facebook_link')]) !!}
                      </div>
                    @else
                      <span>{{ $system->facebook_link }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('twitter_link', trans('app.twitter_link') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
                        {!! Form::text('twitter_link', $system->twitter_link, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.twitter_link')]) !!}
                      </div>
                    @else
                      <span>{{ $system->twitter_link }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('pinterest_link', trans('app.pinterest_link') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-pinterest"></i></span>
                        {!! Form::text('pinterest_link', $system->pinterest_link, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.pinterest_link')]) !!}
                      </div>
                    @else
                      <span>{{ $system->pinterest_link }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('instagram_link', trans('app.instagram_link') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-instagram"></i></span>
                        {!! Form::text('instagram_link', $system->instagram_link, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.instagram_link')]) !!}
                      </div>
                    @else
                      <span>{{ $system->instagram_link }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('youtube_link', trans('app.youtube_link') . ':', ['class' => 'with-help col-sm-3 control-label']) !!}
                  <div class="col-sm-6 nopadding-left">
                    @if ($can_update)
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-youtube"></i></span>
                        {!! Form::text('youtube_link', $system->youtube_link, ['class' => 'form-control', 'placeholder' => trans('app.placeholder.youtube_link')]) !!}
                      </div>
                    @else
                      <span>{{ $system->youtube_link }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </fieldset>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <p class="help-block">* {{ trans('app.form.required_fields') }}</p>
              @if ($can_update)
                <div class="col-md-offset-3">
                  {!! Form::submit(trans('app.update'), ['class' => 'btn btn-lg btn-flat btn-new']) !!}
                </div>
              @endif
            </div>
          </div>
          {!! Form::close() !!}
        </div> <!-- /.tab-pane -->

        <div class="tab-pane" id="reports_tab">
          <div class="row">
            <div class="col-sm-6">
              <fieldset>
                <legend>{{ trans('app.visitors') }}</legend>
                <div class="row">
                  <div class="col-sm-8 text-right">
                    <div class="form-group">
                      {!! Form::label('google_analytic_report', trans('app.google_analytic_report') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.google_analytic_report') }}"></i>
                      <div class="help-block">{{ trans('help.changes_can_take_time', ['time' => get_readble_time_from_secconts(config('cache.remember.statistics'))]) }}</div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'google_analytic_report') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->google_analytic_report ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->google_analytic_report ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->google_analytic_report ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
              </fieldset>
            </div> <!-- /.col-sm-6 -->

            <div class="col-sm-6">
            </div> <!-- /.col-sm-6 -->
          </div> <!-- /.row -->
        </div> <!-- /.tab-pane -->

        <div class="tab-pane" id="notifications_tab">
          <div class="row">
            <div class="col-sm-6">
              <fieldset>
                <legend>{{ trans('app.notifications') }}</legend>
                <div class="row">
                  <div class="col-sm-8 text-right">
                    <div class="form-group">
                      {!! Form::label('notify_when_vendor_registered', trans('app.notify_when_vendor_registered') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.notify_when_vendor_registered') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'notify_when_vendor_registered') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->notify_when_vendor_registered ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->notify_when_vendor_registered ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->notify_when_vendor_registered ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-8 text-right">
                    <div class="form-group">
                      {!! Form::label('notify_new_message', trans('app.notify_new_message') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.notify_new_message') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'notify_new_message') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->notify_new_message ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->notify_new_message ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->notify_new_message ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-8 text-right">
                    <div class="form-group">
                      {!! Form::label('notify_new_ticket', trans('app.notify_new_ticket') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.notify_new_ticket') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'notify_new_ticket') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->notify_new_ticket ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->notify_new_ticket ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->notify_new_ticket ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->

                <div class="row">
                  <div class="col-sm-8 text-right">
                    <div class="form-group">
                      {!! Form::label('notify_when_dispute_appealed', trans('app.notify_when_dispute_appealed') . ':', ['class' => 'with-help control-label']) !!}
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('help.notify_when_dispute_appealed') }}"></i>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    @if ($can_update)
                      <div class="handle horizontal">
                        <a href="javascript:void(0)" data-link="{{ route('admin.setting.system.config.toggle', 'notify_when_dispute_appealed') }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $system->notify_when_dispute_appealed ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $system->notify_when_dispute_appealed ? 'true' : 'false' }}" autocomplete="off">
                          <div class="btn-handle"></div>
                        </a>
                      </div>
                    @else
                      <span>{{ $system->notify_when_dispute_appealed ? trans('app.on') : trans('app.off') }}</span>
                    @endif
                  </div>
                </div> <!-- /.row -->
              </fieldset>
            </div> <!-- /.col-sm-6 -->

            <div class="col-sm-6">

            </div> <!-- /.col-sm-6 -->
          </div> <!-- /.row -->
        </div>
      </div>
      <!-- /.tab-content -->
    </div>
  </div> <!-- /.box -->
@endsection
