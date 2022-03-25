@extends('theme::layouts.main')

@section('content')
  <section>
    <div class="container">
      <div class="clearfix space50"></div>
      <p class="lead text-center space50">
        {!! trans('theme.item_not_available') !!}<br /><br />
        <a href="{{ url('/') }}" class="btn btn-primary btn-sm flat">@lang('theme.button.shop_from_other_categories')</a>
      </p>
    </div> <!-- /.container -->
  </section>
@endsection
