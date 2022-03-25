<div class="clearfix space30"></div>
<div class="row">
    <div class="col-xs-12">
        <div class="my-info-container">
            <div class="my-info-details" style="border-top: 1px #e8e8e8 solid;">
                <ul>
                    <li>
                        <span class="v">
                            {{ get_formated_currency($wallet->balance)}}
                        </span>
                        <span class="d">{{trans('wallet::lang.available_balance')}}</span>
                    </li>
                    <li>
                        <span class="v">
                            {{ get_formated_currency($wallet->lastDeposit ? $wallet->lastDeposit->amount : 0) }}
                        </span>
                        <span class="d">{{trans('wallet::lang.last_deposit')}}</span>
                    </li>
                    <li>
                        <span class="v">
                            {{ get_formated_currency($wallet->lastDebited ? $wallet->lastDebited->amount : 0) }}
                        </span>
                        <span class="d">{{trans('wallet::lang.last_debited')}} </span>
                    </li>
                    <li>
                        <a href="{{ route('account.wallet.deposit.form') }}">
                            <span style="margin-top: 15px" class="d">
                                <i class="fa fa-plus"></i> {{ trans('wallet::lang.deposit_fund') }}
                            </span>
                        </a>
                    </li>
                    <li class="last">
                        @if(config('wallet.transfer.storefront') == true)
                             <a href="{{ route('account.wallet.transfer.form') }}">
                                 <span style="margin-top: 15px" class="d">
                                    <i class="fa fa-exchange"></i> {{ trans('wallet::lang.transfer') }}
                                </span>
                             </a>
                        @endif
                    </li>
                </ul>
            </div><!-- .my-info-details -->
        </div><!-- .my-info-container -->
    </div><!-- .col-sm-12 -->
</div><!-- .row -->

<div class="clearfix space30"></div>

<table class="table table-bordered table-no-sort">
    <thead>
        <tr>
            <th>{{ trans('wallet::lang.date') }}</th>
            <th>{{ trans('wallet::lang.transaction_type') }}</th>
            <th>{{ trans('wallet::lang.description') }}</th>
            <th>{{ trans('wallet::lang.amount') }}</th>
            <th>{{ trans('wallet::lang.status') }}</th>
            <th>{{ trans('wallet::lang.option') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($wallet->transactions()->take(10) as $transaction )
            <tr>
                <td>
                    {{ $transaction->updated_at->toFormattedDateString() }}
                </td>
                <td>
                    {{ $transaction->type }}
                </td>
                <td>
                    {!! $transaction->getFromMetaData('description') !!}
                </td>
                <td>
                    {{ get_formated_currency($transaction->amount, config('system_settings.decimals', 2)) }}
                </td>
                <td>
                    {!! $transaction->statusName() !!}
                </td>
                <td>
                    @if($transaction->confirmed)
                        <a href="{{ route('wallet.transaction.invoice', $transaction) }}" class="btn btn-default btn-sm btn-flat">
                            <i class="fa fa-file-o"></i>
                            {{ trans('app.invoice') }}
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6"><h4 class="text-center text-muted">{{ trans('wallet::lang.no_transaction_found') }}</h4></td></tr>
        @endforelse
    </tbody>
</table>

<div class="clearfix space50"></div>