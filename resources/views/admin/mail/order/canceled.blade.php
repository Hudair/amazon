@component('mail::message')
#{{ trans('notifications.order_canceled.greeting', ['customer' => $order->customer->getName()]) }}

{{ trans('notifications.order_canceled.message', ['order' => $order->order_number]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.order_canceled.button_text') }}
@endcomponent

@include('admin.mail.order._order_detail_panel', ['order_detail' => $order])

{{ trans('messages.thanks') }},<br>
{{ $order->shop->name  . ', ' . get_platform_title() }}
@endcomponent
