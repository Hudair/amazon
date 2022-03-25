@component('mail::message')
#{{ trans('notifications.dispute_appealed.greeting') }}

{{ trans('notifications.dispute_appealed.message', ['order_id' => $reply->repliable->order->order_number, 'reply' => $reply->reply]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.dispute_appealed.button_text') }}
@endcomponent

@include('admin.mail.dispute._dispute_detail_panel', ['dispute_detail' => $reply->repliable])

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
