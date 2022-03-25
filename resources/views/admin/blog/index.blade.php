@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.blogs') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Blog::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.utility.blog.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_blog') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Blog::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.utility.blog.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.utility.blog.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.blog_title') }}</th>
            <th>{{ trans('app.author') }}</th>
            <th><i class="fa fa-comments"></i></th>
            <th>{{ trans('app.date') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($blogs as $blog)
            <tr>
              @can('massDelete', \App\Models\Blog::class)
                <td><input id="{{ $blog->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($blog->coverImage)->path, 'tiny') }}" class="img-sm" alt="{!! $blog->title !!}">
              </td>
              <td width="60%">
                @can('update', $blog)
                  <a href="javascript:void(0)" data-link="{{ route('admin.utility.blog.edit', $blog->id) }}" class="ajax-modal-btn"><strong>{!! $blog->title !!}</strong></a>
                @else
                  <strong>{!! $blog->title !!}</strong>
                @endcan
                <br />
                <span class="excerpt-td">{!! $blog->excerpt !!}</span>
                @if (!$blog->status)
                  <br /><span class="label label-default">{{ strtoupper(trans('app.draft')) }}</span>
                @endif
              </td>
              <td>{{ $blog->author ? $blog->author->getName() : '' }}</td>
              <td>{{ $blog->comments_count }}</td>
              <td class="small">
                @if ($blog->status)
                  {{ trans('app.published_at') }}<br />
                  {{ optional($blog->published_at)->toFormattedDateString() }}
                @else
                  {{ trans('app.updated_at') }}<br />
                  {{ $blog->updated_at->toFormattedDateString() }}
                @endif
              </td>
              <td class="row-options text-muted small">
                @can('update', $blog)
                  <a href="javascript:void(0)" data-link="{{ route('admin.utility.blog.edit', $blog->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan
                @can('delete', $blog)
                  {!! Form::open(['route' => ['admin.utility.blog.trash', $blog->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Blog::class)
          {!! Form::open(['route' => ['admin.utility.blog.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
            <th>{{ trans('app.blog_title') }}</th>
            <th>{{ trans('app.author') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td width="65%">
                <strong>{!! $trash->title !!}</strong>
                <span class="excerpt-td">{!! $trash->excerpt !!}</span>
              </td>
              <td>{{ $trash->author->getName() }}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options small">
                @can('delete', $trash)
                  <a href="{{ route('admin.utility.blog.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;
                  {!! Form::open(['route' => ['admin.utility.blog.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
