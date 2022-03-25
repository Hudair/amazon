@extends('theme::layouts.main')

@section('content')
  <!-- breadcrumb -->
  @include('theme::headers.checkout_page')

  <!-- CONTENT SECTION -->
  @include('checkout::checkout_page')
@endsection

@section('scripts')
  @include('scripts.checkout', ['one_checkout_form' => true])
  @include('checkout::scripts.checkout')
@endsection
