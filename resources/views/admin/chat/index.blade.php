@extends('admin.layouts.master')

@section('content')

  <div id="chatbox">
      <div class="row chatContent">
        <div class="col-sm-4 side">
            @include('admin.chat._left_nav')
        </div>

        <div id="chatConversation" class="col-sm-8 conversation">
            <div class="row heading">
                <div class="col-sm-2 col-md-1 col-xs-3 heading-avatar">
                   <img src="{{ get_gravatar_url('help@incevio.com', 'mini') }}" class="img-circle" alt="{{ trans('app.avatar') }}">
                </div>
                <div class="col-sm-8 col-xs-7 heading-name">
                   <span class="heading-name-meta">{{ trans('app.conversations') }}</span>
                </div>
            </div>

            <div class="row message">
                <div class="row message-body">
                  <div class="col-sm-12">
                    <p class="lead">{!! trans('messages.please_select_conversation') !!}</p>
                  </div>
                </div>
            </div>
        </div>
      </div>
  </div>
@endsection

@section('page-script')
    @include('plugins.chatbox')
@endsection