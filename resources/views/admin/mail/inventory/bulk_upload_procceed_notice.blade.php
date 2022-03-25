@component('mail::message')
#{!! trans('notifications.inventory_bulk_upload_procceed_notice.greeting') !!}

{!! trans('notifications.inventory_bulk_upload_procceed_notice.message', ['success' => $success, 'failed' => $failed]) !!}
<br/>
@if($failed > 0)
<br/>
###{!! trans('app.import_failed') !!}:
@component('mail::table')
| {{ trans('app.image') }} | {{ trans('app.sku') }} | {{ trans('app.title') }} | {{ trans('app.quantity') }} | {{ trans('app.reason') }} |
| :------------- | :-------------: | :------------- | :-------------: | :-------- |
@foreach($failed_list as $row)
@php
	$image_links = explode(',', $row['data']['image_links']);
@endphp
| <img src="{{ count($image_links) ? $image_links[0] : get_placeholder_img('small') }}" width="40"> | {{ $row['data']['sku'] }} | {{ $row['data']['title'] }} | {{ $row['data']['stock_quantity'] }} | {{ $row['reason'] }} |
@endforeach
@endcomponent

{{ trans('notifications.inventory_bulk_upload_procceed_notice.failed') }}
@endif
<br/>

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ trans('notifications.inventory_bulk_upload_procceed_notice.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
