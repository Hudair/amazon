@component('mail::message')
{{ trans('wallet::lang.mail.greeting', ['receiver' => $receiver]) }}

{{ trans('wallet::lang.mail.pending_amount', ['amount' => $amount]) }}

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('wallet::lang.mail.see_now') }}
@endcomponent

{{ trans('wallet::lang.mail.thanks') }},<br>
{{ get_platform_title() }}
<br/>
@endcomponent