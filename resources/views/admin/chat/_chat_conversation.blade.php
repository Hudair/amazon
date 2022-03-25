<div id="openChatbox-{{$chat->customer_id}}" class="row heading">
    <div class="col-sm-2 col-md-1 col-xs-3 heading-avatar">
       <img src="{{ get_avatar_src($chat->customer, 'mini') }}" class="img-circle" alt="{{ trans('app.avatar') }}">
    </div>
    <div class="col-sm-8 col-xs-7 heading-name">
			@if(Gate::allows('view', $chat->customer))
         <a href="javascript:void(0)" data-link="{{ route('admin.admin.customer.show', $chat->customer_id) }}" class="ajax-modal-btn heading-name-meta">{!! $chat->customer->getName() !!}</a>
			@else
				 <span class="heading-name-meta">{{ $chat->customer->getName() }}</span>
			@endif
      {{-- <span class="heading-online">Online</span> --}}
    </div>
    <div class="col-sm-1 col-xs-1  heading-dot pull-right">
      {{-- <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i> --}}
    </div>
</div>

<div class="row message" id="conversationBox">
	<div class="row message-previous">
  		<div class="col-sm-12 previous">
    		<a onclick="previous(this)" id="ankitjain28" name="20">
        		{{-- Show Previous Message! --}}
		    </a>
  		</div>
	</div>

	<div class="row message-body">
	  	<div class="col-sm-12 message-main-receiver">
	        <div class="receiver">
	          <div class="message-text">
					       {!! $chat->message !!}
	        	</div>
	        </div>
	        <span class="message-time">
	            {{ $chat->created_at->diffForHumans() }}
	        </span>
	  	</div>
	</div>

  @foreach($chat->replies as $reply)
      <div class="row message-body">
        <div class="col-sm-12 message-main-{{ $reply->customer_id ? 'receiver' : 'sender'}}">
          <div class="{{ $reply->customer_id ? 'receiver' : 'sender'}}">
            	<div class="message-text">
      					{!! $reply->reply !!}
            	</div>
          </div>
        	<span class="message-time">
	            {{ $reply->updated_at->diffForHumans() }}
        	</span>
        </div>
      </div>
	@endforeach
</div>

<div class="row reply">
	{!! Form::open(['route' => ['admin.support.chat_conversation.reply', $chat], 'files' => true, 'id' => 'chat-form', 'data-toggle' => 'validator']) !!}
        <div class="col-sm-1 col-xs-1 reply-attachment">
          	{{-- <i class="fa fa-paperclip fa-2x"></i> --}}
        </div>
        <div class="col-sm-10 col-xs-10 reply-main">
          	<textarea id="message" name="message" placeholder="Write your reply here ... " class="form-control" rows="1"></textarea>
        </div>
        <div class="col-sm-1 col-xs-1 reply-send nopadding-left">
          	<i class="fa fa-send fa-2x" id="send-btn" aria-hidden="true"></i>
        </div>
	{!! Form::close() !!}
</div>
