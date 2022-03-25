@component('mail::message')
#{{ trans('notifications.user_updated.greeting', ['user' => $user->getName()]) }}

{{ trans('notifications.user_updated.message') }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.user_updated.button_text') }}
@endcomponent
<br/>

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
