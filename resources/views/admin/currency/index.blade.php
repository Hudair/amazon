@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.currencies') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Currency::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.setting.currency.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_currency') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Currency::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.setting.currency.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.iso_code') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.symbol') }}</th>
            <th>{{ trans('app.subunit') }}</th>
            <th>{{ trans('app.decimal_mark') }}</th>
            <th>{{ trans('app.thousands_separator') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($currencies as $currency)
            <tr>
              @can('massDelete', \App\Models\Currency::class)
                <td><input id="{{ $currency->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>{{ $currency->iso_code }}</td>
              <td>
                {{ $currency->name }}
                @if ($currency->active)
                  <span class="indent10 label label-primary pull-right">{{ trans('app.active') }}</span>
                  {{-- <i class="fa fa-question-circle pull-right" data-toggle="tooltip" data-placement="top" title="{{ trans('help.new_language_info') }}"></i> --}}
                @endif
              </td>
              <td>{{ $currency->symbol }}</td>
              <td>{{ $currency->subunit }}</td>
              <td>
                <span class="label label-default">{{ $currency->decimal_mark }}</span>
              </td>
              <td>
                <span class="label label-default">{{ $currency->thousands_separator }}</span>
              </td>
              <td class="row-options">
                @can('update', $currency)
                  <a href="javascript:void(0)" data-link="{{ route('admin.setting.currency.edit', $currency->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $currency)
                  {!! Form::open(['route' => ['admin.setting.currency.destroy', $currency->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
