@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.coupons') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Coupon::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.promotion.coupon.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_coupon') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Coupon::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.promotion.coupon.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.promotion.coupon.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.code') }}</th>
            <th>{{ trans('app.restricted') }}</th>
            <th>{{ trans('app.value') }}</th>
            <th>{{ trans('app.starting_time') }}</th>
            <th>{{ trans('app.ending_time') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($coupons as $coupon)
            <tr>
              @can('massDelete', \App\Models\Coupon::class)
                <td><input id="{{ $coupon->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                {{ $coupon->name }}
                @if ($coupon->ending_time < \Carbon\Carbon::now())
                  <span class="label label-default indent10">{{ strtoupper(trans('app.expired')) }}</span>
                @elseif(!$coupon->isActive())
                  <span class="label label-info indent10">{{ strtoupper(trans('app.inactive')) }}</span>
                @endif
              </td>
              <td>{{ $coupon->code }}</td>
              <td>{{ get_yes_or_no($coupon->customers_count || $coupon->promotion_zones_count) }}</td>
              <td>
                <strong>
                  {{ $coupon->type == 'amount'? get_formated_currency($coupon->value, 2): get_formated_decimal($coupon->value) . ' ' . trans('app.percent') }}
                </strong>
              </td>
              <td>
                {{ $coupon->starting_time ? $coupon->starting_time->toDayDateTimeString() : '' }}
              </td>
              <td>
                {{ $coupon->ending_time ? $coupon->ending_time->toDayDateTimeString() : '' }}
              </td>
              <td class="row-options">
                @can('view', $coupon)
                  <a href="javascript:void(0)" data-link="{{ route('admin.promotion.coupon.show', $coupon->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
                @endcan

                @can('update', $coupon)
                  <a href="javascript:void(0)" data-link="{{ route('admin.promotion.coupon.edit', $coupon->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $coupon)
                  {!! Form::open(['route' => ['admin.promotion.coupon.trash', $coupon->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Coupon::class)
          {!! Form::open(['route' => ['admin.promotion.coupon.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.code') }}</th>
            <th>{{ trans('app.value') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>{{ $trash->name }}</td>
              <td>
                {{ $trash->code }}
                @if ($trash->ending_time < \Carbon\Carbon::now())
                  ({{ trans('app.expired') }})
                @endif
              </td>
              <td>
                {{ $trash->type == 'amount'? get_formated_currency($trash->value, 2): get_formated_decimal($trash->value) . ' ' . trans('app.percent') }}
              </td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.promotion.coupon.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.promotion.coupon.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
