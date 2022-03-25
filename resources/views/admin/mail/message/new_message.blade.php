@component('mail::message')
#{{ trans('notifications.new_message.greeting', ['receiver' => $receiver]) }}

{!! trans('notifications.new_message.message', ['message' => $message->message]) !!}
<br/>

@unless(isset($guest) && $guest)
@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.new_message.button_text') }}
@endcomponent
@endunless

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
