@component('mail::message')
#{{ trans('notifications.system_is_live.greeting', ['user' => $system->superAdmin()->getName()]) }}

{{ trans('notifications.system_is_live.message', ['marketplace' => get_platform_title()]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ trans('notifications.system_is_live.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
