@can('view', $inventory)
	<a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.show', $inventory->id) }}"  class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
@endcan

@can('update', $inventory)
	<a href="{{ route('admin.stock.inventory.edit', $inventory->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
@endcan

@can('delete', $inventory)
	{!! Form::open(['route' => ['admin.stock.inventory.trash', $inventory->id], 'method' => 'delete', 'class' => 'data-form']) !!}
		{!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
	{!! Form::close() !!}
@endcan
