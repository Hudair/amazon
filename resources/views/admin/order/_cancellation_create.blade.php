<div class="modal-dialog modal-sm">
    <div class="modal-content">
        {!! Form::model($order, ['method' => 'PUT', 'route' => ['admin.order.order.cancel', $order->id], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        	{{ trans('app.cancel_order') }}
        </div>
        <div class="modal-body">
            @if(Auth::user()->isFromPlatform())
    			<div class="form-group">
                    {!! Form::label('cancellation_fee', trans('app.vendor_order_cancellation_fee'). ':', ['class' => 'with-help']) !!}
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{ trans('help.vendor_order_cancellation_fee') }}"></i>
                    <div class="input-group">
                        @if(get_currency_prefix())
                            <span class="input-group-addon" id="basic-addon1">
                                {{ get_currency_prefix() }}
                            </span>
                        @endif
                        
                        {!! Form::number('cancellation_fee', config('system_settings.vendor_order_cancellation_fee') ?? 0, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('app.cancellation_fee'), 'required']) !!}

                        @if(get_currency_suffix())
                            <span class="input-group-addon" id="basic-addon1">
                                {{ get_currency_suffix() }}
                            </span>
                        @endif
                    </div>
                    <div class="help-block with-errors"></div>
    			</div>
            @elseif(cancellation_require_admin_approval())
                <div class="alert alert-info">
                    <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
                    {!! trans('messages.cancellation_require_admin_approval') !!}
                </div>
            @elseif(config('system_settings.vendor_order_cancellation_fee') > 0)
                <div class="alert alert-warning">
                    <strong><i class="icon fa fa-warning"></i>{{ trans('app.info') }}: </strong>
                    {!! trans('messages.a_cancellation_fee_be_charged', ['fee' => get_formated_currency(config('system_settings.vendor_order_cancellation_fee'))]) !!}
                </div>
            @else
                <div class="alert alert-warning">
                    <strong><i class="icon fa fa-warning"></i>{{ trans('app.alert') }}</strong>
                    {!! trans('messages.order_will_be_cancelled_instantly') !!}
                </div>
            @endif

            {{-- <small>
              {!! Form::checkbox('notify_customer', 1, null, ['class' => 'icheck', 'checked']) !!}
              {!! Form::label('notify_customer', strtoupper(trans('app.notify_customer')), ['class' => 'indent5']) !!}
              <i class="fa fa-question-circle indent5" data-toggle="tooltip" data-placement="top" title="{{ trans('help.notify_customer') }}"></i>
			</small> --}}
			{{-- <p class="help-block">* {{ trans('app.form.required_fields') }}</p> --}}
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.cancel_order'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->