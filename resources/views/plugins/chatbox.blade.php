<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@if(env('APP_DEBUG', false))
<script type="text/javascript">
"use strict";
  ;(function($, window, document) {
      $(document).ready(function(){
        Pusher.logToConsole = true; // Enable pusher logging - don't include this in production
    });
  }(window.jQuery, window, document));
</script>
@endif

<script type="text/javascript">
;(function($, window, document) {
  function prepareNewConversation(msgObj)
  {
      var link = '{{ route("admin.support.chat_conversation.show", ":slug") }}';
      link = link.replace(':slug', msgObj.conversation_id);

      return $('<div>').attr('id', 'chat-'+msgObj.customer_id).addClass('row sidebarBody').append(
            $('<a>').attr('href', 'javascript:void(0)').attr('data-link', link).addClass('get-content').append(
              $('<div>').addClass('col-sm-3 col-xs-3').append(
                  $('<img/>').attr('src', msgObj.avatar).attr('alt', '{{ trans('app.avatar') }}').addClass('img-circle')
              )
            ).append(
              $('<div>').addClass('col-sm-9 col-xs-9 sideBar-main nopadding').append(
                $('<div>').addClass('row').append(
                  $('<div>').addClass('col-sm-8 col-xs-8 sideBar-name').append(
                    $('<span>').addClass('name-meta strong').text(msgObj.sender).append(
                      $('<span>').addClass('label label-primary flat indent10').text(msgObj.status)
                    )
                  ).append(
                    $('<p>').addClass('excerpt strong').text( getExcerptMsg(msgObj.text) )
                  )
                ).append(
                  $('<div>').addClass('col-sm-4 col-xs-4 pull-right time').append(
                    $('<span>').addClass('time-meta pull-right').text(msgObj.time)
                  )
                )
              )
            )
      );
  }

  function prepareNewChatMsg(txt, who = 'sender')
  {
      return $('<div>').addClass('row message-body').append(
            $('<div>').addClass('col-sm-12 message-main-'+who).append(
              $('<div>').addClass(who).append(
                  $('<div>').addClass('message-text').text(txt)
              )
            ).append(
              $('<span>').addClass('message-time').text('{{ trans('theme.now') }}')
            )
      );
  }

  function getExcerptMsg(text)
  {
    return text.substring(0, 120);
  }

  function markAsUnread(chatNode)
  {
    var label = chatNode.find(".label");

    if(label.hasClass('hide')) {
      label.removeClass('hide'); // Show unread label
    }
    else {
      chatNode.find(".name-meta, p.excerpt").addClass('strong');
    }
  }

  function markAsRead(chatNode)
  {
    chatNode.find(".name-meta, p.excerpt").removeClass('strong');
    chatNode.find(".label").addClass('hide'); // Hide unread label
  }

  $( document ).ready(function () {
    $('body').on('click', 'a.get-content', function(e) {
        e.preventDefault();
        $('.loader').show();
        var node = $(this);
        $.get(node.data('link'), function(data) {
          $('.loader').hide();
          $('#chatConversation').html(data); //Display the result
          updateScroll('conversationBox'); //Scroll to bottom
          markAsRead(node); // Mark as read
        });
    });

    $('body').on('click', 'i#send-btn', function(e) {
        e.preventDefault();

        var msg = $.trim($("textarea#message").val());
        var form = $(this).parents("form#chat-form");
        var response = '';

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            complete: function (xhr, textStatus) {
              switch (xhr.status) {
                case 200:
                    $("textarea#message").val('');
                    response = prepareNewChatMsg(msg, 'sender');
                    break;
                case 401:
                    $("#conversationBox").html(""); //Clear the chatbox
                    response = $('<p>').addClass('text-danger').text("{!! trans('messages.session_expired') !!}");
                    $('<br/><br/>').prependTo(response);
                    $('<a>').attr('href', "javascript:void(0)").attr('data-toggle', "modal").attr('data-target', "#loginModal").addClass('btn btn-primary').text("{{ trans('app.login') }}").appendTo(response);
                    break;
                case 403:
                case 419:
                    $("#conversationBox").html(""); //Clear the chatbox
                    response = $('<p>').addClass('text-danger').text("{!! trans('messages.session_expired') !!}");
                    $('<br/><br/>').prependTo(response);
                    $('<a>').attr('href', "{{ route('customer.login') }}").addClass('btn btn-primary').text("{{ trans('app.login') }}").appendTo(response);
                    break;
                default:
                    response = $('<div>').addClass('row message-body').append(
                          $('<div>').addClass('col-sm-12').append(
                            $('<p class="lead">').addClass('text-danger').text("{!! trans('messages.failed') !!}")
                          )
                    );
                    $('<br/><br/>').prependTo(response);
              }

              $("#conversationBox").append(response);

              updateScroll('conversationBox'); //Scroll to bottom
            },
        });
      // $("#chat-form").submit();
    });

    // Pusher script
    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: '{{env('PUSHER_APP_CLUSTER')}}',
      forceTLS: true
    });

    var channel = pusher.subscribe('{{get_vendor_chat_room_id()}}');
    channel.bind('zcart-chat-new-message', function(result) {
      // Check if the coversation is already exist
      var chatNode = $('#chat-' + result.customer_id);
      if(chatNode.length === 0) { //It message is from a new customer
        var newChat = prepareNewConversation(result);

        $("#leftsidebar .sidebarContent").append(newChat);
      }
      else {  //Old customer
        var openChatbox = document.getElementById("openChatbox-" + result.customer_id);
        if(openChatbox) { //The chatbox is already open
            response = prepareNewChatMsg( getExcerptMsg(result.text), 'receiver');
            $("#conversationBox").append(response);
            updateScroll('conversationBox'); //Scroll to bottom
        }
        else { //Chatbox is not open
          markAsUnread(chatNode); // Mark as unread
        }

        chatNode.find("p.excerpt").text( getExcerptMsg(result.text) ); // Update the excerpt on left menu
        chatNode.find(".time span").text(result.time); // Update the time on left menu
      }

        // var response = $('<span>').addClass('chat_msg_item');
          // response.addClass('chat_msg_item_admin').text(result.text);
          // agent_avatar.prependTo(response);
          // $("#chat_conversation").append(response);
          // updateScroll('conversationBox'); //Scroll to bottom
    });
  });
}(window.jQuery, window, document));
</script>

