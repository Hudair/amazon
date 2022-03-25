@extends('admin.layouts.master')

@section('content')
	<div class="box">
	    <div class="box-header with-border">
	      <h3 class="box-title">{{ trans('wallet::lang.payouts') }}</h3>
	      <div class="box-tools pull-right">
		    @if(Auth::user()->isAdmin())
				 <a href="javascript:void(0)" data-link="{{ route('admin.wallet.payout.form') }}" class="ajax-modal-btn btn btn-new btn-flat"><i class="fa fa-plus"></i> {{ trans('wallet::lang.payout') }}</a>
			@endif
	      </div>
	    </div> <!-- /.box-header -->
	    <div class="box-body">
		    <table class="table table-hover table-no-sort">
		        <thead>
			        <tr>
						<th>{{ trans('wallet::lang.date') }}</th>
						<th>{{ trans('wallet::lang.shop') }}</th>
						<th>{{ trans('wallet::lang.description') }}</th>
						<th>{{ trans('wallet::lang.remaining_balance') }}</th>
						<th>{{ trans('wallet::lang.amount') }}</th>
						<th>{{ trans('wallet::lang.status') }}</th>
						<th>{{ trans('wallet::lang.option') }}</th>
			        </tr>
		        </thead>
				<tbody>
			        @foreach($payouts as $transaction)
				        @if($transaction->isTypeOf(\Incevio\Package\Wallet\Models\Transaction::TYPE_PAYOUT))
					        <tr>
					        	<td>
					        		{{ $transaction->created_at->toFormattedDateString() }}
					        	</td>
					        	<td>
					        		{{ $transaction->payable->getName() }}
					        	</td>
								<td>
					        		{!! $transaction->getFromMetaData('description') !!}
					        	</td>
					        	<td>
					        		{{ get_formated_currency($transaction->balance, config('system_settings.decimals', 2)) }}
					        	</td>
					        	<td>
					        		{{ get_formated_currency($transaction->amount, config('system_settings.decimals', 2)) }}
					        	</td>
					        	<td>
					        		{!! $transaction->statusName() !!}
					        	</td>
								<td>
									@if($transaction->isApproved())
										<a href="{{ route('wallet.transaction.invoice', $transaction) }}" class="btn btn-default btn-sm btn-flat">
											<i class="fa fa-file-o"></i> {{ trans('app.invoice') }}
										</a>
									@endif
					        	</td>
					        </tr>
			        	@endif
			        @endforeach
		        </tbody>
		    </table>
	    </div> <!-- /.box-body -->
	</div> <!-- /.box -->
@endsection