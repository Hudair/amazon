<ul class="list-group">
	@if($billable->stripe_id && $billable->invoices())
	    @foreach($billable->invoices() as $invoice)
		    <table class="table">
		    	<thead>
			    	<tr>
			    		<th>{{ trans('app.date') }}</th>
			    		<th>{{ trans('app.description') }}</th>
			    		<th>{{ trans('app.status') }}</th>
			    		<th>{{ trans('app.amount') }}</th>
			    		<th>&nbsp;</th>
			    	</tr>
		    	</thead>
		    	<tbody>
	                @foreach($invoice->subscriptions() as $subscription)
			    		<tr>
			    			{{-- <td>{{ $invoice->date()->toFormattedDateString() }}</td> --}}
			    			<td>{{ \Carbon\Carbon::createFromTimestamp($invoice->asStripeInvoice()->created)->toFormattedDateString() }}</td>
			    			<td>
			    				{{ trans('app.invoice_for', ['start' => $subscription->startDateAsCarbon()->toFormattedDateString(), 'end' => $subscription->endDateAsCarbon()->toFormattedDateString()]) }}
			    			</td>
			    			<td>{{ trans('app.' . $invoice->status) }}</td>
			    			<td>{{ $invoice->total() }}</td>
				            <td>
				            	<a href="{{ route('admin.account.subscription.invoice',$invoice->id) }}"><i class="fa fa-cloud-download" data-toggle="tooltip" data-placement="top" title="{{ trans('app.download') }}"></i></a>
				            </td>
			    		</tr>
	                @endforeach
		    	</tbody>
		    </table>
	    @endforeach
	@else
    	<span class="indent5">{{ trans('app.no_invoice') }}</span>
	@endif
</ul>