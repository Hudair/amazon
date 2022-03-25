@extends('theme::layouts.main')

@section('content')
  <!-- HEADER SECTION -->
  @include('theme::headers.dispute_page')

  <!-- CONTENT SECTION -->
  @include('theme::contents.dispute_page')

  <!-- MODALS -->
  @includeWhen(! $order->dispute, 'theme::modals.dispute')

  @if ($order->dispute)
    @if ($order->dispute->isClosed())
      @include('theme::modals.dispute_appeal')
    @else
      @include('theme::modals.dispute_response')
    @endif
  @endif
@endsection
