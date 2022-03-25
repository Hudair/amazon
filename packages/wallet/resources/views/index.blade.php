@extends('admin.layouts.master')

@section("page-style")
	{{-- @include('wallet::assets.style') --}}
@endsection

@section('content')
    <!-- Info boxes -->
    {{-- <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12 nopadding-right">
          <div class="info-box">

            <span class="info-box-icon bg-yellow">
            	<i class="fa fa-exchange"></i>
            </span>

            <div class="info-box-content">
              	<span class="info-box-text">{{ trans('wallet::lang.pending_balance') }}</span>
              	<span class="info-box-number">
	              {{ get_formated_currency(Auth::user()->shop->balance, config('system_settings.decimals', 2)) }}
              	</span>
          		<span class="progress-description text-muted">
          			<i class="icon ion-md-hourglass"></i>
      				{{ trans('messages.no_sale', ['date' => trans('wallet::lang.today')]) }}
                </span>
            </div> <!-- /.info-box-content -->
          </div> <!-- /.info-box -->
        </div> <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12 nopadding-right nopadding-left">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">
            	<i class="fa fa-calculator"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">{{ trans('wallet::lang.refunded_amount') }}</span>
              	<span class="info-box-number">
		    			<a href="{{ url('admin/order/order?tab=unfulfilled') }}" class="pull-right small" data-toggle="tooltip" data-placement="left" title="{{ trans('wallet::lang.detail') }}" >
	    					<i class="fa fa-send-o"></i>
    					</a>
				</span>
              	<div class="progress" style="background: transparent;"></div>
              	<span class="progress-description text-muted">
                    <i class="fa fa-calendar"></i> {{ trans('wallet::lang.in_last_30_days') }}
                </span>
            </div> <!-- /.info-box-content -->
          </div> <!-- /.info-box -->
        </div> <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12 nopadding-right nopadding-left">
          	<div class="info-box">
	            <span class="info-box-icon bg-red">
	            	<i class="fa fa-bullhorn"></i>
	            </span>

	            <div class="info-box-content">
	              <span class="info-box-text">{{ trans('wallet::lang.desputed_amount') }}</span>
	              	<span class="info-box-number">0
		    			<a href="{{ url('admin/stock/inventory?tab=out_of_stock') }}" class="pull-right small" data-toggle="tooltip" data-placement="left" title="{{ trans('wallet::lang.detail') }}" >
	    					<i class="fa fa-send-o"></i>
		    			</a>
	              	</span>

	              	@php
	              		// $stock_out_percents = $stock_count > 0 ?
	              				// round(($stock_out_count / $stock_count) * 100) :
	              				// ($stock_out_count * 100);
	              	@endphp
	              	<div class="progress">
	                	<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
	              	</div>
	              	<span class="progress-description text-muted">
	                </span>
	            </div> <!-- /.info-box-content -->
          	</div> <!-- /.info-box -->
	    </div> <!-- /.col -->
    	<div class="col-md-3 col-sm-6 col-xs-12 nopadding-left">
         	<div class="info-box">
	            <span class="info-box-icon bg-green">
	            	<i class="fa fa-bank"></i>
	            </span>

	            <div class="info-box-content">
	              	<span class="info-box-text">{{ trans('wallet::lang.balance') }}</span>
	              	<span class="info-box-number">
		              {{ get_formated_currency(Auth::user()->shop->balance, config('system_settings.decimals', 2)) }}
					</span>
	              	<span class="progress-description text-muted">
	                    <i class="fa fa-clock-o"></i> {{ trans('wallet::lang.next_payout_date') }}
	                </span>
	            </div> <!-- /.info-box-content -->
          	</div> <!-- /.info-box -->
     	</div> <!-- /.col -->
    </div> <!-- /.row --> --}}

	<div class="box">
	    <div class="box-header with-border">
	      <h3 class="box-title">{{ trans('wallet::lang.transactions') }}</h3>
	      <div class="box-tools pull-right">
			@if(auth()->user()->isMerchant())
				<a href="javascript:void(0)" data-link="{{ route(config('wallet.routes.withdrawal_form'))  }}" class="ajax-modal-btn btn btn-new btn-flat">
					<i class="fa fa-plus"></i>
					{{ trans('wallet::lang.payout_request') }}
				</a>
				<a href="{{ route(config('wallet.routes.deposit_form'))  }}" class="btn btn-primary btn-flat">
					 {{ get_currency_symbol() . ' ' . trans('wallet::lang.deposit_fund') }}
				</a>

			    <a href="{{ route(config('wallet.routes.transfer_form'))  }}" class=" btn btn-warning btn-flat">
				  <i class="fa fa-exchange"></i>
				  {{ trans('wallet::lang.transfer') }}
			    </a>
			@endif
	      </div>
	    </div> <!-- /.box-header -->
	    <div class="box-body">
		    <table class="table table-hover table-no-sort">
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
					@if($wallet->transactions)
				        @foreach($wallet->transactions as $transaction )
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
									@if($transaction->approved)
										<a href="{{ route('wallet.transaction.invoice', $transaction) }}" class="btn btn-default btn-sm btn-flat">
											<i class="fa fa-file-o"></i>
											{{ trans('app.invoice') }}
										</a>
									@endif
					        	</td>
					        </tr>
				        @endforeach
			        @endif
		        </tbody>
		    </table>
	    </div> <!-- /.box-body -->
	</div> <!-- /.box -->
@endsection

@section('page-script')
	{{-- @include('wallet::assets.scripts') --}}
@endsection