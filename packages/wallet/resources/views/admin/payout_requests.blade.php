@extends('admin.layouts.master')

@section('content')
	<div class="box">
	    <div class="box-header with-border">
	      <h3 class="box-title">{{ trans('wallet::lang.payout_requests') }}</h3>
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
						<th>{{ trans('wallet::lang.payout_amount') }}</th>
						<th>{{ trans('app.options') }}</th>
			        </tr>
		        </thead>
				<tbody>
			        @foreach($payout_requests as $transaction)
				        @if($transaction->isTypeOf(\Incevio\Package\Wallet\Models\Transaction::TYPE_PAYOUT))
					        <tr>
					        	<td>
					        		{{ $transaction->created_at->toFormattedDateString() }}
					        	</td>
					        	<td>
					        		{!! $transaction->payable->getName() !!}
					        	</td>
								<td>
									{!! $transaction->getFromMetaData('description') !!}
								</td>
					        	<td>
					        		{{ get_formated_currency($transaction->amount, config('system_settings.decimals', 2)) }}
					        	</td>
						        <td class="row-options">
									@if(Auth::user()->isAdmin())
										<a href="javascript:void(0)" data-link="{{ route('admin.payout.approval', $transaction) }}" class="ajax-modal-btn btn btn-new btn-sm">
											<i class="fa fa-check"></i> {{ trans('wallet::lang.approve') }}
										</a>

								        {!! Form::open(['route' => ['admin.wallet.payout.decline', $transaction], 'method' => 'post', 'class' => 'action-form confirm']) !!}
								        	<button class="btn btn-flat btn-red" class="">
												<i class="fa fa-close"></i> {{ trans('wallet::lang.decline') }}
								        	</button>
								        {!! Form::close() !!}
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