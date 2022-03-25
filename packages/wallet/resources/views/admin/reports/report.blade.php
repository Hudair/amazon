@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2 nopadding-right">
                            <div class="form-group">
                                <label>{{ trans('wallet::lang.type') }}</label>
                                <select id="payoutType" onchange="fireEventOnFilter(this.value)" class="form-control" name="payout_type">
                                    <option value="" @if(request()->get('payout_type') == "") selected @endif >{{trans('app.all')}}</option>
                                    <option value="deposit" @if(request()->get('payout_type') == "deposit") selected @endif >{{trans('wallet::lang.deposit')}}</option>
                                    <option value="withdraw" @if(request()->get('payout_type') == "withdraw") selected @endif >{{trans('wallet::lang.withdraw')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 nopadding-right">
                            <div class="form-group">
                                <label>{{ trans('wallet::lang.status') }}</label>
                                <select id="status" onchange="fireEventOnFilter(this.value)" class="form-control" name="status">
                                    <option value="" @if(request()->get('status') == "") selected @endif >{{trans('app.all')}}</option>
                                    <option value="0" @if(request()->get('status') == "0") selected @endif >{{trans('wallet::lang.pending')}}</option>
                                    <option value="1" @if(request()->get('status') == "1") selected @endif >{{trans('wallet::lang.approve')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 nopadding-right nopadding-left">
                            &nbsp;
                        </div>
                        <div class="col-md-2 nopadding-right nopadding-left">
                            &nbsp;
                        </div>
                        <div class="col-md-2 nopadding-right nopadding-left">
                            &nbsp;
                        </div>
                        <div class="col-md-2 nopadding-left">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button onclick="clearAllFilter()" type="button" class="btn btn-default pull-right" name="search" value="1"><i class="fa fa-caret-left"></i> {{trans('app.clear')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box margin-top-2">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('wallet::lang.payouts') }}</h3>
        <div class="box-tools pull-right">
            @include('admin.partials.reports.timeframe')
        </div>
    </div> <!-- /.box-header -->
    <div class="box-body">
        <div class="rg-card-simple equal-height">
            <canvas id="payoutReport" style="height: 300px; min-height: 300px; max-height: 300px; width: 100%"></canvas>
        </div>

        <span class="spacer30"></span>

        <table class="table table-hover table-no-sort">
            <thead>
            <tr>
                <th>{{ trans('wallet::lang.date') }}</th>
                <th>{{ trans('wallet::lang.shop') }}</th>
                <th>{{ trans('wallet::lang.type') }}</th>
                <th>{{ trans('wallet::lang.description') }}</th>
                <th>{{ trans('wallet::lang.remaining_balance') }}</th>
                <th>{{ trans('wallet::lang.amount') }}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data as $payout )
                    <tr>
                        <td>
                            {{ $payout->created_at->toFormattedDateString() }}
                        </td>
                        <td>
                            {{ $payout->payable->name }}
                        </td>
                        <td>
                            {{ $payout->type }}
                        </td>
                        <td>
                            {{ $payout->meta['description'] }}
                        </td>
                        <td>
                            {{ get_formated_currency($payout->balance, config('system_settings.decimals', 2)) }}
                        </td>
                        <td>
                            {{ get_formated_currency($payout->amount, config('system_settings.decimals', 2)) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> <!-- /.box-body -->
</div> <!-- /.box -->
@endsection

@section('page-script')
    @include('wallet::scripts.report')
@endsection
