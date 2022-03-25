@extends('admin.layouts.master')

@section('content')
  <div class="alert alert-default alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p class="strong"><i class="icon fa fa-question-circle"></i>{{ trans('app.how_it_works') }}:</p>
    {!! trans('dynamicCommission::lang.how_it_works') !!}
  </div>

  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('dynamicCommission::lang.dynamic_commissions') }}</h3>
      <div class="box-tools pull-right">
      </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <div class="clearfix spacer30"></div>

      <div class="col-md-8">
        {!! Form::open(['route' => ['dynamicCommission.routes.update'], 'class' => '', 'id' => 'form', 'data-toggle' => '	validator']) !!}

        @php
          $commissions = get_from_option_table('dynamicCommission_milestones', []);
          $reset_on_payout = get_from_option_table('dynamicCommission_reset_on_payout', 0);
        @endphp

        <!-- Important to keep this -->
        @unless(empty($commissions))
          {!! Form::hidden('milestones[amounts][0]', 0, ['required']) !!}
        @endunless

        @foreach ($commissions as $key => $commission)
          <div class="row" id="milestone{{ $key }}">
            <div class="col-sm-6">
              <div class="form-group">
                @if ($loop->first)
                  {!! Form::label('amounts', trans('dynamicCommission::lang.when_sold') . ': ', ['class' => 'control-label']) !!}
                @endif
                <div class="input-group">
                  <span class="input-group-addon">{{ config('system_settings.currency_symbol', '$') }}</span>
                  {!! Form::number('milestones[amounts][]', $commission['milestone'], ['min' => 0, 'steps' => 'any', 'class' => 'form-control', 'placeholder' => trans('dynamicCommission::lang.milestone_amount'), $commission['milestone'] == 0 ? 'disabled' : '', 'required']) !!}
                  <span class="input-group-addon">{{ trans('dynamicCommission::lang.and_up') }}</span>
                </div>
                <div class="help-block with-errors"></div>
              </div>
            </div> <!-- /.col-sm-5 -->

            <div class="col-sm-4 nopadding-left">
              <div class="form-group">
                @if ($loop->first)
                  {!! Form::label('amounts', trans('dynamicCommission::lang.commission_rate') . ': ', ['class' => 'control-label']) !!}
                @endif
                <div class="input-group">
                  {!! Form::number('milestones[commissions][]', $commission['commission'], ['min' => 0, 'steps' => 'any', 'class' => 'form-control', 'placeholder' => trans('dynamicCommission::lang.commission'), 'required']) !!}
                  <span class="input-group-addon">{{ trans('app.percent') }}</span>
                </div>
                <div class="help-block with-errors"></div>
              </div>
            </div> <!-- /.col-sm-5 -->

            <div class="col-sm-2 nopadding-left">
              <div class="form-group">
                @if ($loop->first)
                  <label class="control-label">&nbsp;</label>
                @endif
                @unless($commission['milestone'] == 0)
                  <button type="button" onclick="removeMilestone('milestone{{ $key }}')" class="btn btn-danger"><i class="fa fa-times"></i></button>
                @endunless
              </div>
            </div>
          </div> <!-- /.row -->
        @endforeach

        {{-- virtual_milestones --}}
        <div id="virtual_milestones"></div>

        <div class="clearfix spacer20"></div>
        <div class="row">
          <div class="col-sm-5">
            <button type="button" id="addMilestone" class="btn btn-lg btn-default">
              <i class="fa fa-plus"></i>
              {{ trans('dynamicCommission::lang.add_more_milestone') }}
            </button>
          </div>

          <div class="col-sm-5">
            {!! Form::submit(trans('app.update'), ['class' => 'btn btn-lg btn-flat btn-new pull-right']) !!}
          </div>
          <div class="col-sm-2"></div>
        </div>
        {!! Form::close() !!}
      </div><!-- /.col-md-8 -->

      <div class="col-md-4 nopadding-left">
        <div class="row">
          {{-- <div class="col-sm-7 text-right">
            <div class="form-group">
              {!! Form::label('dynamicCommission_reset_on_payout', trans('dynamicCommission::lang.sold_amount_reset_on_payout') . ':', ['class' => 'with-help control-label']) !!}
              <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="{{ trans('dynamicCommission::lang.sold_amount_reset_on_payout_help_text') }}"></i>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="handle horizontal text-center">
              <a href="javascript:void(0)" data-link="{{ route('admin.package.config.toggle', ['option' => 'dynamicCommission_reset_on_payout']) }}" type="button" class="btn btn-md btn-secondary btn-toggle {{ $reset_on_payout ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $reset_on_payout ? 'true' : 'false' }}" autocomplete="off" {{ is_subscription_enabled() ? '' : 'disabled' }}>
                <div class="btn-handle"></div>
              </a>
            </div>
          </div> --}}
        </div> <!-- /.row -->
      </div><!-- /.col-md-8 -->
    </div> <!-- /.box-body -->
    <div class="clearfix spacer30"></div>
  </div> <!-- /.box -->
@endsection

@section('page-script')
  @include('dynamicCommission::scripts.dynamic_fields')
@endsection
