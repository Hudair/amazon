@component('mail::message')
#{{ trans('notifications.verdor_registered.greeting') }}

{!! trans('notifications.verdor_registered.message', ['marketplace' => get_platform_title(), 'shop_name' => $merchant->owns->name, 'merchant_email' => $merchant->email]) !!}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ trans('notifications.verdor_registered.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
