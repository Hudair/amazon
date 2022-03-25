@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.warehouses') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Warehouse::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.stock.warehouse.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_warehouse') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Warehouse::class)
              <th class="massActionWrapper">
                <!-- Check all button -->
                <div class="btn-group ">
                  <button type="button" class="btn btn-xs btn-default checkbox-toggle">
                    <i class="fa fa-square-o" data-toggle="tooltip" data-placement="top" title="{{ trans('app.select_all') }}"></i>
                  </button>
                  <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">{{ trans('app.toggle_dropdown') }}</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.stock.warehouse.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.stock.warehouse.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.incharge') }}</th>
            <th>{{ trans('app.status') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($warehouses as $warehouse)
            <tr>
              @can('massDelete', \App\Models\Warehouse::class)
                <td><input id="{{ $warehouse->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($warehouse->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.image') }}">
              </td>
              <td>{{ $warehouse->name }}</td>
              <td>{{ $warehouse->email }}</td>
              <td>{{ $warehouse->manager ? $warehouse->manager->getName() : '' }}</td>
              <td>{{ $warehouse->active ? trans('app.active') : trans('app.inactive') }}</td>
              <td class="row-options">
                @can('view', $warehouse)
                  <a href="javascript:void(0)" data-link="{{ route('admin.stock.warehouse.show', $warehouse->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
                @endcan

                @can('update', $warehouse)
                  <a href="javascript:void(0)" data-link="{{ route('admin.stock.warehouse.edit', $warehouse->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;

                  @if ($warehouse->primaryAddress)
                    <a href="javascript:void(0)" data-link="{{ route('address.edit', $warehouse->primaryAddress->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.update_address') }}" class="fa fa-map-marker"></i></a>&nbsp;
                  @else
                    <a href="javascript:void(0)" data-link="{{ route('address.create', ['warehouse', $warehouse->id]) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.add_address') }}" class="fa fa-plus-square-o"></i></a>&nbsp;
                  @endif
                @endcan

                @can('delete', $warehouse)
                  {!! Form::open(['route' => ['admin.stock.warehouse.trash', $warehouse->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                  {!! Form::close() !!}
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->

  <div class="box collapsed-box">
    <div class="box-header with-border">
      <h3 class="box-title">
        @can('massDelete', \App\Models\Warehouse::class)
          {!! Form::open(['route' => ['admin.stock.warehouse.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
          {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm btn btn-default btn-flat ajax-silent', 'title' => trans('help.empty_trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'right']) !!}
          {!! Form::close() !!}
        @else
          <i class="fa fa-trash-o"></i>
        @endcan
        {{ trans('app.trash') }}
      </h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.incharge') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <img src="{{ get_storage_file_url(optional($trash->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.image') }}">
              </td>
              <td>{{ $trash->name }}</td>
              <td>{{ $trash->email }}</td>
              <td>{{ $trash->manager ? $trash->manager->getName() : '' }}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.stock.warehouse.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.stock.warehouse.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.delete_permanently'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                  {!! Form::close() !!}
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
