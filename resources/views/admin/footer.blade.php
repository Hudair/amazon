<!-- Main Footer -->
<footer class="main-footer">
  <div class="pull-right hidden-xs">
  	@if(auth()->user()->isSuperAdmin())
	    <a href="https://incevio.com/" target="_blank">zCart Version: {{ \App\Models\System::VERSION }}</a>
  	@else
	  	<span>{{ trans('app.today_is') . ' ' . date('l M-j, Y')}}</span>
  	@endif
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; {{date('Y') }} {{ config('system_settings.name') ?: config('app.name') }}.</strong> All rights reserved.
</footer>