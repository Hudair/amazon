@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.products') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Product::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.catalog.product.bulk') }}" class="ajax-modal-btn btn btn-default btn-flat">{{ trans('app.bulk_import') }}</a>
          <a href="{{ route('admin.catalog.product.create') }}" class=" btn btn-new btn-flat">{{ trans('app.add_product') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover" id="all-product-table">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Product::class)
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
                    <li>
                      <a href="javascript:void(0)" data-link="{{ route('admin.catalog.product.massTrash') }}" class="massAction" data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a>
                    </li>
                    <li>
                      <a href="javascript:void(0)" data-link="{{ route('admin.catalog.product.massDestroy') }}" class="massAction" data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a>
                    </li>
                  </ul>
                </div>
              </th>
            @else
              <th></th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.gtin') }}</th>
            <th width="20%">{{ trans('app.category') }}</th>
            <th>{{ trans('app.listing') }}</th>
            @if (Auth::user()->isFromPlatform())
              <th width="15%">{{ trans('app.added_by') }}</th>
            @else
              <th></th>
            @endif
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->

  {{-- @if (Auth::user()->isFromPlatform()) --}}
  <div class="box collapsed-box">
    <div class="box-header with-border">
      <h3 class="box-title">
        @can('massDelete', \App\Models\Product::class)
          {!! Form::open(['route' => ['admin.catalog.product.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.model_number') }}</th>
            <th>{{ trans('app.category') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                @if ($trash->featuredImage)
                  <img src="{{ get_storage_file_url(optional($trash->featuredImage)->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.featured_image') }}">
                @else
                  <img src="{{ get_storage_file_url(optional($trash->image)->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
                @endif
              </td>
              <td>{{ $trash->name }}</td>
              <td>{{ $trash->model_number }}</td>
              <td>
                @foreach ($trash->categories as $category)
                  <span class="label label-outline">{{ $category->name }}</span>
                @endforeach
              </td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.catalog.product.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.catalog.product.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
  {{-- @endif --}}
@endsection
