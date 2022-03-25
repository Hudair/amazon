@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.languages') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Language::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.setting.language.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_language') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Language::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.setting.language.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.setting.language.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.language') }}</th>
            <th>{{ trans('app.code') }}</th>
            <th>{{ trans('app.php_locale_code') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($languages as $language)
            <tr>
              @can('massDelete', \App\Models\Language::class)
                <td><input id="{{ $language->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td width="45%">
                <img src="{{ asset(sys_image_path('flags') . array_slice(explode('_', $language->php_locale_code), -1)[0] . '.png') }}" class="lang-flag small" alt="{{ $language->code }}">
                <span class="indent10">{{ $language->language }}</span>
                @if ($language->rtl)
                  <span class="indent10 label label-outline">{{ trans('app.rtl') }}</span>
                @endif
                @if ($language->active)
                  <span class="indent10 label label-primary pull-right">{{ trans('app.active') }}</span>
                  <i class="fa fa-question-circle pull-right" data-toggle="tooltip" data-placement="top" title="{{ trans('help.new_language_info') }}"></i>
                @endif
              </td>
              <td>{!! $language->code !!}</td>
              <td>{!! $language->php_locale_code !!}</td>
              <td class="row-options text-muted small">
                @can('update', $language)
                  <a href="javascript:void(0)" data-link="{{ route('admin.setting.language.edit', $language) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan
                @can('delete', $language)
                  @if (in_array($language->id, config('system.freeze.languages')))
                    <i class="fa fa-bell-o text-muted" data-toggle="tooltip" data-placement="left" title="{{ trans('messages.freezed_model') }}"></i>
                  @else
                    {!! Form::open(['route' => ['admin.setting.language.trash', $language], 'method' => 'delete', 'class' => 'data-form']) !!}
                    {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.trash'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
                    {!! Form::close() !!}
                  @endif
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
        @can('massDelete', \App\Models\Language::class)
          {!! Form::open(['route' => ['admin.setting.language.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
            <th>{{ trans('app.language') }}</th>
            <th>{{ trans('app.code') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td width="45%">
                <img src="{{ asset(sys_image_path('flags') . array_slice(explode('_', $trash->php_locale_code), -1)[0] . '.png') }}" class="lang-flag small" alt="{{ $trash->code }}">
                <span class="indent10">{{ $trash->language }}</span>
                @if ($trash->rtl)
                  <span class="indent10 label label-primary pull-right">{{ trans('app.rtl') }}</span>
                @endif
              </td>
              <td>{!! $trash->code !!}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options text-muted small">
                @can('delete', $trash)
                  <a href="{{ route('admin.setting.language.restore', $trash) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;
                  {!! Form::open(['route' => ['admin.setting.language.destroy', $trash], 'method' => 'delete', 'class' => 'data-form']) !!}
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
