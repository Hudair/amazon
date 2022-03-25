<div class="clearfix space50"></div>
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="contact-info">
          <h2 class="space20">&nbsp;</h2>
          <div class="media-list">
            @if (config('system_settings.support_phone'))
              <div class="media space20">
                <i class="pull-left fas fa-phone"></i>
                <div class="media-body">
                  <h4>@lang('theme.phone'):</h4>
                  {{ config('system_settings.support_phone') }}
                </div>
              </div>
            @endif

            @if (config('system_settings.support_phone_toll_free'))
              <div class="media space20">
                <i class="pull-left fas fa-phone-square"></i>
                <div class="media-body">
                  <h4>@lang('theme.phone'): (@lang('theme.toll_free'))</h4>
                  {{ config('system_settings.support_phone_toll_free') }}
                </div>
              </div>
            @endif

            @if (config('system_settings.support_email'))
              <div class="media space20">
                <i class="pull-left fas fa-envelope-o"></i>
                <div class="media-body">
                  <h4>@lang('theme.email'):</h4>
                  <a href="mailto:{{ config('system_settings.support_email') }}">{{ config('system_settings.support_email') }}</a>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div> <!-- /.col-md-4 -->

      <div class="col-md-8">
        <div class="section-title">
          <h4>@lang('theme.section_headings.contact_form')</h4>
        </div>

        {!! Form::open(['route' => 'contact_us', 'id' => 'contact_us_form', 'role' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="row">
          <div class="col-md-6 nopadding-right">
            <div class="form-group">
              {!! Form::text('name', null, ['class' => 'form-control input-lg flat', 'placeholder' => trans('theme.placeholder.name'), 'maxlength' => '100', 'required']) !!}
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-6 nopadding-left">
            <div class="form-group">
              {!! Form::email('email', null, ['class' => 'form-control input-lg flat', 'placeholder' => trans('theme.placeholder.email'), 'maxlength' => '100', 'required']) !!}
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>

        <div class="form-group">
          {!! Form::text('subject', null, ['class' => 'form-control input-lg flat', 'placeholder' => trans('theme.placeholder.contact_us_subject'), 'maxlength' => 200, 'required']) !!}
          <div class="help-block with-errors"></div>
        </div>

        <div class="form-group">
          {!! Form::textarea('message', null, ['class' => 'form-control input-lg flat', 'placeholder' => trans('theme.placeholder.message'), 'rows' => 3, 'maxlength' => 500, 'required']) !!}
          <div class="help-block with-errors"></div>
        </div>

        <div class="row">
          <div class="col-md-6 nopadding-right">
            <div class="form-group">
              <button type="submit" class='btn btn-primary btn-lg flat'><i class="fas fa-paper-plane"></i> {{ trans('theme.button.send_message') }}</button>
            </div>
          </div>
          <div class="col-md-6 nopadding-left">
            <div class="form-group">
              @if (config('services.recaptcha.key'))
                <div class="g-recaptcha" data-sitekey="{!! config('services.recaptcha.key') !!}"></div>
              @endif
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        {!! Form::close() !!}
      </div> <!-- /.col-md-8 -->
    </div>
  </div>
</section>
<!-- END CONTENT SECTION -->

<div class="clearfix space50"></div>

{{-- Include the recaptcha api script when its enabled --}}
@if (config('services.recaptcha.key'))
  @section('scripts')
    @include('theme::scripts.recaptcha')
  @endsection
@endif
