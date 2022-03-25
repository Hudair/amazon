@component('mail::message')
{{ trans('inspector::lang.greeting', ['receiver' => $receiver]) }}

{{ trans('inspector::lang.approved_content', ['approved' => $approved]) }}

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('inspector::lang.see_now') }}
@endcomponent

{{ trans('inspector::lang.thanks') }},<br>
{{ get_platform_title() }}
<br/>
@endcomponent