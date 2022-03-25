<div id="global-alert-box" class="alert alert-warning alert-dismissible {{ Session::has('global_msg') ? '' : 'hidden'}}">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-warning"></i> {{ trans('app.alert') }}</h4>
  <p id="global-alert-msg">{{ Session::get('global_msg') }}</p>
</div>

<div id="global-notice-box" class="alert alert-info alert-dismissible {{ Session::has('global_notice') ? '' : 'hidden'}}">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-info-circle"></i> {{ trans('app.notice') }}</h4>
  <p id="global-notice">{{ Session::get('global_notice') }}</p>
</div>

<div id="global-error-box" class="alert alert-danger alert-dismissible {{ Session::has('global_error') ? '' : 'hidden'}}">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-stop-circle-o"></i> {{ trans('app.error') }}</h4>
  <p id="global-error">{{ Session::get('global_error') }}</p>
</div> <!-- /#global-alert-box -->