<div class="clearfix space30"></div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel panel-heading">
            <h3 class="panel-title">{{ trans('wallet::lang.deposit_fund') }}</h3>
            {{--<div class="box-tools pull-right">
            </div>--}}
        </div> <!-- /.box-header -->
        <div class="panel-body">
            {!! Form::open(['route' => 'account.wallet.deposit', 'id' => 'depositForm', 'data-toggle' => 'validator']) !!}
            <div class="col-md-7 nopadding-left">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="input-group">
                                @if(get_currency_prefix())
                                    <span class="input-group-addon" id="basic-addon1">
                                        {{ get_currency_prefix() }}
                                    </span>
                                @endif

                                {!! Form::number('amount', null, ['id' => 'amount', 'class' => 'form-control input-lg', 'step' => 'any', 'min' => 1 , 'placeholder' => trans('wallet::lang.amount'), 'required' => 'required']) !!}

                                @if(get_currency_suffix())
                                    <span class="input-group-addon" id="basic-addon1">
                                        {{ get_currency_suffix() }}
                                    </span>
                                @endif
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="spacer10"></div>

                        @foreach($paymentMethods as $paymentMethod)
                            @php
                                $config = get_payment_config_info($paymentMethod->code);
                            @endphp

                            {{-- Skip the payment option if not confirured --}}
                            @continue(! $config)

                            @if(($paymentMethod->code == 'stripe'))
                                <div class="form-group">
                                    <label>
                                        <input name="payment_method" value="saved_card" class="icheck payment-option" id="saved-card" type="radio" data-code="{{ $paymentMethod->code }}" data-info="{{$config['msg']}}" data-type="{{ $paymentMethod->type }}" required="required" {{ old('payment_method') ? '' : 'checked' }} />
                                       {{-- @lang('wallet::lang.card'): <i class="fa fa-cc-{{ strtolower($merchant->card_brand) }}"></i> ************{{$merchant->card_last_four}}--}}
                                       {{ $paymentMethod->name }}
                                    </label>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>
                                    <input name="payment_method" value="{{ $paymentMethod->code }}" class="icheck payment-option" type="radio" data-code="{{ $paymentMethod->code }}" data-info="{{$config['msg']}}" data-type="{{ $paymentMethod->type }}" required="required" {{ old('payment_method') == $paymentMethod->code ? 'checked' : '' }}/> {{ $paymentMethod->code == 'stripe' ? trans('app.credit_card') : $paymentMethod->name }}
                                </label>
                            </div>
                        @endforeach
                    </div> <!-- /.panel-body -->
                  {{--  <div class="panel-footer" style="text-align: center">
                        {!! Form::submit(trans('wallet::lang.transfer'), ['class' => 'btn btn-primary btn-new']) !!}
                    </div>--}}
                </div> <!-- /.panel -->
            </div> <!-- /.col-md-8 -->
            <div class="col-md-5">
                <div class="nopadding">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{-- authorize-net --}}
                            @include('partials.authorizenet_card_form')

                            {{-- Stripe --}}
                            @include('partials.strip_card_form')

                            {{-- JRF Pay --}}
                            @if(is_incevio_package_loaded('jrfpay'))
                                @include('partials.jrfpay_payment_form')
                            @endif

                            <p id="payment-instructions" class="text-info small space30">
                                <i class="fa fa-info-circle"></i>
                                <span>@lang('app.select_payment_option')</span>
                            </p>

                            <div id="submit-btn-block" class="clearfix space30">
                                <button id="pay-now-btn"  class="btn btn-primary btn-lg btn-block" type="submit">
                                    <small><i class="fa fa-shield"></i> <span id="pay-now-btn-txt">@lang('wallet::lang.pay_now')</span></small>
                                </button>

                                <a href="javascript:void(0)" id="paypal-express-btn" class="hide" type="submit">
                                    <img src="{{ asset(sys_image_path('payment-methods') . "paypal-express.png") }}" width="70%" alt="paypal express checkout" title="paypal-express" />
                                </a>
                            </div>
                        </div> <!-- /.panel-body -->
                    </div> <!-- /.panel -->
                </div> <!-- /.col-md-4 -->
            </div>

            {!! Form::close() !!}
        </div> <!-- /.box-body -->
    </div> <!-- /.box -->
</div>

{{--
@section('scripts')
     @include('wallet::customer.scripts.deposit')
@endsection--}}
