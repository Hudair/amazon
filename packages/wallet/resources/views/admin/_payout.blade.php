<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        	{{ trans('wallet::lang.payout') }}
        </div>
        {!! Form::open(['route' => 'admin.wallet.payout', 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-body">

            <div class="form-group">
                <label>{{ trans('app.shop') }}</label>
                {!! Form::select('shop_id', $shops, null,  ['class' => 'form-control select2', 'width' => '100%', 'placeholder' => trans('wallet::lang.select'), 'required']) !!}
                <div class="help-block with-errors"></div>
            </div>

            <div class="form-group">
                {!! Form::label('order', trans('wallet::lang.payout_amount')) !!}
                <div class="input-group">
                    @if(get_currency_prefix())
                        <span class="input-group-addon">
                            {{ get_currency_prefix() }}
                        </span>
                    @endif
                    {!! Form::number('amount', 0, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('wallet::lang.payout_amount'), 'required']) !!}
                    @if(get_currency_suffix())
                        <span class="input-group-addon">
                            {{ get_currency_suffix() }}
                        </span>
                    @endif
                </div>
                <div class="help-block with-errors"></div>
            </div>

            <div class="form-group">
                {!! Form::label('order', trans('wallet::lang.payout_fee')) !!}
                <div class="input-group">
                    @if(get_currency_prefix())
                        <span class="input-group-addon">
                            {{ get_currency_prefix() }}
                        </span>
                    @endif
                    {!! Form::number('fee', 0, ['class' => 'form-control', 'step' => 'any', 'min' => 0, 'placeholder' => trans('wallet::lang.payout_fee'), 'required']) !!}
                    @if(get_currency_suffix())
                        <span class="input-group-addon">
                            {{ get_currency_suffix() }}
                        </span>
                    @endif
                </div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('wallet::lang.approve'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
