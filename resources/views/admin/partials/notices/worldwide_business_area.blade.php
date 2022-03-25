@if(config('system_settings.worldwide_business_area'))
	<div class="alert alert-info alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong><i class="icon fa fa-info-circle"></i>{{ trans('app.notice') }}</strong>
		{{ trans('messages.active_worldwide_business_area') }}
	</div>
@endif