@extends('admin.layouts.master')

@php
$can_update = Gate::allows('update', $config) ?: null;
$active_payment_methods = $config->paymentMethods->pluck('id')->toArray();
$has_config = false;
@endphp

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">
        {{ trans('app.payment_methods') }}
      </h3>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-12">
          @foreach ($payment_method_types as $type_id => $type)
            @php
              $payment_providers = $payment_methods->where('type', $type_id);
              $logo_path = sys_image_path('payment-method-types') . "{$type_id}.svg";
            @endphp

            @if ($payment_providers->count())
              <div class="row">
                <span class="spacer10"></span>
                <div class="col-sm-5">
                  @if (File::exists($logo_path))
                    <img src="{{ asset($logo_path) }}" width="100" height="25" alt="{{ $type }}">
                    <span class="spacer10"></span>
                  @else
                    <p class="lead">{{ $type }}</p>
                  @endif
                  <p>{!! get_payment_method_type($type_id)['description'] !!}</p>
                </div> <!-- /.col-ms-5 -->

                <div class="col-sm-7">
                  @foreach ($payment_providers as $payment_provider)
                    <!-- Skip the wallet because wallet setting has option to activatte -->
                    @continue($payment_provider->code == 'zcart-wallet')

                    @php
                      $has_config = false;
                      $logo_path = sys_image_path('payment-methods') . "{$payment_provider->code}.png";
                    @endphp
                    <ul class="list-group">
                      <li class="list-group-item">
                        @if (File::exists($logo_path))
                          <img src="{{ asset($logo_path) }}" class="open-img-md" alt="{{ $type }}">
                        @else
                          <p class="list-group-item-heading inline lead">
                            {{ $payment_provider->name }}
                          </p>
                        @endif

                        <span class="spacer10"></span>

                        <p class="list-group-item-text">
                          {!! $payment_provider->description !!}
                        </p>

                        <span class="spacer20"></span>

                        @if (in_array($payment_provider->id, $active_payment_methods))
                          @if ($can_update)
                            @switch($payment_provider->code)
                              @case('stripe')
                                @if ($config->stripe)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('instamojo')
                                @if ($config->instamojo)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('authorize-net')
                                @if ($config->authorizeNet)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('paypal-express')
                                @if ($config->paypalExpress)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('paypal-marketplace')
                                @if ($config->paypalMarketplace)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('paystack')
                                @if ($config->paystack)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('cybersource')
                                @if ($config->cybersource)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('razorpay')
                                @if ($config->razorpay)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('mpesa')
                                @if ($config->mpesa)
                                  @php
                                    $has_config = true;
                                  @endphp
                                @endif
                              @break

                              @case('wire')
                              @case('cod')
                              @case('pip')
                                @php
                                  $active = $config->manualPaymentMethods->pluck('id')->toArray();
                                  
                                  $has_config = in_array($payment_provider->id, $active) ? true : false;
                                @endphp
                              @break
                            @endswitch

                            @unless($has_config)
                              <div class="alert alert-danger">@lang('app.payment_method_configuration_issue')</div>
                            @endunless

                            @if ($payment_provider->code == 'stripe')
                              <a href="{{ route('admin.setting.paymentMethod.activate', $payment_provider->id) }}" class="btn btn-info">{{ trans('app.update') }}</a>
                            @else
                              <a href="javascript:void(0)" data-link="{{ route('admin.setting.paymentMethod.activate', $payment_provider->id) }}" class="btn ajax-modal-btn btn-info">{{ trans('app.update') }}</a>
                            @endif

                            <a href="{{ route('admin.setting.paymentMethod.deactivate', $payment_provider->id) }}" class="btn btn-default ajax-silent confirm"> {{ trans('app.deactivate') }}</a>
                          @else
                            <span class="label label-default">{{ trans('app.active') }}</span>
                          @endif
                        @else
                          @if ($can_update)
                            @if ($payment_provider->code == 'stripe')
                              <a href="{{ route('admin.setting.paymentMethod.activate', $payment_provider->id) }}" class="btn btn-primary">{{ $has_config ? trans('app.reactivate') : trans('app.activate') }}</a>
                            @else
                              <a href="javascript:void(0)" data-link="{{ route('admin.setting.paymentMethod.activate', $payment_provider->id) }}" class="btn ajax-modal-btn btn-primary">{{ $has_config ? trans('app.reactivate') : trans('app.activate') }}</a>
                            @endif
                          @else
                            <span class="label label-default">{{ trans('app.inactive') }}</span>
                          @endif
                        @endif

                        <span class="spacer15"></span>
                  </li>
                </ul>
              @endforeach
            </div> <!-- /.col-ms-7 -->
          </div> <!-- /.row -->

          @unless($loop->last)
            <hr />
          @endunless
        @endif
      @endforeach
    </div> <!-- /.col-sm-12 -->
  </div> <!-- /.row -->
</div> <!-- /.box-body -->
</div> <!-- /.box -->
@endsection
