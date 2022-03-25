@extends('admin.layouts.master')

@section('content')
  <div class="row">
    <div class="col-md-2 nopadding-right">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('app.merchant') }}</h3>
        </div> <!-- /.box-header -->
        <div class="box-body">
          @if (Gate::allows('view', $dispute->shop))
            <a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $dispute->shop_id) }}" class="ajax-modal-btn small"><span class="lead"> {{ $dispute->shop->name }} </span></a>
          @else
            <span class="lead">{{ $dispute->shop->name }}</span>
          @endif

          <img src="{{ get_storage_file_url(optional($dispute->shop->image)->path, 'small') }}" class="thumbnail" alt="{{ trans('app.logo') }}">

          <p>
            {{ trans('app.total_disputes') }}:
            <span class="label label-outline">{{ \App\Helpers\Statistics::dispute_count($dispute->shop_id) }}</span>
          </p>
          <p>
            {{ trans('app.latest_days', ['days' => 30]) }}:
            <span class="label label-info"><strong>{{ \App\Helpers\Statistics::dispute_count($dispute->shop_id, 30) }}</strong></span>
          </p>

          @if ($dispute->shop->owner)
            <hr />
            <div class="form-group">
              <label>{{ trans('app.owner') }}</label>
              <p>
                <img src="{{ get_avatar_src($dispute->shop->owner, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                &nbsp;
                @if (Gate::allows('view', $dispute->shop->owner))
                  <a href="javascript:void(0)" data-link="{{ route('admin.admin.user.show', $dispute->shop->owner_id) }}" class="ajax-modal-btn small"><span class="lead">{{ $dispute->shop->owner->getName() }}</span></a>
                @else
                  <span class="small">{{ $dispute->shop->owner->getName() }}</span>
                @endif
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            {{ trans('app.dispute') }}
            <span class="indent10">{!! $dispute->statusName() !!}</span>
          </h3>
          <div class="box-tools pull-right">
            @can('view', $dispute->order)
              <a href="{{ route('admin.order.order.show', $dispute->order->id) }}" class="btn btn-default btn-flat">
                <i class="fa fa-shopping-cart"></i> {{ trans('app.order_details') }}
              </a>
            @endcan

            @unless($dispute->order->refunds->count())
              @can('initiate', \App\Models\Refund::class)
                <a href="javascript:void(0)" data-link="{{ route('admin.support.refund.form', $dispute->order->id) }}" class="ajax-modal-btn btn btn-new btn-flat">{{ trans('app.initiate_refund') }}</a>
              @endcan
            @endunless

            @can('response', $dispute)
              <a href="javascript:void(0)" data-link="{{ route('admin.support.dispute.response', $dispute) }}" class="ajax-modal-btn btn btn-info btn-flat"><i class="fa fa-reply"></i> {{ trans('app.response') }}</a>
            @endcan
          </div>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <span class="label label-outline">
            @can('view', $dispute->order)
              <a href="{{ route('admin.order.order.show', $dispute->order->id) }}">
                {{ trans('app.order_number') . ': ' }}{{ $dispute->order->order_number }}
              </a>
            @else
              {{ trans('app.order_number') . ': ' }}{{ $dispute->order->order_number }}
            @endcan
          </span>
          <p class="lead">{{ $dispute->dispute_type->detail }}</p>

          @if (count($dispute->attachments))
            {{ trans('app.attachments') . ': ' }}
            @foreach ($dispute->attachments as $attachment)
              <a href="{{ route('attachment.download', $attachment) }}"><i class="fa fa-file"></i></a>
            @endforeach
          @endif

          @if ($dispute->description)
            <div class="well">
              {!! $dispute->description !!}
            </div>
          @endif

          @if ($dispute->replies->count() > 0)
            <fieldset>
              <legend>{{ strtoupper(trans('app.conversations')) }}</legend>
            </fieldset>

            @foreach ($dispute->replies as $reply)
              @include('admin.partials._reply_conversations')
            @endforeach
          @endif
        </div> <!-- /.box-body -->
      </div> <!-- /.box -->

      @if ($dispute->order->refunds->count())
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">{{ trans('app.refunds') }}</h3>
            <div class="box-tools pull-right"></div>
          </div>
          <div class="box-body">
            <table class="table">
              <thead>
                <tr>
                  <th>{{ trans('app.refund_amount') }}</th>
                  <th>{{ trans('app.status') }}</th>
                  <th>{{ trans('app.created_at') }}</th>
                  <th>{{ trans('app.updated_at') }}</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($dispute->order->refunds as $refund)
                  <tr>
                    <td>{{ get_formated_currency($refund->amount, 2) }}</td>
                    <td>{!! $refund->statusName() !!}</td>
                    <td>{{ $refund->created_at->diffForHumans() }}</td>
                    <td>{{ $refund->updated_at->diffForHumans() }}</td>
                    <td>
                      @if ($refund->isOpen())
                        @can('approve', $refund)
                          <a href="javascript:void(0)" data-link="{{ route('admin.support.refund.response', $refund) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.response') }}" class="fa fa-random"></i></a>&nbsp;
                        @endcan
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif

      @include('admin.partials._activity_logs', ['logger' => $dispute])
    </div>

    <div class="col-md-3 nopadding-left">
      @if ($dispute->product_id)
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"> {{ trans('app.product') }}</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div> <!-- /.box-header -->
          <div class="box-body">
            <div class="form-group">
              <img src="{{ get_storage_file_url(optional($dispute->product->image)->path, 'medium') }}" class="thumbnail" width="100%" alt="{{ trans('app.image') }}">

              @if (Gate::allows('view', $dispute->product))
                <a href="javascript:void(0)" data-link="{{ route('admin.catalog.product.show', $dispute->product_id) }}" class="ajax-modal-btn"><span>{{ $dispute->product->name }}</span></a>
              @else
                <span>{{ $dispute->product->name }}</span>
              @endif
            </div>
          </div>
        </div>
      @endif

      @if ($dispute->refund_amount)
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">{{ trans('app.refund_requested') }}</h3>
          </div>
          <div class="box-body">
            <p>
              <strong>{{ trans('app.amount') }}: </strong> {{ get_formated_currency($dispute->refund_amount, 2) }}
            </p>
          </div>
        </div>
      @endif

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> {{ trans('app.customer') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div> <!-- /.box-header -->
        <div class="box-body">
          <img src="{{ get_avatar_src($dispute->customer, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">

          @if (Gate::allows('view', $dispute->customer))
            <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.show', $dispute->customer_id) }}" class="ajax-modal-btn small"><span class="lead indent10">{{ $dispute->customer->getName() }}</span></a>
          @else
            <span class="lead indent10">{{ $dispute->customer->getName() }}</span>
          @endif

          <p>
            {{ trans('app.total_disputes') }}:
            <span class="label label-outline">{{ \App\Helpers\Statistics::disputes_by_customer_count($dispute->customer_id) }}</span>
          </p>

          <p>
            {{ trans('app.latest_days', ['days' => 30]) }}:
            <span class="label label-info"><strong>{{ \App\Helpers\Statistics::disputes_by_customer_count($dispute->customer_id, 30) }}</strong></span>
          </p>
          <hr />
          <div class="form-group text-muted">
            <p>
              <label>{{ trans('app.created_at') }}</label>
              {{ $dispute->created_at->diffForHumans() }}
            </p>
            <p>
              <label>{{ trans('app.updated_at') }}</label>
              {{ $dispute->updated_at->diffForHumans() }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
