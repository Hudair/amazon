@component('mail::message')
#{{ trans('notifications.shop_created.greeting', ['merchant' => $shop->owner->getName()]) }}

{{ trans('notifications.shop_created.message', ['shop_name' => $shop->name]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ trans('notifications.shop_created.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
