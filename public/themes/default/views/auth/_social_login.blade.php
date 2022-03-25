<div class="text-center text-muted p-3">{{ trans('theme.login_with_social') }}</div>

<div class="d-flex justify-content-center social-buttons">
  @if (config('services.facebook.client_id'))
    <a href="{{ route('socialite.customer', 'facebook') }}" class="btn btn-facebook btn-round" data-toggle="tooltip" data-placement="top" title="{{ trans('theme.button.login_with_fb') }}">
      <i class="fab fa-facebook-f"></i>
    </a>
  @endif

  @if (config('services.google.client_id'))
    <a href="{{ route('socialite.customer', 'google') }}" class="btn btn-google btn-round" data-toggle="tooltip" data-placement="top" title="{{ trans('theme.button.login_with_g') }}">
      <i class="fab fa-google"></i>
    </a>
  @endif

  @if (is_incevio_package_loaded('apple-login'))
    <a href="{{ route('socialite.customer', 'apple') }}" class="btn btn-apple btn-round" data-toggle="tooltip" data-placement="top" title="{{ trans('appleLogin::lang.login_with_apple') }}">
      <i class="fab fa-apple"></i>
    </a>
  @endif
</div>
