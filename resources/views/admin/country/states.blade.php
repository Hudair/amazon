@extends('admin.layouts.master')

@section('content')
  @include('admin.partials.notices.worldwide_business_area')

  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">
        {{ trans('app.states') . ': ' . $country->name }}
        <i class="fa fa-question-circle indent10 small" data-toggle="tooltip" data-placement="right" title="{{ trans('help.active_business_zone') }}"></i>
      </h3>
      <div class="box-tools pull-right">
        @can('update', $country)
          <a href="javascript:void(0)" data-link="{{ route('admin.setting.state.create', $country->id) }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_state') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            {{-- @can('massDelete', \App\Models\Country::class) --}}
            {{-- <th class="massActionWrapper"> --}}
            <!-- Check all button -->
            {{-- <div class="btn-group ">
									<button type="button" class="btn btn-xs btn-default checkbox-toggle">
										<i class="fa fa-square-o" data-toggle="tooltip" data-placement="top" title="{{ trans('app.select_all') }}"></i>
									</button>
									<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">{{ trans('app.toggle_dropdown') }}</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="javascript:void(0)" data-link="{{ route('admin.setting.country.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
									</ul>
								</div> --}}
            {{-- </th> --}}
            {{-- @endcan --}}
            <th>{{ trans('app.iso_code') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($country->states as $state)
            <tr>
              {{-- @can('massDelete', \App\Models\Country::class)
							<td><input id="{{ $state->id }}" type="checkbox" class="massCheck"></td>
					  	@endcan --}}
              <td>{{ $state->iso_code }}</td>
              <td>
                {{ $state->name }}

                @if ($state->active)
                  <span class="indent10 label label-primary pull-right">{{ trans('app.active') }}</span>
                @endif
              </td>
              <td class="row-options">
                @can('update', $country)
                  <a href="javascript:void(0)" data-link="{{ route('admin.setting.state.edit', $state->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $country)
                  {!! Form::open(['route' => ['admin.setting.state.destroy', $state->id], 'method' => 'delete', 'class' => 'data-form']) !!}
                  {!! Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'confirm ajax-silent', 'title' => trans('app.delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']) !!}
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
