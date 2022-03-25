@if ($messages->count() > 0)
  @php
    $search_q = isset($search_q) ? $search_q : null;
  @endphp

  <table class="table table-hover table-striped message-inbox">
    <thead>
      <tr>
        <td colspan="6">{{ trans('theme.of_total', ['first' => $messages->firstItem(), 'last' => $messages->lastItem(), 'total' => $messages->total()]) . ' ' . trans('theme.my_messages') }}</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($messages as $message)
        <tr id="item_{{ $message->id }}">
          <td class="mailbox-name" width="15%">
            @if ($message->shop)
              <a href="{{ route('show.store', $message->shop->slug) }}">
                <img src="{{ get_storage_file_url(optional($message->shop->image)->path, 'thumbnail') }}" class="seller-info-logo img-circle" alt="{{ trans('theme.logo') }}">
                {!! $message->shop->getQualifiedName() !!}
              </a>
            @else
              {{ trans('theme.store') }}
            @endif
          </td>
          <td class="mailbox-subject" width="60%">
            <a href="{{ route('message.show', $message) }}" class="{{ $message->isUnread() ? 'unread' : '' }}">
              <span>{!! highlightWords($message->subject, $search_q) !!} </span> - {!! highlightWords(\Illuminate\Support\Str::limit(strip_tags($message->message), 180 - strlen($message->subject)), $search_q) !!}
            </a>
          </td>
          <td class="mailbox-attachment">
            @if ($message->replies_count)
              <span class="label label-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('app.replies') }}">{{ $message->replies_count }}</span>
            @endif
            @if ($message->hasAttachments())
              <i class="fas fa-paperclip" data-toggle="tooltip" data-placement="top" title="{{ trans('app.attachments') }}"></i>
            @endif
          </td>
          <td>
            <small>
              @if ($message->isUnread())
                {!! $message->statusName() !!}
              @endif

              @if ($message->about())
                {!! $message->about() !!}
              @endif
            </small>
          </td>
          <td class="mailbox-date">{{ $message->updated_at->diffForHumans() }}</td>
          <td>
            @if ($message->order_id)
              <a href="{{ route('order.detail', $message->order_id) }}" data-toggle="tooltip" data-placement="left" data-title="{{ trans('theme.button.order_detail') }}"><i class="fas fa-shopping-cart"></i></a>
            @endif

            @if ($message->product_id)
              <a href="{{ route('show.product', $message->item->slug) }}" data-toggle="tooltip" data-placement="left" data-title="{{ trans('theme.button.view_product_details') }}"><i class="far fa-external-link"></i></a>
            @endif

            <a href="{{ route('message.archive', $message) }}" class="confirm" data-toggle="tooltip" data-placement="left" data-title="{{ trans('theme.archive') }}"><i class="fas fa-trash-o"></i></a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="sep"></div>
@else
  <div class="clearfix space50"></div>
  <p class="lead text-center space50">
    @lang('theme.nothing_found')
  </p>
@endif

<div class="row pagenav-wrapper">
  {{ $messages->links('theme::layouts.pagination') }}
</div><!-- /.row .pagenav-wrapper -->
<div class="clearfix space20"></div>
