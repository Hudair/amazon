<div id="zcart_chat">
  <div id="chat-window" class="chat">
    <div class="chat_header">
      <div class="chat_option">
        <div class="header_img">
          <img src="{{ get_storage_file_url(optional($shop->image)->path, 'thumbnail') }}" />
        </div>
        <span id="chat_head">{{ $shop->name }}</span> <br>
        <span class="agent">{{ $agent->getName() }}</span> <span class="online">({{ $agent_status }})</span>
      </div>
    </div>

    <div id="chat_conversation" class="chat_converse">
      {{-- <a id="chat_second_screen" class="fchat"><i class="fas fa-arrow-right"></i></a> --}}
      @unless(Auth::guard('customer')->check())
        <p>
          {!! trans('theme.login_to_chat') !!}
          <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">{{ trans('theme.button.login') }}</a>
        </p>
      @else
        <p class="text-primary">{!! trans('theme.connecting') !!}</p>
      @endunless
    </div>

    @if (Auth::guard('customer')->check())
      <div class="fchat_field">
        {{-- <a id="fchat_camera" class="fchat"><i class="fas fa-camera"></i></a> --}}
        <a id="fchat_send" class="fchat"><i class="fas fa-paper-plane"></i></a>
        <textarea id="chatBoxMsg" name="chat_message" placeholder="Send a message" class="chat_field chat_message"></textarea>
      </div>
    @endif
  </div>

  <a id="chatbox" class="fchat">
    <i class="chat-icon fas fa-comment"></i>
  </a>
</div>

<script type="text/javascript">
  "use strict";
  var agent_avatar = $('<div>').addClass('chat_avatar');
  $('<img/>').attr('src', "{{ get_storage_file_url(optional($shop->image)->path, 'thumbnail') }}").appendTo(agent_avatar);

  function updateScroll() {
    var element = document.getElementById("chat_conversation");
    element.scrollTop = element.scrollHeight;
  }

  ;
  (function($, window, document) {
    $(document).ready(function() {
      // When send button clicked
      $("#fchat_send").on('click', function() {
        sendTheMessage();
      });

      // When enter button pressed
      document.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          sendTheMessage();
        }
      });

      $('#chatbox').click(function() {
        toggleFchat();
      });

      // Send the message
      function sendTheMessage() {
        var msg = $.trim($("#chatBoxMsg").val());
        if (msg == '') return;

        $("#fchat_send").addClass('hidden');

        var response = '';
        $.ajax({
          url: "{{ route('chat.start') }}",
          type: 'POST',
          data: {
            'message': msg,
            'shop_slug': "{{ $shop->slug }}",
          },
          complete: function(xhr, textStatus) {
            $("#fchat_send").removeClass('hidden');

            switch (xhr.status) {
              case 200:
                $("#chatBoxMsg").val('');
                response = $('<span>').addClass('chat_msg_item chat_msg_item_user').text(msg);
                break;

              case 401:
                $("#chat_conversation").html(""); //Clear the chatbox
                response = $('<p>').addClass('text-danger').text("{!! trans('theme.login_to_chat') !!}");
                $('<br/><br/>').prependTo(response);
                $('<a>').attr('href', "javascript:void(0)").attr('data-toggle', "modal").attr('data-target', "#loginModal").addClass('btn btn-primary').text("{{ trans('theme.button.login') }}").appendTo(response);
                break;

              case 403:
              case 419:
                $("#chat_conversation").html(""); //Clear the chatbox
                response = $('<p>').addClass('text-danger').text("{!! trans('theme.session_expired') !!}");
                $('<br/><br/>').prependTo(response);
                $('<a>').attr('href', "javascript:void(0)").attr('data-toggle', "modal").attr('data-target', "#loginModal").addClass('btn btn-primary').text("{{ trans('theme.button.login') }}").appendTo(response);
                break;

              case 404:
                response = $('<p>').addClass('text-danger').text("{!! trans('theme.shop_not_found') !!}");
                $('<br/><br/>').prependTo(response);
                $('<a>').attr('href', "/").addClass('btn btn-primary').text("{{ trans('theme.button.shop_now') }}").appendTo(response);
                break;

              default:
                response = $('<p>').addClass('text-danger').text("{!! trans('theme.notify.failed') !!}");
                $('<br/><br/>').prependTo(response);
            }

            $("#chat_conversation").append(response);

            updateScroll();
          },
        });
      }

      //Toggle chat and links
      function toggleFchat() {
        $('.chat-icon').toggleClass('fa-comment');
        $('.chat-icon').toggleClass('fa-times');
        $('.chat-icon').toggleClass('is-active');
        $('.chat-icon').toggleClass('is-visible');
        $('#chatbox').toggleClass('is-float');
        $('.chat').toggleClass('is-visible');
        $('.fchat').toggleClass('is-visible');

        if ($("#chat-window").hasClass('is-visible')) {
          loadOldChat();
        }
      }

      //Load Old Chats
      function loadOldChat() {
        $.ajax({
          url: "{{ route('chat.conversation', $shop->id) }}",
          success: function(result) {
            var response = $('<span>').addClass('chat_msg_item');
            var conversations = '';

            if (result) {
              response.addClass('chat_msg_item_user').text(result.message);

              result.replies.forEach(function(reply) {
                // response.append($('<span>').addClass('chat_msg_item chat_msg_item_admin').text(reply.reply));
                if (reply.user_id) {
                  conversations += '<span class="chat_msg_item chat_msg_item_admin">' +
                    '<div class="chat_avatar">' +
                    '<img src="{{ get_storage_file_url(optional($shop->image)->path, 'thumbnail') }}"/>' +
                    '</div>' +
                    reply.reply + '</span>';
                } else {
                  conversations += '<span class="chat_msg_item chat_msg_item_user">' + reply.reply + '</span>';
                }
              });
            } else {
              response.addClass('chat_msg_item_admin').text("{!! trans('theme.chat_welcome') !!}");
              agent_avatar.prependTo(response);
            }

            $("#chat_conversation").html(response).append(conversations);

            updateScroll();
          }
        });
      }
    });
  }(window.jQuery, window, document));
