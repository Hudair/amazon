@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.packagings') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Packaging::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.shipping.packaging.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_packaging') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Packaging::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.shipping.packaging.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.shipping.packaging.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.cost') }}</th>
            <th class="text-center">{{ trans('app.active') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($packagings as $packaging)
            <tr>
              @can('massDelete', \App\Models\Packaging::class)
                <td><input id="{{ $packaging->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($packaging->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.image') }}">
              </td>
              <td>
                {{ $packaging->name }}
                @if ($packaging->default)
                  <label class="label label-default indent10">{{ trans('app.default') }}</label>
                @endif
                <br>
                <small>{{ get_formated_dimension($packaging) }}</small>
              </td>
              <td>
                {!! $packaging->cost && $packaging->cost > 0 ? get_formated_currency($packaging->cost, 2) : '<label class="label label-primary">' . trans('app.free') . '</label>' !!}
              </td>
              <td class="text-center">
                {{ $packaging->active ? trans('app.yes') : '-' }}
              </td>
              <td class="row-options">
                @can('update', $packaging)
                  <a href="javascript:void(0)" data-link="{{ route('admin.shipping.packaging.edit', $packaging->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $packaging)
                  {!! Form::open(['route' => ['admin.shipping.packaging.trash', $packaging->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Packaging::class)
          {!! Form::open(['route' => ['admin.shipping.packaging.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.cost') }}</th>
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
              <td>
                {{ $trash->name }}<br>
                <small>{{ get_formated_dimension($trash) }}</small>
              </td>
              <td>
                {!! $trash->cost && $trash->cost > 0 ? get_formated_currency($trash->cost, 2) : '<label class="label label-primary">' . trans('app.free') . '</label>' !!}
              </td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.shipping.packaging.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.shipping.packaging.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
