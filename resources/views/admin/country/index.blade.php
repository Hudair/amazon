@extends('admin.layouts.master')

@section('content')
  @include('admin.partials.notices.worldwide_business_area')

  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">
        {{ trans('app.countries') }}
        <i class="fa fa-question-circle indent10 small" data-toggle="tooltip" data-placement="right" title="{{ trans('help.active_business_zone') }}"></i>
      </h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Country::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.setting.country.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_country') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.flag') }}</th>
            <th>{{ trans('app.iso_code') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th class="text-center">{{ trans('app.number_of_states') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($countries as $country)
            <tr>
              <td>{!! get_flag_img_by_code($country->iso_code) !!}</td>
              <td>{{ $country->iso_code }}</td>
              <td>
                <a href="{{ route('admin.setting.country.states', $country->id) }}">
                  {{ $country->name }}
                </a>

                @if ($country->eea)
                  <span class="indent10 label label-outline" data-toggle="tooltip" data-placement="top" title="{{ trans('help.eea') }}">{{ trans('app.eea') }}</span>
                @endif

                @if ($country->active)
                  <span class="indent10 label label-primary pull-right">{{ trans('app.active') }}</span>
                @endif
              </td>
              <td class="text-center">{{ $country->states_count }}</td>
              <td class="row-options">
                <a href="{{ route('admin.setting.country.states', $country->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.state') }}" class="fa fa-plus"></i></a>&nbsp;

                @can('update', $country)
                  <a href="javascript:void(0)" data-link="{{ route('admin.setting.country.edit', $country->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
