@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.merchants') }}</h3>
      <div class="box-tools pull-right">
        @can('create', \App\Models\Merchant::class)
          <a href="javascript:void(0)" data-link="{{ route('admin.vendor.merchant.create') }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.add_merchant') }}</a>
        @endcan
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-2nd-no-sort">
        <thead>
          <tr>
            @can('massDelete', \App\Models\Merchant::class)
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
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a></li>
                    <li><a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a></li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.avatar') }}</th>
            <th>{{ trans('app.nice_name') }}</th>
            <th>{{ trans('app.full_name') }}</th>
            <th>{{ trans('app.shop') }}</th>
            @if (is_subscription_enabled())
              <th>{{ trans('app.current_billing_plan') }}</th>
            @endif
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($merchants as $merchant)
            <tr>
              @can('massDelete', \App\Models\Merchant::class)
                <td><input id="{{ $merchant->owns->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_avatar_src($merchant, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
              </td>
              <td>
                {{ $merchant->nice_name }}

                @unless($merchant->active)
                  <span class="label label-default indent10">{{ trans('app.inactive') }}</span>
                @endunless
              </td>
              <td>{{ $merchant->name }}</td>
              <td>
                @if ($merchant->owns->name)
                  <img src="{{ get_storage_file_url(optional($merchant->owns->logoImage)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
                  <p class="indent10">
                    <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $merchant->owns->id) }}" class="ajax-modal-btn">
                      {{ $merchant->owns->name }}
                    </a>
                  </p>

                  @if ($merchant->owns->deleted_at)
                    <span class="label label-default indent10">
                      <i class="fa fa-trash-o small"></i> {{ trans('app.in_trash') }}
                    </span>
                  @endif

                  @if ($merchant->owns->isDown())
                    <span class="label label-default indent10">{{ trans('app.maintenance_mode') }}</span>
                  @elseif(!$merchant->owns->active)
                    <span class="label label-default indent10">{{ trans('app.inactive') }}</span>
                  @endif
                @endif
              </td>

              @if (is_subscription_enabled())
                <td>
                  {{ optional($merchant->owns)->plan->name }}

                  @if ($merchant->owns->onTrial())
                    <span class="label label-info indent10">{{ trans('app.trialing') }}</span>
                  @endif
                </td>
              @endif

              <td class="row-options">
                @can('view', $merchant)
                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.merchant.show', $merchant->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.profile') }}" class="fa fa-user-circle-o"></i></a>&nbsp;
                @endcan

                @can('secretLogin', $merchant)
                  <a href="{{ route('admin.user.secretLogin', $merchant) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.secret_login_user') }}" class="fa fa-user-secret"></i></a>&nbsp;
                @endcan

                @can('update', $merchant)
                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.merchant.edit', $merchant->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;

                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.merchant.changePassword', $merchant->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.change_password') }}" class="fa fa-lock"></i></a>&nbsp;

                  @if ($merchant->primaryAddress)
                    <a href="javascript:void(0)" data-link="{{ route('address.edit', $merchant->primaryAddress->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.update_address') }}" class="fa fa-map-marker"></i></a>&nbsp;
                  @else
                    <a href="javascript:void(0)" data-link="{{ route('address.create', ['user', $merchant->id]) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.add_address') }}" class="fa fa-plus-square-o"></i></a>&nbsp;
                  @endif
                @endcan

                @can('delete', $merchant)
                  {!! Form::open(['route' => ['admin.vendor.shop.trash', $merchant->owns->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Merchant::class)
          {!! Form::open(['route' => ['admin.vendor.shop.emptyTrash'], 'method' => 'delete', 'class' => 'data-form']) !!}
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
            <th>{{ trans('app.avatar') }}</th>
            <th>{{ trans('app.nice_name') }}</th>
            <th>{{ trans('app.full_name') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.shop') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <img src="{{ get_avatar_src($trash, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
              </td>
              <td>{{ $trash->nice_name }}</td>
              <td>{{ $trash->name }}</td>
              <td>{{ $trash->email }}</td>
              <td>
                @if ($trash->owns)
                  <img src="{{ get_storage_file_url(optional($trash->owns->image)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
                  <p class="indent10">{{ $trash->owns->name }}</p>
                @endif

                @if ($trash->owns->deleted_at)
                  <span class="label label-default indent10">
                    <i class="fa fa-trash-o small"></i> {{ trans('app.in_trash') }}
                  </span>
                @endif
              </td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.vendor.shop.restore', $trash->owns->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.vendor.shop.destroy', $trash->owns->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
