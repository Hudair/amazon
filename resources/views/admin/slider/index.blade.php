@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.sliders') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Slider::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.appearance.slider.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_slider') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Slider::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.appearance.slider.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.mobile_slider') }}</th>
            <th>{{ trans('app.detail') }}</th>
            <th>{{ trans('app.slider') }}</th>
            <th>{{ trans('app.options') }}</th>
            <th>{{ trans('app.created_at') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($sliders as $slider)
            <tr>
              @can('massDelete', \App\Models\Slider::class)
                <td><input id="{{ $slider->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($slider->mobileImage)->path, 'cover_thumb') }}" class="" height="50%" alt="{{ trans('app.image') }}">
              </td>
              <td>
                <strong style="color: {{ $slider->title_color }}">{!! $slider->title !!} </strong>
                <br />
                <small style="color: {{ $slider->sub_title_color }}">{!! $slider->sub_title !!}</small>
              </td>
              <td>
                <img src="{{ get_storage_file_url(optional($slider->featureImage)->path, 'cover_thumb') }}" class="thumbnail" alt="{{ trans('app.slider_image') }}">
              </td>
              <td>
                {{ trans('app.title_color') . ': ' }}<strong>{!! $slider->title_color !!}</strong>
                <br />
                {{ trans('app.sub_title_color') . ': ' }}<strong>{!! $slider->sub_title_color !!}</strong>
                <br />
                {{ trans('app.alternative_color') . ': ' }}<strong>{!! $slider->alt_color !!}</strong>
                <br />
                {{ trans('app.order') . ': ' }}<strong>{!! $slider->order !!}</strong>
                <br />
                {{ trans('app.link') . ': ' }}<strong>{!! $slider->link !!}</strong>
              </td>
              <td>
                {{ $slider->created_at->toFormattedDateString() }}
              </td>
              <td class="row-options text-muted small">
                @can('update', $slider)
                  <a href="javascript:void(0)" data-link="{{ route('admin.appearance.slider.edit', $slider->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan
                @can('delete', $slider)
                  {!! Form::open(['route' => ['admin.appearance.slider.destroy', $slider->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
