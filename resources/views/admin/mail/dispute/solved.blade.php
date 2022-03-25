@component('mail::message')
#{{ trans('notifications.dispute_solved.greeting', ['customer' => $dispute->customer->getName()]) }}

{{ trans('notifications.dispute_solved.message', ['order_id' => $dispute->order->order_number]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ trans('notifications.dispute_solved.button_text') }}
@endcomponent

@include('admin.mail.dispute._dispute_detail_panel', ['dispute_detail' => $dispute])

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent