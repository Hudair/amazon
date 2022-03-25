<div id="leftsidebar">
    <div class="row heading">
      <div class="heading-title">
        <i class="fa fa-comments fa-2x" aria-hidden="true"></i>
        {{ trans('app.chat_conversations') }}
      </div>
    </div>

    {{-- <div class="row searchBox">
      <div class="col-sm-12 searchBox-inner">
        <div class="form-group has-feedback">
          <input id="searchText" type="text" class="form-control" name="searchText" placeholder="Search">
          <span class="fa fa-search form-control-feedback"></span>
        </div>
      </div>
    </div> --}}

    <div class="row sidebarContent">
		@forelse($chats as $conversation)
			<div id="chat-{{$conversation->customer_id}}" class="row sidebarBody {{ isset($chat) && ($conversation->id == $chat->id) ? 'active' : ''}}">
            	<a href="javascript:void(0)" data-link="{{ route('admin.support.chat_conversation.show', $conversation) }}" class="get-content" style="{{ $conversation->isUnread() ? 'color: #222;' : '' }}">
					<div class="col-sm-3 col-xs-3">
					    <img src="{{ get_avatar_src($conversation->customer, 'mini') }}" class="img-circle" alt="{{ trans('app.avatar') }}">
					</div>

					<div class="col-sm-9 col-xs-9 sideBar-main nopadding">
					  	<div class="row">
						    <div class="col-sm-8 col-xs-8 sideBar-name">
						      	<span class="name-meta {{ $conversation->isUnread() ? 'strong' : '' }}">
									{!! $conversation->customer->getName() !!}

									<span class="label label-primary flat indent10 {{ !$conversation->isUnread() ? 'hide' : ''}}">{{ $conversation->statusName(true) }}</span>
						    	</span>

		            			<p class="excerpt {{ $conversation->isUnread() ? 'strong' : '' }}">
		            				{!! Str::limit($conversation->last_message(), 120) !!}
			            		</p>
						    </div>

						    <div class="col-sm-4 col-xs-4 pull-right time">
						      	<span class="time-meta pull-right">{{ $conversation->updated_at->diffForHumans() }}</span>
						    </div>
					  	</div>
					</div>
				</a>
			</div>
  		@empty
  			<p class="text-center"> {{ trans('app.no_data_found') }} </p>
		@endforelse
    </div>
</div>