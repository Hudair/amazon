@component('mail::message')
#{{ trans('notifications.customer_updated.greeting', ['customer' => $customer->getName()]) }}

{{ trans('notifications.customer_updated.message') }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.customer_updated.button_text') }}
@endcomponent
<br/>

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent