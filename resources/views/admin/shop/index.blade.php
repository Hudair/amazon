@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.shops') }}</h3>
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
            @can('massDelete', \App\Models\Shop::class)
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
                    <li>
                      <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.massTrash') }}" class="massAction " data-doafter="reload"><i class="fa fa-trash"></i> {{ trans('app.trash') }}</a>
                    </li>
                    <li>
                      <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.massDestroy') }}" class="massAction " data-doafter="reload"><i class="fa fa-times"></i> {{ trans('app.delete_permanently') }}</a>
                    </li>
                  </ul>
                </div>
              </th>
            @endcan
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.shop_name') }}</th>
            @if (is_subscription_enabled())
              <th>{{ trans('app.current_billing_plan') }}</th>
            @endif
            {{-- @if (is_incevio_package_loaded('wallet'))
              <th>{{ trans('wallet::lang.balance') }}</th>
            @endif --}}
            @if (is_incevio_package_loaded('dynamicCommission'))
              <th>{{ trans('dynamicCommission::lang.periodic_sold_amount') }}</th>
            @endif
            <th>{{ trans('app.owner') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody id="massSelectArea">
          @foreach ($shops as $shop)
            <tr class="{{ !$shop->active ? 'inactive' : '' }}">
              @can('massDelete', \App\Models\Shop::class)
                <td><input id="{{ $shop->id }}" type="checkbox" class="massCheck"></td>
              @endcan
              <td>
                <img src="{{ get_storage_file_url(optional($shop->logoImage)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
              </td>
              <td>
                {{ $shop->name }}

                @if ($shop->isVerified())
                  <img src="{{ get_verified_badge() }}" class="verified-badge img-xs" data-toggle="tooltip" data-placement="top" title="{{ trans('help.verified_seller') }}" alt="verified-badge">
                @endif

                @if ($shop->isDown())
                  <span class="label label-default indent10">{{ trans('app.maintenance_mode') }}</span>
                @endif

                @can('update', $shop)
                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.toggle', $shop) }}" data-doafter="reload" type="button" class="toggle-widget toggle-confirm pull-right">
                    <i class="fa fa-{{ $shop->active ? 'heart-o' : 'heart' }}" data-toggle="tooltip" data-placement="top" title="{{ $shop->active ? trans('app.deactivate') : trans('app.activate') }}"></i>
                  </a>
                @endcan
              </td>

              @if (is_subscription_enabled())
                <td>
                  {{ $shop->plan->name }}

                  @if ($shop->onTrial())
                    <span class="label label-info indent10">{{ trans('app.trialing') }}</span>
                  @elseif($shop->hasExpiredPlan())
                    <span class="label label-default indent10">{{ trans('app.expired') }}</span>
                  @endif

                  @if ($shop->onTrial() || $shop->hasExpiredPlan())
                    @if (Auth::user()->isAdmin())
                      <a href="javascript:void(0)" data-link="{{ route('admin.vendor.subscription.editTrial', $shop) }}" class="ajax-modal-btn pull-right"><i data-toggle="tooltip" data-placement="top" title="{{ trans('help.update_trial_period') }}" class="fa fa-calendar"></i> </a>
                    @endif
                  @endif
                </td>
              @endif

              {{-- @if (is_incevio_package_loaded('wallet'))
                <td>{{ get_formated_currency($shop->balance) }}</td>
              @endif --}}

              @if (is_incevio_package_loaded('dynamicCommission'))
                <td>{{ get_formated_currency($shop->periodic_sold_amount) }}</td>
              @endif

              <td>
                <img src="{{ get_avatar_src($shop->owner, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">

                <p class="indent10">
                  @can('view', $shop->owner)
                    <a href="javascript:void(0)" data-link="{{ route('admin.vendor.merchant.show', $shop->owner_id) }}" class="ajax-modal-btn">{{ $shop->owner->getName() }}</a>
                  @else
                    {{ $shop->owner->getName() }}
                  @endcan

                  @unless($shop->owner->active)
                    <span class="label label-default indent10">{{ trans('app.inactive') }}</span>
                  @endunless
                </p>
              </td>

              <td class="row-options">
                @can('view', $shop)
                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $shop->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;

                  <a href="{{ route('admin.vendor.shop.staffs', $shop->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.staffs') }}" class="fa fa-users"></i></a>&nbsp;
                @endcan

                @can('secretLogin', $shop->owner)
                  <a href="{{ route('admin.user.secretLogin', $shop->owner->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.secret_login_merchant') }}" class="fa fa-user-secret"></i></a>&nbsp;
                @endcan

                @can('update', $shop)
                  <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.edit', $shop->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}" class="fa fa-edit"></i></a>&nbsp;
                  @if ($shop->primaryAddress)
                    <a href="javascript:void(0)" data-link="{{ route('address.edit', $shop->primaryAddress->id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.update_address') }}" class="fa fa-map-marker"></i></a>&nbsp;
                  @else
                    <a href="javascript:void(0)" data-link="{{ route('address.create', ['shop', $shop->id]) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.add_address') }}" class="fa fa-plus-square-o"></i></a>&nbsp;
                  @endif
                @endcan

                @can('delete', $shop)
                  {!! Form::open(['route' => ['admin.vendor.shop.trash', $shop->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
        @can('massDelete', \App\Models\Shop::class)
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
            <th>{{ trans('app.image') }}</th>
            <th>{{ trans('app.name') }}</th>
            <th>{{ trans('app.email') }}</th>
            <th>{{ trans('app.owner') }}</th>
            <th>{{ trans('app.deleted_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trashes as $trash)
            <tr>
              <td>
                <img src="{{ get_storage_file_url(optional($trash->logo)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
              </td>
              <td>{{ $trash->name }}</td>
              <td>{{ $trash->email }}</td>
              <td>
                <img src="{{ get_avatar_src($trash->owner, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                <p class="indent10">{{ $trash->owner->getName() }}</p>
              </td>
              <td>{{ $trash->deleted_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('delete', $trash)
                  <a href="{{ route('admin.vendor.shop.restore', $trash->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.restore') }}" class="fa fa-database"></i></a>&nbsp;

                  {!! Form::open(['route' => ['admin.vendor.shop.destroy', $trash->id], 'method' => 'delete', 'class' => 'data-form']) !!}
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
