@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.subscription_plans') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\SubscriptionPlan::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.setting.subscriptionPlan.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_subscription_plan') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-option" id="sortable" data-action="{{ Route('admin.setting.subscriptionPlan.reorder') }}">
        <thead>
          <tr>
            @can('massDelete', \App\Models\SubscriptionPlan::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.setting.subscriptionPlan.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.setting.subscriptionPlan.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th width="7px">{{ trans('app.#') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th><i class="fa fa-money"></i> {{ trans('app.cost_per_month') }}</th>
            <th><i class="fa fa-users"></i> {{ trans('app.team_size') }}</th>
            <th><i class="fa fa-cubes"></i> {{ trans('app.inventory_limit') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($subscription_plans as $subscriptionPlan)
            <tr id="{{ $subscriptionPlan->plan_id }}">
              @can('massDelete', \App\Models\SubscriptionPlan::class)
                <td>
                  @if ($subscriptionPlan->shops_count)
                    <span class="text-muted">
                      <i class="fa fa-ban" data-toggle="tooltip" data-placement="right" title="{{ trans('help.this_plan_has_active_subscribers') }}"></i>
                    </span>
                  @else
                    <input id="{{ $subscriptionPlan->plan_id }}" type="checkbox" class="massCheck">
                  @endif
                </td>
              @endcan
              <td>
                <i data-toggle="tooltip" data-placement="top" title="{{ trans('app.move') }}" class="fa fa-arrows sort-handler"> </i>
              </td>
              <td>
                {{ $subscriptionPlan->name }}
                @if ($subscriptionPlan->featured)
                  <span class="label label-primary indent10">{{ trans('app.featured') }}</span>
                @endif

                <span class="label label-outline pull-right" data-toggle="tooltip" data-placement="top" title="{{ trans('help.subscribers_count') }}">
                  {{ $subscriptionPlan->shops_count }}
                </span>
              </td>
              <td>{{ get_formated_currency($subscriptionPlan->cost, 2) }}</td>
              <td>{{ $subscriptionPlan->team_size }}</td>
              <td>{{ $subscriptionPlan->inventory_limit }}</td>
              <td class="row-options">
                @can('view', $subscriptionPlan)
                  <a href="javascript:void(0)" data-link="{{ route('admin.setting.subscriptionPlan.show', $subscriptionPlan->plan_id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;
                @endcan

                @can('update', $subscriptionPlan)
                  <a href="javascript:void(0)" data-link="{{ route('admin.setting.subscriptionPlan.edit', $subscriptionPlan->plan_id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                @endcan

                @can('delete', $subscriptionPlan)
                  @if ($subscriptionPlan->shops_count)
                    <span class="text-muted">
                      <i class="fa fa-trash-o" data-toggle="tooltip" data-placement="left" title="{{ trans('help.this_plan_has_active_subscribers') }}"></i>
                    </span>
                  @else
                    {!! Form::open(['route' => ['admin.setting.subscriptionPlan.trash', $subscriptionPlan->plan_id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\SubscriptionPlan::class)
          {!! Form::open(['route' => ['admin.setting.subscriptionPlan.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
      <table class="table table-hover table-no-option">
        <thead>
          <tr>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.cost_per_month') }}</th>
            <th>{{ trans('app.team_size') }}</th>
            <th>{{ trans('app.inventory_limit') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>{{ $trash->name }}</td>
              <td>{{ get_formated_currency($trash->cost, 2) }}</td>
              <td>{{ $trash->team_size }}</td>
              <td>{{ $trash->inventory_limit }}</td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.setting.subscriptionPlan.restore', $trash->plan_id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.setting.subscriptionPlan.destroy', $trash->plan_id], 'method' => 'delete', 'class' => 'data-form']) !!}
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

@section('page-script')
  @include('plugins.drag-n-drop')
@endsection
