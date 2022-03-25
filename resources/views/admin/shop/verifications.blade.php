@extends('admin.layouts.master')

@section('content')
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">{{ trans('app.pending_verifications') }}</h3>
		</div> <!-- /.box-header -->
		<div class="box-body">
			<table class="table table-hover table-option">
				<thead>
					<tr>
						<th>{{ trans('app.shop_name') }}</th>
						<th>{{ trans('app.current_billing_plan') }}</th>
						<th>{{ trans('app.uploaded_documents') }}</th>
						<th>{{ trans('app.option') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($merchants as $merchant )
						@continue(! $merchant->shop) {{-- Skip if shop not avilable --}}

						<tr>
							<td>
								<img src="{{ get_storage_file_url(optional($merchant->shop->logo)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">

								<p class="indent10">
									@can('view', $merchant->shop)
										<a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $merchant->shop->id) }}"  class="ajax-modal-btn">{{ $merchant->shop->name }}</a>
									@else
										{{ $merchant->shop->name }}
									@endcan

				            		@if($merchant->shop->isDown())
							          	<span class="label label-default indent10">{{ trans('app.maintenance_mode') }}</span>
									@endif
								</p>
							</td>
				          	<td>
				          		{{ $merchant->shop->plan->name }}
				          	</td>
							<td>
								@foreach($merchant->attachments as $attachment )
									<a href="{{ route('attachment.download', $attachment) }}">
										<i class="fa fa-cloud-download"></i>
										{{ $attachment->name }}
									</a>
									<small class="indent10">
							            ({{ get_formated_file_size($attachment->size) }})
							            {{ $attachment->updated_at->diffForHumans() }}
									</small>

									@can('delete', $attachment)
										{!! Form::open(['route' => ['attachment.delete', $attachment], 'method' => 'delete', 'class' => 'data-form']) !!}
											{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent text-muted indent10', 'title' => trans('app.delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
										{!! Form::close() !!}
									@endcan

									@if(! $loop->last)
										<br/>
									@endif
								@endforeach
							</td>
							<td class="row-options">
								@if(auth()->user()->isAdmin())
									<a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.verify', $merchant->shop) }}" class="ajax-modal-btn btn btn-default btn-sm btn-flat">{{ trans('app.verify') }}</a>&nbsp;&nbsp;&nbsp;
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div> <!-- /.box-body -->
	</div> <!-- /.box -->
@endsection