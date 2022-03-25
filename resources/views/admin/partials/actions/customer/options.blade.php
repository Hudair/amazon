@can('view', $customer)
  <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.show', $customer->id) }}" class="ajax-modal-btn modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.profile') }}" class="fa fa-user-circle-o"></i></a>&nbsp;
@endcan

@can('update', $customer)
  <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.edit', $customer->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;

  <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.changePassword', $customer->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.change_password') }}" class="fa fa-lock"></i></a>&nbsp;
@endcan

@can('view', $customer)
  @if ($customer->primaryAddress)
    <a href="{{ route('admin.admin.customer.addresses', $customer->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.show_addresses') }}" class="fa fa-address-card-o"></i></a>&nbsp;
  @else
    <a href="javascript:void(0)" data-link="{{ route('address.create', ['customer', $customer->id]) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.add_address') }}" class="fa fa-plus-square-o"></i></a>&nbsp;
  @endif
@endcan

@can('delete', $customer)
  {!! Form::open(['route' => ['admin.admin.customer.trash', $customer->id], 'method' => 'delete', 'class' => 'data-form']) !!}
  {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
  {!! Form::close() !!}
@endcan
