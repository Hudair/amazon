@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.banners') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Banner::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.appearance.banner.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_banner') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Banner::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.appearance.banner.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.detail') }}</th>
            <th>{{ trans('app.banner_image') }}</th>
            <th>{{ trans('app.options') }}</th>
            <th>{{ trans('app.created_at') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($banners as $banner)
            <tr>
              @can('massDelete', \App\Models\Banner::class)
                <td><input id="{{ $banner->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <strong>{!! $banner->title !!} </strong>
                @unless($banner->group)
                  <span class="label label-default indent10">{{ strtoupper(trans('app.draft')) }}</span>
                @endunless
                <br />
                <span class="small">{!! $banner->description !!}</span>
              </td>
              <td>
                <img src="{{ get_storage_file_url(optional($banner->featureImage)->path, 'cover_thumb') }}" class="thumbnail" alt="{{ trans('app.banner_image') }}">
              </td>
              <td>
                {{ trans('app.group') . ': ' }}
                <strong>
                  @if ($banner->group)
                    {!! $banner->group->name !!}
                  @else
                    {!! trans('app.unspecified') !!}
                  @endif
                </strong>
                <br />
                {{ trans('app.columns') . ': ' }}<strong>{!! $banner->columns !!}</strong>
                <br />
                {{ trans('app.order') . ': ' }}<strong>{!! $banner->order !!}</strong>
                <br />
                {{ trans('app.link_label') . ': ' }}<strong>{!! $banner->link_label !!}</strong>
                <br />
                {{ trans('app.link') . ': ' }}<strong>{!! $banner->link !!}</strong>
              </td>
              <td>
                {{ $banner->created_at->toFormattedDateString() }}
              </td>
              <td class="row-options text-muted small">
                @can('update', $banner)
                  <a href="javascript:void(0)" data-link="{{ route('admin.appearance.banner.edit', $banner->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan
                @can('delete', $banner)
                  {!! Form::open(['route' => ['admin.appearance.banner.destroy', $banner->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
@endsection
