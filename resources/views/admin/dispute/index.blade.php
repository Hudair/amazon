@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.disputes') }}</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.customer') }}</th>
            <th>{{ trans('app.type') }}</th>
            <th>{{ trans('app.refund_requested') }}</th>
            <th>{{ trans('app.response') }}</th>
            <th>{{ trans('app.updated_at') }}</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($disputes as $dispute)
            <tr>
              <td>
                <img src="{{ get_avatar_src($dispute->customer, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                <p class="indent10">
                  <strong>{{ $dispute->customer->getName() }}</strong>
                  @if (Auth::user()->isFromPlatform() && $dispute->shop)
                    <br /><span>{{ trans('app.vendor') . ': ' . optional($dispute->shop)->name }}</span>
                  @endif
                </p>
              </td>
              <td>
                @if (!Auth::user()->isFromPlatform())
                  {!! $dispute->statusName() !!}
                @endif

                <a href="{{ route('admin.support.dispute.show', $dispute->id) }}">{{ $dispute->dispute_type->detail }}</a>
              </td>
              <td>
                {{ get_formated_currency($dispute->refund_amount, 2) }}
              </td>
              <td><span class="label label-default">{{ $dispute->replies_count }}</span></td>
              <td>{{ $dispute->updated_at->diffForHumans() }}</td>
              <td class="row-options">
                <a href="{{ route('admin.support.dispute.show', $dispute->id) }}"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}" class="fa fa-expand"></i></a>&nbsp;

                @can('response', $dispute)
                  <a href="javascript:void(0)" data-link="{{ route('admin.support.dispute.response', $dispute) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.response') }}" class="fa fa-reply"></i></a>&nbsp;
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
      <h3 class="box-title">{{ trans('app.closed_disputes') }}</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-hover table-no-sort">
        <thead>
          <tr>
            <th>{{ trans('app.customer') }}</th>
            <th>{{ trans('app.type') }}</th>
            <th>{{ trans('app.response') }}</th>
            <th>{{ trans('app.updated_at') }}</th>
            <th>{{ trans('app.option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($closed as $dispute)
            <tr>
              <td>
                <img src="{{ get_avatar_src($dispute->customer, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                <p class="indent10">
                  <strong>{{ $dispute->customer->getName() }}</strong>
                  @if (Auth::user()->isFromPlatform() && $dispute->shop)
                    <br /><span>{{ trans('app.vendor') . ': ' . optional($dispute->shop)->name }}</span>
                  @endif
                </p>
              </td>
              <td>
                @if (!Auth::user()->isFromPlatform())
                  {!! $dispute->statusName() !!}
                @endif
                <a href="{{ route('admin.support.dispute.show', $dispute->id) }}">{{ $dispute->dispute_type->detail }}</a>
              </td>
              <td><span class="label label-default">{{ $dispute->replies_count }}</span></td>
              <td>{{ $dispute->updated_at->diffForHumans() }}</td>
              <td class="row-options">
                @can('response', $dispute)
                  <a href="javascript:void(0)" data-link="{{ route('admin.support.dispute.response', $dispute) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="{{ trans('app.response') }}" class="fa fa-reply"></i></a>&nbsp;
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
