{{ $customer->nice_name }}
@unless($customer->active)
    <span class="label label-default indent10">{{ trans('app.inactive') }}</span>
@endunless