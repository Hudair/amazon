<td class="row-options">
  @can('update', $category)
    <a href="javascript:void(0)" data-link="{{ route('admin.catalog.category.edit', $category->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
  @endcan
  @can('delete', $category)
    {!! Form::open(['route' => ['admin.catalog.category.trash', $category->id], 'method' => 'delete', 'class' => 'data-form']) !!}
    {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
    {!! Form::close() !!}
  @endcan
</td>
