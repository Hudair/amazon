<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Page Not Found</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      line-height: 1.2;
      margin: 0;
    }

    html {
      color: #888;
      display: table;
      font-family: sans-serif;
      height: 100%;
      text-align: center;
      width: 100%;
    }

    body {
      display: table-cell;
      vertical-align: middle;
      margin: 2em auto;
    }

    h1 {
      color: #555;
      font-size: 2em;
      font-weight: 400;
    }

    p {
      margin: 0 auto;
      width: 280px;
    }

    .brand-logo {
      max-width: 120px;
      max-height: 40px;
    }

    @media only screen and (max-width: 280px) {

      body,
      p {
        width: 95%;
      }

      h1 {
        font-size: 1.5em;
        margin: 0 0 0.3em;
      }

    }

  </style>
</head>

<body>
  <a href="{{ url('/') }}">
    @if (Storage::exists('logo.png'))
      <img src="{{ get_storage_file_url('logo.png', 'full') }}" alt="{{ trans('app.logo') }}" title="{{ trans('app.logo') }}">
    @else
      <img src="https://placehold.it/140x60/eee?text={{ get_platform_title() }}" alt="LOGO" title="LOGO" />
    @endif
  </a>
  <h1>Page Not Found</h1>
  <p>Sorry, but the page you were trying to view does not exist.</p>
  <a href="{{ url()->previous() }}">@lang('theme.button.go_back')</a>
</body>

</html>
<!-- IE needs 512+ bytes: https://blogs.msdn.microsoft.com/ieinternals/2010/08/18/friendly-http-error-pages/ -->
