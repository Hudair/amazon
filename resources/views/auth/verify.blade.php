@extends('auth.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ trans('auth.verify_email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ trans('auth.fresh_verification_link') }}
                        </div>
                    @endif

                    {{ trans('auth.check_your_email') }}, <a href="{{ route('verification.resend') }}">{{ trans('auth.request_another_link') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection