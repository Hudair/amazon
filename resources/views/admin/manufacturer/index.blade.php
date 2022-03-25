@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.manufacturers') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Manufacturer::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.catalog.manufacturer.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_manufacturer') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Manufacturer::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.catalog.manufacturer.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.catalog.manufacturer.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.phone') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.country') }}</th>
            <th>{{ trans('app.products') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($manufacturers as $manufacturer)
            <tr>
              @can('massDelete', \App\Models\Manufacturer::class)
                <td><input id="{{ $manufacturer->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($manufacturer->logoImage)->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
              </td>
              <td>
                <p class="indent10">
                  {{ $manufacturer->name }}
                </p>
                @unless($manufacturer->active)
                  <span class="label label-default indent5 small">{{ trans('app.inactive') }}</span>
                @endunless
              </td>
              <td>{{ $manufacturer->phone }}</td>
              <td>{{ $manufacturer->email }}</td>
              <td>{{ optional($manufacturer->country)->name }}</td>
              <td>
                <span class="label label-default">{{ $manufacturer->products_count }}</span>
              </td>
              <td class="row-options">
                @can('view', $manufacturer)
                  <a href="javascript:void(0)" data-link="{{ route('admin.catalog.manufacturer.show', $manufacturer->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
                @endcan

                @can('update', $manufacturer)
                  <a href="javascript:void(0)" data-link="{{ route('admin.catalog.manufacturer.edit', $manufacturer->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $manufacturer)
                  {!! Form::open(['route' => ['admin.catalog.manufacturer.trash', $manufacturer->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Manufacturer::class)
          {!! Form::open(['route' => ['admin.catalog.manufacturer.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
      <table class="table table-hover table-2nd-sort">
        <thead>
          <tr>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.phone') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <img src="{{ get_storage_file_url(optional($trash->logo)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.image') }}">
                <p class="indent10">
                  {{ $trash->name }}
                </p>
              </td>
              <td>{{ $trash->phone }}</td>
              <td>{{ $trash->email }}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.catalog.manufacturer.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.catalog.manufacturer.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
