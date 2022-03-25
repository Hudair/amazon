@component('mail::message')
#{{ trans('notifications.shop_is_live.greeting', ['merchant' => $shop->owner->getName()]) }}

{{ trans('notifications.shop_is_live.message', ['shop_name' => $shop->name]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ trans('notifications.shop_is_live.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
