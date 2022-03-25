@component('mail::message')
#{{ trans('notifications.dispute_created.greeting', ['merchant' => $dispute->shop->name]) }}

{{ trans('notifications.dispute_created.message', ['order_id' => $dispute->order->order_number]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.dispute_created.button_text') }}
@endcomponent

@include('admin.mail.dispute._dispute_detail_panel', ['dispute_detail' => $dispute])

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
