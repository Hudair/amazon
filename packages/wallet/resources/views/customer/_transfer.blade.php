<div class="clearfix space30"></div>
<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default">
      <div class="panel-heading">{{ trans('wallet::lang.transfer_balance') }}</div>
        <div class="panel-body">
            {!! Form::open(['route' => 'account.wallet.transfer', 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
                <div class="form-group">
                    {!! Form::label('email', trans('wallet::lang.transfer_to')) !!}
                    <div class="input-group">
                        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('wallet::lang.transfer_to_wallet'), 'required']) !!}
                        <span class="input-group-addon">
                            <i class="fa fa-info-circle" data-toggle="tooltip" title="{!! trans('wallet::lang.transfer_to_help_text') !!}" data-placement="left"></i>
                        </span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group space30">
                    {!! Form::label('order', trans('wallet::lang.amount')) !!}
                    <div class="input-group">
                        @if(get_currency_prefix())
                            <span class="input-group-addon">
                                {{ get_currency_prefix() }}
                            </span>
                        @endif
                        {!! Form::number('amount', 0, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('wallet::lang.amount'), 'required']) !!}
                        @if(get_currency_suffix())
                            <span class="input-group-addon">
                            {{ get_currency_suffix() }}
                        </span>
                        @endif
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

                <button id="pay-now-btn" class="btn btn-primary btn-lg btn-block" type="submit">
                    <small><i class="fa fa-shield"></i>
                        <span id="pay-now-btn-txt">@lang('wallet::lang.transfer')</span>
                    </small>
                </button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
