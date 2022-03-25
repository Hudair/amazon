@extends('theme::layouts.main')

@section('content')
  <!-- Blog COVER IMAGE -->
  @include('theme::banners.blog_cover')

  <!-- CONTENT SECTION -->
  @includeWhen(isset($blogs), 'theme::contents.blog_page')

  @includeWhen(isset($blog), 'theme::contents.blog_single')
@endsection
