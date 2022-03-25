@if ($ratings)
  @for ($i = 0; $i < 5; $i++)
    <a href="javascript:void(0)">
      @if ($ratings - $i >= 1)
        <i class="fas fa-star"></i>
      @elseif( ($ratings - $i) < 1 && ($ratings - $i)> 0 )
          <i class="fas fa-star-half-alt"></i>
        @else
          <i class="far fa-star"></i>
      @endif
    </a>
  @endfor
@endif
