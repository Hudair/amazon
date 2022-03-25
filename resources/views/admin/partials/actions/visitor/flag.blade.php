@if(file_exists(sys_image_path('flags') . $visitor->country_code . '.png'))
	<img src="{{asset(sys_image_path('flags') . $visitor->country_code . '.png')}}" class="lang-flag">
@endif