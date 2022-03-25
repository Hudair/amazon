@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.category_groups') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\CategoryGroup::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.catalog.categoryGroup.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_category_group') }}</a>
        @endcan
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\CategoryGroup::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.catalog.categoryGroup.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.catalog.categoryGroup.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.background_image') }}</th>
            <th>{{ trans('app.cover_image') }}</th>
            <th>{{ trans('app.category_group') }}</th>
            <th>{{ trans('app.sub_groups') }}</th>
            <th>{{ trans('app.order') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($categoryGrps as $categoryGrp)
            <tr>
              @can('massDelete', \App\Models\CategoryGroup::class)
                <td><input id="{{ $categoryGrp->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                @if (Storage::exists(optional($categoryGrp->backgroundImage)->path))
                  <img src="{{ get_storage_file_url(optional($categoryGrp->backgroundImage)->path, 'small') }}" class="" alt="{{ trans('app.background_image') }}">
                @endif
              </td>
              <td>
                @if (Storage::exists(optional($categoryGrp->coverImage)->path))
                  <img src="{{ get_storage_file_url(optional($categoryGrp->coverImage)->path, 'cover_thumb') }}" class="img-sm" alt="{{ trans('app.cover_image') }}">
                @endif
              </td>
              <td>
                <h5>
                  <i class="fa {{ $categoryGrp->icon }}"></i> {{ $categoryGrp->name }}
                  @unless($categoryGrp->active)
                    <span class="label label-default indent5 small">{{ trans('app.inactive') }}</span>
                  @endunless
                </h5>
                @if ($categoryGrp->description)
                  <span class="excerpt-td small">{!! Str::limit($categoryGrp->description, 220) !!}</span>
                @endif
              </td>
              <td>
                <span class="label label-default">{{ $categoryGrp->sub_groups_count }}</span>
              </td>
              <td>{{ $categoryGrp->order }}</td>
              <td class="row-options">
                @can('update', $categoryGrp)
                  <a href="javascript:void(0)" data-link="{{ route('admin.catalog.categoryGroup.edit', $categoryGrp->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $categoryGrp)
                  {!! Form::open(['route' => ['admin.catalog.categoryGroup.trash', $categoryGrp->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                  {!! Form::close() !!}
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->

  <div class="box collapsed-box">
    <div class="box-header with-border">
      <h3 class="box-title">
        @can('massDelete', \App\Models\CategoryGroup::class)
          {!! Form::open(['route' => ['admin.catalog.categoryGroup.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-option">
        <thead>
          <tr>
            <th>{{ trans('app.category_group') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <h5>
                  <i class="fa {{ $trash->icon }}"></i> {{ $trash->name }}
                </h5>
                @if ($trash->description)
                  <span class="excerpt-td small">{!! Str::limit($trash->description, 220) !!}</span>
                @endif
              </td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.catalog.categoryGroup.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.catalog.categoryGroup.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.delete_permanently'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                  {!! Form::close() !!}
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
@endsection
