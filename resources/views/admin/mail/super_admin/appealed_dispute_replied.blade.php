@component('mail::message')
#{{ trans('notifications.appealed_dispute_replied.greeting') }}

{!! trans('notifications.appealed_dispute_replied.message', ['order_id' => $reply->repliable->order->order_number, 'reply' => $reply->reply]) !!}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.appealed_dispute_replied.button_text') }}
@endcomponent

@include('admin.mail.dispute._dispute_detail_panel', ['dispute_detail' => $reply->repliable])

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
