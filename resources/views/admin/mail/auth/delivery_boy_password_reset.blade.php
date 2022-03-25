@component('mail::message')
{{ trans('api.delivery_boy_password_reset') }}
<br/>

@component('mail::button', ['url' => '', 'color' => 'blue'])
{{ $token }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
