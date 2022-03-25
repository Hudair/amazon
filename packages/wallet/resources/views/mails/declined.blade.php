@component('mail::message')
{{ trans('wallet::lang.mail.greeting', ['receiver' => $receiver]) }}

{{ trans('wallet::lang.mail.declined_msg') }}

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('wallet::lang.mail.see_now') }}
@endcomponent

{{ trans('inspector::lang.thanks') }},<br>
{{ get_platform_title() }}
<br/>
@endcomponent