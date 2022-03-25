@component('mail::message')
#{{ trans('notifications.message_replied.greeting', ['receiver' => $receiver]) }}

{!! trans('notifications.message_replied.message', ['reply' => $reply->reply]) !!}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.message_replied.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