</script>

@if (Auth::guard('customer')->check())
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

  @if (env('APP_DEBUG', false))
    <script type="text/javascript">
      "use strict";;
      (function($, window, document) {
        $(document).ready(function() {
          Pusher.logToConsole = true; // Enable pusher logging - don't include this in production
        });
      }(window.jQuery, window, document));
    </script>
  @endif

  <script type="text/javascript">
    "use strict";;
    (function($, window, document) {
      $(document).ready(function() {
        var pusher = new Pusher('{{ config('services.pusher.key') }}', {
          cluster: '{{ config('services.pusher.cluster') }}',
          forceTLS: true
        });

        var channel = pusher.subscribe('{{ get_chat_room_name($shop->id . Auth::guard('customer')->user()->id) }}');
        channel.bind('zcart-chat-new-message', function(result) {
          var response = $('<span>').addClass('chat_msg_item');
          response.addClass('chat_msg_item_admin').text(result.text);
          agent_avatar.prependTo(response);
          $("#chat_conversation").append(response);
          updateScroll();
        });
      });
    }(window.jQuery, window, document));
  </script>
@endif

<style type="text/css">
  #zcart_chat {
    bottom: 0;
    position: fixed;
    margin: 1em;
    right: 0;
    z-index: 998;
  }

  #zcart_chat #chat_conversation {
    display: block;
  }

  #zcart_chat .btn {
    display: block;
    margin: 10px auto;
  }

  #zcart_chat ul li {
    list-style: none;
  }

  #zcart_chat .fchat {
    display: block;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    text-align: center;
    color: #f0f0f0;
    margin: 25px auto 0;
    box-shadow: 0 0 4px rgba(0, 0, 0, .14), 0 4px 8px rgba(0, 0, 0, .28);
    cursor: pointer;
    -webkit-transition: all .1s ease-out;
    transition: all .1s ease-out;
    position: relative;
    z-index: 998;
    overflow: hidden;
    background: #42a5f5;
  }

  #zcart_chat .fchat>i {
    font-size: 2em;
    line-height: 55px;
    -webkit-transition: all .2s ease-out;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }

  #zcart_chat .fchat:not(:last-child) {
    width: 0;
    height: 0;
    margin: 20px auto 0;
    opacity: 0;
    visibility: hidden;
    line-height: 40px;
  }

  #zcart_chat .fchat:not(:last-child)>i {
    font-size: 1.4em;
    line-height: 40px;
  }

  #zcart_chat .fchat:not(:last-child).is-visible {
    width: 40px;
    height: 40px;
    margin: 15px auto 10;
    opacity: 1;
    visibility: visible;
  }

  #zcart_chat .fchat:nth-last-child(1) {
    -webkit-transition-delay: 25ms;
    transition-delay: 25ms;
  }

  #zcart_chat .fchat:not(:last-child):nth-last-child(2) {
    -webkit-transition-delay: 20ms;
    transition-delay: 20ms;
  }

  #zcart_chat .fchat:not(:last-child):nth-last-child(3) {
    -webkit-transition-delay: 40ms;
    transition-delay: 40ms;
  }

  #zcart_chat .fchat:not(:last-child):nth-last-child(4) {
    -webkit-transition-delay: 60ms;
    transition-delay: 60ms;
  }

  #zcart_chat .fchat:not(:last-child):nth-last-child(5) {
    -webkit-transition-delay: 80ms;
    transition-delay: 80ms;
  }

  #zcart_chat .fchat(:last-child):active,
  #zcart_chat .fchat(:last-child):focus,
  #zcart_chat .fchat(:last-child):hover {
    box-shadow: 0 0 6px rgba(0, 0, 0, .16), 0 6px 12px rgba(0, 0, 0, .32);
  }

  /*Chatbox*/
  #zcart_chat .chat {
    position: fixed;
    right: 85px;
    bottom: 20px;
    width: 400px;
    font-size: 12px;
    line-height: 22px;
    font-family: 'Roboto';
    font-weight: 500;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
    display: none;
    box-shadow: 1px 1px 100px 2px rgba(0, 0, 0, 0.22);
    border-radius: 4px;
    -webkit-transition: all .2s ease-out;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }

  #zcart_chat .chat_header {
    /* margin: 10px; */
    font-size: 13px;
    font-family: 'Roboto';
    font-weight: 500;
    color: #f3f3f3;
    height: 55px;
    background: #42a5f5;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    padding-top: 8px;
  }

  #zcart_chat .chat_header2 {
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
  }

  #zcart_chat .chat_header .span {
    float: right;
  }

  #zcart_chat .chat.is-visible {
    display: block;
    -webkit-animation: zoomIn .2s cubic-bezier(.42, 0, .58, 1);
    animation: zoomIn .2s cubic-bezier(.42, 0, .58, 1);
  }

  #zcart_chat .is-hide {
    opacity: 0
  }

  #zcart_chat .chat_option {
    float: left;
    font-size: 15px;
    list-style: none;
    position: relative;
    height: 100%;
    width: 100%;
    text-align: relative;
    margin-right: 10px;
    letter-spacing: 0.5px;
    font-weight: 400
  }

  #zcart_chat .header_img {
    background-color: #fff;
    max-width: 56px;
    border-radius: 50%;
    line-height: 50px;
    float: left;
    margin: -20px 10px 10px 10px;
    border: 3px solid rgba(0, 0, 0, 0.21);
  }

  #zcart_chat .header_img img {
    border-radius: 50%;
    max-width: 50px;
    max-height: 50px;
    text-align: center;
    vertical-align: middle;
  }

  #zcart_chat .change_img img {
    width: 35px;
    margin: 0px 20px 0px 20px;
  }

  #zcart_chat .chat_option .agent {
    font-size: 12px;
    font-weight: 300;
  }

  #zcart_chat .chat_option .online {
    opacity: 0.7;
    font-size: 11px;
    font-weight: 300;
  }

  #zcart_chat .chat_color {
    display: block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin: 10px;
    float: left;
  }

  #zcart_chat p,
  #zcart_chat a {
    -webkit-animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
    animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
  }

  #zcart_chat p {
    display: block;
    text-align: center;
    padding: 10px 20px;
    margin-top: 40px;
    color: #888
  }

  #zcart_chat .chat_field {
    position: relative;
    margin: 5px 0 5px 0;
    width: 50%;
    font-family: 'Roboto';
    font-size: 12px;
    line-height: 30px;
    font-weight: 500;
    color: #4b4b4b;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
    border: none;
    outline: none;
    display: inline-block;
  }

  #zcart_chat .chat_field.chat_message {
    height: 30px;
    resize: none;
    font-size: 13px;
    font-weight: 400;
  }

  #zcart_chat .chat_category {
    text-align: left;
  }

  #zcart_chat .chat_category {
    margin: 20px;
    background: rgba(0, 0, 0, 0.03);
    padding: 10px;
  }

  #zcart_chat .chat_category ul li {
    width: 80%;
    height: 30px;
    background: #fff;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
    border-radius: 3px;
    border: 1px solid #e0e0e0;
    font-size: 13px;
    cursor: pointer;
    line-height: 30px;
    color: #888;
    text-align: center;
  }

  #zcart_chat .chat_category li:hover {
    background: #83c76d;
    color: #fff;
  }

  #zcart_chat .chat_category li.active {
    background: #83c76d;
    color: #fff;
  }

  #zcart_chat .fchat_field {
    width: 100%;
    display: inline-block;
    text-align: center;
    background: #fff;
    border-top: 1px solid #eee;
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
  }

  #zcart_chat .fchat_field2 {
    bottom: 0px;
    position: absolute;
    border-bottom-right-radius: 0px;
    border-bottom-left-radius: 0px;
    z-index: 999;
  }

  #zcart_chat .fchat_field a {
    display: inline-block;
    text-align: center;
  }

  #zcart_chat #fchat_camera {
    float: left;
    background: rgba(0, 0, 0, 0);
  }

  #zcart_chat #fchat_send {
    float: right;
    background: rgba(0, 0, 0, 0);
  }

  #zcart_chat .fchat_field .fchat {
    width: 35px;
    height: 35px;
    box-shadow: none;
    margin: 5px;
  }

  #zcart_chat .fchat_field .fchat>i {
    font-size: 1.6em;
    line-height: 35px;
    color: #bbb;
  }

  #zcart_chat .fchat_field .fchat>i:hover {
    color: #42a5f5;
  }

  #zcart_chat #chat_conversation {
    position: relative;
    background: #fff;
    margin: 0px;
    height: 300px;
    min-height: 0;
    font-size: 12px;
    line-height: 18px;
    overflow-y: auto;
    width: 100%;
    float: right;
    padding: 10px 0;
  }

  #zcart_chat .chat_converse_full_screen {
    height: 100%;
    max-height: 800px
  }

  #zcart_chat .chat .chat_converse .chat_msg_item {
    position: relative;
    margin: 2px 0;
    padding: 8px 10px;
    max-width: 65%;
    display: block;
    word-wrap: break-word;
    overflow-wrap: break-word;
    -webkit-animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
    animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
    clear: both;
    z-index: 999;
  }

  #zcart_chat .status {
    margin: 45px -50px 0 0;
    float: right;
    display: block;
    font-size: 11px;
    opacity: 0.3;
  }

  #zcart_chat .chat .chat_converse .chat_msg_item .chat_avatar {
    height: 34px;
    line-height: 34px;
    border: 1px solid #d3d3d3;
    position: absolute;
    top: 0;
  }

  #zcart_chat .chat .chat_converse .chat_msg_item.chat_msg_item_admin .chat_avatar {
    left: -42px;
    background: rgba(0, 0, 0, 0.03);
  }

  #zcart_chat .chat .chat_converse .chat_msg_item.chat_msg_item_user .chat_avatar {
    right: -42px;
    background: rgba(0, 0, 0, 0.6);
  }

  #zcart_chat .chat .chat_converse .chat_msg_item .chat_avatar,
  #zcart_chat .chat_avatar img {
    max-width: 34px;
    max-height: 34px;
    text-align: center;
    vertical-align: middle;
    border-radius: 50%;
    opacity: 0.9;
  }

  #zcart_chat .chat .chat_converse .chat_msg_item.chat_msg_item_admin {
    margin-left: 47px;
    float: left;
    border-radius: 10px 10px 10px 0px;
    background: rgba(0, 0, 0, 0.07);
    color: #666;
  }

  #zcart_chat .chat .chat_converse .chat_msg_item.chat_msg_item_user {
    margin-right: 10px;
    float: right;
    border-radius: 10px 10px 0px 10px;
    background: #42a5f5;
    color: #eceff1;
  }

  #zcart_chat .chat .chat_converse .chat_msg_item.chat_msg_item_admin:before {
    content: '';
    position: absolute;
    top: 15px;
    left: -12px;
    z-index: 998;
    border: 6px solid transparent;
    border-right-color: rgba(255, 255, 255, .4);
  }

  #zcart_chat .chat_form .get-notified label {
    color: #077ad6;
    font-weight: 600;
    font-size: 11px;
  }

  #zcart_chat input {
    position: relative;
    width: 90%;
    font-family: 'Roboto';
    font-size: 12px;
    line-height: 20px;
    font-weight: 500;
    color: #4b4b4b;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
    outline: none;
    background: #fff;
    display: inline-block;
    resize: none;
    padding: 5px;
    border-radius: 3px;
  }

  #zcart_chat .chat_form .get-notified input {
    margin: 2px 0 0 0;
    border: 1px solid #83c76d;
  }

  #zcart_chat .chat_form .get-notified i {
    background: #83c76d;
    width: 30px;
    height: 32px;
    font-size: 18px;
    color: #fff;
    line-height: 30px;
    font-weight: 600;
    text-align: center;
    margin: 2px 0 0 -30px;
    position: absolute;
    border-radius: 3px;
  }

  #zcart_chat .chat_form .message_form {
    margin: 10px 0 0 0;
  }

  #zcart_chat .chat_form .message_form input {
    margin: 5px 0 5px 0;
    border: 1px solid #e0e0e0;
  }

  #zcart_chat .chat_form .message_form textarea {
    margin: 5px 0 5px 0;
    border: 1px solid #e0e0e0;
    position: relative;
    width: 90%;
    font-family: 'Roboto';
    font-size: 12px;
    line-height: 20px;
    font-weight: 500;
    color: #4b4b4b;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
    outline: none;
    background: #fff;
    display: inline-block;
    resize: none;
    padding: 5px;
    border-radius: 3px;
  }

  #zcart_chat .chat_form .message_form button {
    margin: 5px 0 5px 0;
    border: 1px solid #e0e0e0;
    position: relative;
    width: 95%;
    font-family: 'Roboto';
    font-size: 12px;
    line-height: 20px;
    font-weight: 500;
    color: #fff;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
    outline: none;
    background: #fff;
    display: inline-block;
    resize: none;
    padding: 5px;
    border-radius: 3px;
    background: #83c76d;
    cursor: pointer;
  }

  #zcart_chat strong.chat_time {
    padding: 0 1px 1px 0;
    font-weight: 500;
    font-size: 8px;
    display: block;
  }

  /*Chatbox scrollbar*/

  /*::-webkit-scrollbar {
 width: 6px;
 }

 ::-webkit-scrollbar-track {
 border-radius: 0;
 }

 ::-webkit-scrollbar-thumb {
 margin: 2px;
 border-radius: 10px;
 background: rgba(0, 0, 0, 0.2);
 }*/
  /*Element state*/

  #zcart_chat .is-active {
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg);
    -webkit-transition: all 1s ease-in-out;
    transition: all 1s ease-in-out;
  }

  #zcart_chat .is-float {
    box-shadow: 0 0 6px rgba(0, 0, 0, .16), 0 6px 12px rgba(0, 0, 0, .32);
  }

  #zcart_chat .is-loading {
    display: block;
    -webkit-animation: load 1s cubic-bezier(0, .99, 1, 0.6) infinite;
    animation: load 1s cubic-bezier(0, .99, 1, 0.6) infinite;
  }

  /*Animation*/

  @-webkit-keyframes zoomIn {
    0% {
      -webkit-transform: scale(0);
      transform: scale(0);
      opacity: 0.0;
    }

    100% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1;
    }
  }

  @keyframes zoomIn {
    0% {
      -webkit-transform: scale(0);
      transform: scale(0);
      opacity: 0.0;
    }

    100% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1;
    }
  }

  @-webkit-keyframes load {
    0% {
      -webkit-transform: scale(0);
      transform: scale(0);
      opacity: 0.0;
    }

    50% {
      -webkit-transform: scale(1.5);
      transform: scale(1.5);
      opacity: 1;
    }

    100% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 0;
    }
  }

  @keyframes load {
    0% {
      -webkit-transform: scale(0);
      transform: scale(0);
      opacity: 0.0;
    }

    50% {
      -webkit-transform: scale(1.5);
      transform: scale(1.5);
      opacity: 1;
    }

    100% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 0;
    }
  }

  /* SMARTPHONES PORTRAIT */

  @media only screen and (min-width: 300px) {
    #zcart_chat .chat {
      width: 250px;
    }
  }

  /* SMARTPHONES LANDSCAPE */
  @media only screen and (min-width: 480px) {
    #zcart_chat .chat {
      width: 300px;
    }

    #zcart_chat .chat_field {
      width: 65%;
    }
  }

  /* TABLETS PORTRAIT */
  @media only screen and (min-width: 768px) {
    #zcart_chat .chat {
      width: 300px;
    }

    #zcart_chat .chat_field {
      width: 65%;
    }
  }

  /* TABLET LANDSCAPE / DESKTOP */
  @media only screen and (min-width: 1024px) {
    #zcart_chat .chat {
      width: 300px;
    }

    #zcart_chat .chat_field {
      width: 65%;
    }
  }

  /*Color Options*/

  #zcart_chat .blue .fchat {
    background: #42a5f5;
    color: #fff;
  }

  #zcart_chat .blue .chat {
    background: #42a5f5;
    color: #999;
  }


  /* Ripple */

  #zcart_chat .ink {
    display: block;
    position: absolute;
    background: rgba(38, 50, 56, 0.4);
    border-radius: 100%;
    -moz-transform: scale(0);
    -ms-transform: scale(0);
    webkit-transform: scale(0);
    -webkit-transform: scale(0);
    transform: scale(0);
  }

  /*animation effecid	#zcart_chat .ink.animate {
 -webkit-animation: ripple 0.5s ease-in-out;
  animation: ripple 0.5s ease-in-out;
 }

 @-webkit-keyframes ripple {
 /*scale the element to 250% to safely cover the entire link and fade it out*/

  100% {
    opacity: 0;
    -moz-transform: scale(5);
    -ms-transform: scale(5);
    webkit-transform: scale(5);
    -webkit-transform: scale(5);
    transform: scale(5);
  }
  }

  @keyframes ripple {
    /*scale the element to 250% to safely cover the entire link and fade it out*/

    100% {
      opacity: 0;
      -moz-transform: scale(5);
      -ms-transform: scale(5);
      webkit-transform: scale(5);
      -webkit-transform: scale(5);
      transform: scale(5);
    }
  }

  ::-webkit-input-placeholder {
    /* Chrome */
    color: #bbb;
  }

  :-ms-input-placeholder {
    /* IE 10+ */
    color: #bbb;
  }

  ::-moz-placeholder {
    /* Firefox 19+ */
    color: #bbb;
  }

  :-moz-placeholder {
    /* Firefox 4 - 18 */
    color: #bbb;
  }

</style>
