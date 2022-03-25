@component('mail::message')
#{{ trans('notifications.customer_registered.greeting', ['customer' => $customer->getName()]) }}

{{ trans('notifications.customer_registered.message') }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.customer_registered.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
