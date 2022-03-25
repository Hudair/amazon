@component('mail::message')
#{{ trans('notifications.cancellation_request_acknowledgement.greeting', ['customer' => $order->customer->getName()]) }}

{{ trans('notifications.cancellation_request_acknowledgement.message', ['order' => $order->order_number]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.cancellation_request_acknowledgement.button_text') }}
@endcomponent

@include('admin.mail.order._order_detail_panel', ['order_detail' => $order])

{{ trans('messages.thanks') }},<br>
{{ $order->shop->name  . ', ' . get_platform_title() }}
@endcomponent