<style type="text/css">
    #chatbox {
      overflow: hidden;
      top: 19px;
      height: calc(100% - 38px);
      margin: auto;
      padding: 0;
      color: #666;
    }

    #chatbox .chatContent {
      width: 100%;
      overflow: hidden;
      margin: 0;
      padding: 0;
    }

    .side {
      padding: 0;
      margin: 0;
    }
    #leftsidebar {
      padding: 0;
      margin: 0;
      /*height: 100%;*/
      width: 100%;
      z-index: 1;
      position: relative;
      display: block;
      top: 0;
    }

    #leftsidebar a {
      color: #666;
    }

    .heading {
      padding: 10px 16px 10px 15px;
      margin: 0;
      height: 60px;
      width: 100%;
      background-color: #eee;
      z-index: 1000;
    }

    .heading-avatar {
      padding: 0;
      cursor: pointer;

    }

    .heading-avatar img {
      border-radius: 50%;
      height: 40px;
      width: 40px;
    }

    .heading-name {
      padding: 0 !important;
      /*cursor: pointer;*/
    }

    .heading-name-meta {
      font-weight: 700;
      font-size: 100%;
      padding: 5px;
      padding-bottom: 0;
      text-align: left;
      text-overflow: ellipsis;
      white-space: nowrap;
      color: #000;
      display: block;
    }
    .heading-online {
      display: none;
      padding: 0 5px;
      font-size: 12px;
      color: #93918f;
    }
    .heading-compose {
      padding: 0;
    }

    .heading-compose i {
      text-align: center;
      padding: 5px;
      color: #93918f;
      cursor: pointer;
    }

    .heading-dot {
      padding: 0;
      margin-left: 10px;
    }

    .heading-dot i {
      text-align: right;
      padding: 5px;
      color: #93918f;
      cursor: pointer;
    }

    .searchBox {
      padding: 0 !important;
      margin: 0 !important;
      height: 60px;
      width: 100%;
    }

    .searchBox-inner {
      height: 100%;
      width: 100%;
      padding: 10px !important;
      background-color: #fbfbfb;
    }


    /*#searchBox-inner input {
      box-shadow: none;
    }*/

    .searchBox-inner input:focus {
      outline: none;
      border: none;
      box-shadow: none;
    }

    .sidebarContent {
      padding: 0 !important;
      margin: 0 !important;
      background-color: #fff;
      min-height: 350px;
      max-height: 500px;
      overflow-y: auto;
      border: 1px solid #f7f7f7;
      height: calc(100% - 120px);
    }

    .sidebarBody {
      position: relative;
      padding: 10px !important;
      border-bottom: 1px solid #f7f7f7;
      height: 72px;
      margin: 0 !important;
      cursor: pointer;
    }

    .sidebarBody.active,
    .sidebarBody:hover {
      background-color: #f2f2f2;
    }

    .sidebarContent img {
      height: 49px;
      width: 49px;
    }

    .sideBar-main .row {
      padding: 0 !important;
      margin: 0 !important;
    }

    .sideBar-name {
      padding: 0 !important;
    }

    .name-meta {
      font-size: 1.2em;
      text-align: left;
      text-overflow: ellipsis;
      white-space: nowrap;
      color: #000;
    }

    .name-meta .label{
      zoom: 80%;
    }

    .sidebarContent .time {
      padding: 10px 0px !important;
    }

    .time-meta {
      text-align: right;
      font-size: 11px;
      /*padding: 1% !important;*/
      color: rgba(0, 0, 0, .4);
      vertical-align: baseline;
    }

    .composeBox {
      padding: 0 !important;
      margin: 0 !important;
      height: 60px;
      width: 100%;
    }

    .composeBox-inner {
      height: 100%;
      width: 100%;
      padding: 10px !important;
      background-color: #fbfbfb;
    }

    .composeBox-inner input:focus {
      outline: none;
      border: none;
      box-shadow: none;
    }

    .compose-sideBar {
      padding: 0 !important;
      margin: 0 !important;
      background-color: #fff;
      overflow-y: auto;
      border: 1px solid #f7f7f7;
      height: calc(100% - 160px);
    }

    /*Conversation*/
    .conversation {
      padding: 0 !important;
      margin: 0 !important;
      height: 100%;
      /*width: 100%;*/
      border-left: 1px solid rgba(0, 0, 0, .08);
      overflow-y: auto;
    }

    .message {
      padding: 20px 0 !important;
      margin: 0 !important;
      background: url("w.jpg") no-repeat fixed center;
      background-size: cover;
      overflow-y: auto;
      border: 1px solid #f7f7f7;
      height: 350px;
      /*height: calc(100% - 120px);*/
    }
    .message-previous {
      margin : 0 !important;
      padding: 0 !important;
      height: auto;
      width: 100%;
    }
    .previous {
      font-size: 15px;
      text-align: center;
      padding: 10px !important;
      cursor: pointer;
    }

    .previous a {
      text-decoration: none;
      font-weight: 700;
    }

    .message-body {
      margin: 0 0 3px 0 !important;
      padding: 0 !important;
      width: auto;
      height: auto;
    }

    .message-main-receiver {
      /*padding: 10px 20px;*/
      max-width: 60%;
    }

    .message-main-sender {
      padding: 3px 20px !important;
      margin-left: 40% !important;
      max-width: 60%;
    }

    .message-text {
      margin: 0 !important;
      padding: 5px !important;
      word-wrap:break-word;
      font-weight: 200;
      font-size: 14px;
      padding-bottom: 0 !important;
    }

    .message-main-receiver .message-time {
      margin: 0 !important;
      margin-left: 9px !important;
      font-size: 0.8em;
      /*text-align: right;*/
      color: #9a9a9a;
    }

    .message-main-sender .message-time {
      float: right;
      margin: 9px 9px 0 0 !important;
      font-size: 0.8em;
      /*text-align: right;*/
      color: #9a9a9a;
    }

    .receiver {
      width: auto !important;
      padding: 4px 10px 7px !important;
      border-radius: 10px 10px 10px 0;
      background: #ffffff;
      font-size: 12px;
      text-shadow: 0 1px 1px rgba(0, 0, 0, .2);
      word-wrap: break-word;
      display: inline-block;
    }

    .sender {
      float: right;
      width: auto !important;
      background: #dcf8c6;
      border-radius: 10px 10px 0 10px;
      padding: 4px 10px 7px !important;
      font-size: 12px;
      text-shadow: 0 1px 1px rgba(0, 0, 0, .2);
      display: inline-block;
      word-wrap: break-word;
    }

    /*Reply*/
    .reply {
      height: 60px;
      width: 100%;
      background-color: #f5f1ee;
      padding: 10px 5px 10px 5px !important;
      margin: 0 !important;
      z-index: 1000;
    }
    .reply i {
      text-align: center;
      padding: 5px !important;
      color: #93918f;
      cursor: pointer;
    }

    .reply-main {
      padding: 2px 0px !important;
    }

    .reply-main textarea {
      width: 100%;
      resize: none;
      overflow: hidden;
      padding: 8px !important;
      outline: none;
      border: none;
      text-indent: 5px;
      box-shadow: none;
      height: 100%;
      font-size: 16px;
    }

    .reply-main textarea:focus {
      outline: none;
      border: none;
      text-indent: 5px;
      box-shadow: none;
    }

    @media screen and (max-width: 700px) {
      #chatbox {
        top: 0;
        height: 100%;
      }
      .heading {
        height: 70px;
        background-color: #009688;
      }
      .fa-2x {
        font-size: 2.3em !important;
      }
      .heading-avatar {
        padding: 0 !important;
      }
      .heading-avatar img {
        height: 50px;
        width: 50px;
      }
      .heading-compose {
        padding: 5px !important;
      }
      .heading-compose i {
        color: #fff;
        cursor: pointer;
      }
      .heading-dot {
        padding: 5px !important;
        margin-left: 10px !important;
      }
      .heading-dot i {
        color: #fff;
        cursor: pointer;
      }
      .sidebarContent {
        height: calc(100% - 130px);
      }
      .sidebarBody {
        height: 80px;
      }
      .sidebarContent img {
        height: 55px;
        width: 55px;
      }
      .sideBar-main .row {
        padding: 0 !important;
        margin: 0 !important;
      }
      .sideBar-name {
        padding: 10px 5px !important;
      }
      .name-meta {
        font-size: 16px;
        padding: 5% !important;
      }
      .time-meta {
        text-align: right;
        font-size: 12px;
        padding: 4% !important;
        color: rgba(0, 0, 0, .4);
        vertical-align: baseline;
      }
      /*Conversation*/
      .conversation {
        padding: 0 !important;
        margin: 0 !important;
        height: 100%;
        /*width: 100%;*/
        border-left: 1px solid rgba(0, 0, 0, .08);
        /*overflow-y: auto;*/
      }
      .message {
        height: calc(100% - 140px);
      }
      .reply {
        height: 70px;
      }
      .reply i {
        padding: 5px 2px !important;
        font-size: 1.8em !important;
      }
      .reply-main {
        padding: 2px 8px !important;
      }
      .reply-main textarea {
        padding: 8px !important;
        font-size: 18px;
      }
      .reply-send i {
        padding: 5px 2px 5px 0 !important;
        font-size: 1.8em !important;
      }
    }
</style>