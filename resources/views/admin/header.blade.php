<!-- Main Header -->
<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('/') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">{{ Str::limit(get_site_title(), 2, '.') }}</span>

    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">{{ get_site_title() }}</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="javascript::void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <ul class="nav navbar-nav hidden-xs">
      <li>
        <a href="{{ route('admin.account.profile') }}">
          {{ trans('app.welcome') . ' ' . Auth::user()->getName() }}
        </a>
      </li>

      <li>
        <a href="{{ get_shop_url() }}" target="_blank">
          <i class="fa fa-external-link"></i> {{ trans('app.store_front') }}
        </a>
      </li>
    </ul>

    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages Menu-->
        <li class="dropdown messages-menu">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope-o"></i>
            @if ($count_message = $unread_messages->count())
              <span class="label label-success">{{ $count_message }}</span>
            @endif
          </a>
          <ul class="dropdown-menu">
            <li class="header">{{ trans('messages.message_count', ['count' => $count_message]) }}</li>
            <li>
              <ul class="menu">
                @forelse($unread_messages as $message)
                  @continue($loop->index > 5)

                  <li>
                    <!-- start message -->
                    <a href="{{ route('admin.support.message.show', $message) }}">
                      <div class="pull-left">
                        <img src="{{ get_avatar_src($message->customer, 'tiny') }}" class="img-circle" alt="{{ trans('app.avatar') }}">
                      </div>
                      <h4>
                        {!! $message->subject !!}
                        <small><i class="fa fa-clock-o"></i> {{ $message->created_at->diffForHumans() }}</small>
                      </h4>

                      <p>
                        {{ Str::limit($message->message, 100) }}
                      </p>
                    </a>
                  </li>
                @endforeach
              </ul><!-- /.menu -->
            </li>
            <li class="footer"><a href="{{ url('admin/support/message/labelOf/' . \App\Models\Message::LABEL_INBOX) }}">{{ trans('app.go_to_msg_inbox') }}</a></li>
          </ul>
        </li><!-- /.messages-menu -->

        <!-- Notifications Menu -->
        <li class="dropdown notifications-menu" id="notifications-dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            @if ($count_notification = Auth::user()->unreadNotifications->count())
              <span class="label label-warning">{{ $count_notification }}</span>
            @endif
          </a>
          <ul class="dropdown-menu">
            <li class="header">{{ trans('messages.notification_count', ['count' => $count_notification]) }}</li>
            <li>
              <ul class="menu">
                @foreach (Auth::user()->unreadNotifications as $notification)
                  <li>
                    @php
                      $notification_view = 'admin.partials.notifications.' . Str::snake(class_basename($notification->type));
                    @endphp

                    @includeFirst([$notification_view, 'admin.partials.notifications.default'])
                  </li>
                @endforeach
              </ul><!-- .menu -->
            </li>
            <li class="footer"><a href="{{ route('admin.notifications') }}">{{ trans('app.view_all_notifications') }}</a></li>
          </ul>
        </li><!-- /.notifications-menu -->

        <!-- Announcement Menu -->
        @if ($active_announcement = get_global_announcement())
          <li class="dropdown tasks-menu" id="announcement-dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bullhorn"></i>
              @if ($active_announcement && $active_announcement->updated_at > Auth::user()->read_announcements_at)
                <span class="label"><i class="fa fa-circle"></i></span>
              @endif
            </a>
            <ul class="dropdown-menu">
              <li>
                {!! $active_announcement->parsed_body !!}
                @if ($active_announcement->action_url)
                  <span class="indent10">
                    <a href="{{ $active_announcement->action_url }}" class="btn btn-flat btn-default btn-xs">{{ $active_announcement->action_text }}</a>
                  </span>
                @endif
              </li>
            </ul>
          </li><!-- /.notifications-menu -->
        @endif

        <!-- Wallet -->
        @if (Auth::user()->isMerchant() && is_incevio_package_loaded('wallet'))
          <li class="dropdown tasks-menu" id="wallet-dropdown">
            <a href="{{ route('merchant.wallet') }}">
              <span>{{ trans('wallet::lang.balance') }}: </span>
              {{ get_formated_currency(Auth::user()->shop->balance, config('system_settings.decimals', 2)) }}
            </a>
          </li>
        @endif

        <li class="user user-menu">
          <a href="{{ route('admin.account.profile') }}">
            @if (Auth::user()->image)
              <img src="{{ get_storage_file_url(Auth::user()->image->path, 'tiny') }}" class="user-image" alt="{{ trans('app.avatar') }}">
            @else
              <img src="{{ get_gravatar_url(Auth::user()->email, 'tiny') }}" class="user-image" alt="{{ trans('app.avatar') }}">
            @endif
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ trans('app.account') }}</span>
          </a>
        </li>

        <li>
          <a href="{{ Request::session()->has('impersonated') ? route('admin.secretLogout') : route('logout') }}"><i class="fa fa-sign-out"></i> <span class="hidden-xs">{{ trans('app.log_out') }}</span></a>
        </li>

        <li>
          {{-- <a href="javascript:void(0)" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> --}}
        </li>
      </ul>
    </div>
  </nav>
</header>
