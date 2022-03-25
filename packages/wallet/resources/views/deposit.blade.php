@extends('admin.layouts.master')

@section('content')
	<div class="box">
	    <div class="box-header with-border">
	      <h3 class="box-title">{{ trans('wallet::lang.deposit_fund') }}</h3>
	      <div class="box-tools pull-right">
	      </div>
	    </div> <!-- /.box-header -->
	    <div class="box-body">
	        {!! Form::open(['route' => 'account.wallet.deposit', 'id' => 'depositForm', 'data-toggle' => 'validator']) !!}
			  	<div class="col-md-6 col-md-offset-1 nopadding-left">
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

				                @if(($paymentMethod->code == 'stripe') && $merchant->hasBillingToken())
				                  <div class="form-group">
				                    <label>
				                      <input name="payment_method" value="saved_card" class="icheck payment-option" id="saved-card" type="radio" data-code="{{ $paymentMethod->code }}" data-info="{{$config['msg']}}" data-type="{{ $paymentMethod->type }}" required="required" {{ old('payment_method') ? '' : 'checked' }} />
				                      @lang('wallet::lang.card'): <i class="fa fa-cc-{{ strtolower($merchant->card_brand) }}"></i> ************{{$merchant->card_last_four}}
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
		        	</div> <!-- /.panel -->
			    </div> <!-- /.col-md-8 -->

			    <div class="col-md-4 nopadding">
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

				            <div id="submit-btn-block" class="clearfix space30" style="display: none;">
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
	        {!! Form::close() !!}
	    </div> <!-- /.box-body -->
	</div> <!-- /.box -->
@endsection

@section('page-script')
	@include('wallet::scripts.deposit')
@endsection