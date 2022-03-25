@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.delivery_boys') }}</h3>
      <div class="box-tools pull-right">
        <a href="javascript:void(0)" data-link="{{ route('admin.admin.deliveryboy.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_delivery_boy') }}</a>
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\User::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.admin.deliveryboy.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>

                    <li><a href="javascript:void(0)" data-link="{{ route('admin.admin.deliveryboy.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.avatar') }}</th>
            <th>{{ trans('app.nice_name') }}</th>
            <th>{{ trans('app.full_name') }}</th>
            <th>{{ trans('app.phone_number') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.status') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($deliveryBoys as $deliveryboy)
            <tr>
              <td><input id="{{ $deliveryboy->id }}" type="checkbox" class="massCheck"></td>
              <td>
                <img src="{{ get_avatar_src($deliveryboy, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
              </td>
              <td>{{ $deliveryboy->nice_name }}</td>
              <td>{{ $deliveryboy->full_name }}</td>
              <td>{{ $deliveryboy->phone_number ?? '' }}</td>
              <td>{{ $deliveryboy->email }}</td>
              <td>{{ $deliveryboy->status == 1 ? trans('app.active') : trans('app.inactive') }}</td>
              <td class="row-options">
                <a href="javascript:void(0)" data-link="{{ route('admin.admin.deliveryboy.show', $deliveryboy->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.profile') }}" class="fa fa-user-circle-o"></i></a>&nbsp;

                <a href="javascript:void(0)" data-link="{{ route('admin.admin.deliveryboy.edit', $deliveryboy->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;

                {!! Form::open(['route' => ['admin.admin.deliveryboy.trash', $deliveryboy->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                {!! Form::close() !!}
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
        {!! Form::open(['route' => ['admin.admin.deliveryboy.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
        {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm btn btn-default btn-flat ajax-silent', 'title' => trans('help.empty_trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'right']) !!}
        {!! Form::close() !!}
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
            <th>{{ trans('app.avatar') }}</th>
            <th>{{ trans('app.full_name') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <img src="{{ get_avatar_src($trash, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
              </td>
              <td>{{ $trash->nice_name }}</td>
              <td>{{ $trash->email }}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                <a href="{{ route('admin.admin.deliveryboy.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                {!! Form::open(['route' => ['admin.admin.deliveryboy.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.delete_permanently'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
