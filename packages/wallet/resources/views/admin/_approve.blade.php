<div class="modal-dialog modal-xs">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            {{ trans('wallet::lang.approval') }}
        </div>

        {!! Form::open(['route' => ['admin.wallet.payout.approve', $transaction], 'method' => 'post', 'class' => 'action-form', 'data-toggle' => 'validator']) !!}
        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('order', trans('wallet::lang.payout_fee')) !!}
                <div class="input-group">
                    @if(get_currency_prefix())
                        <span class="input-group-addon" id="basic-addon1">
                            {{ get_currency_prefix() }}
                        </span>
                    @endif
                    {!! Form::number('fee', 0, ['class' => 'form-control input-lg', 'step' => 'any', 'min' => 0 , 'placeholder' => trans('wallet::lang.fee'), 'required']) !!}
                    @if(get_currency_suffix())
                        <span class="input-group-addon" id="basic-addon1">
                            {{ get_currency_suffix() }}
                        </span>
                    @endif
                </div>
                <div class="help-block with-errors">{!! trans('wallet::lang.this_amount_will_charge') !!}</div>
            </div>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('wallet::lang.approve'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->