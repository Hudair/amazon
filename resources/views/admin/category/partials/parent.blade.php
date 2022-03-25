<td>
  @if ($category->subGroup->group->deleted_at)
    <i class="fa fa-trash-o small"></i>
  @endif
  {!! $category->subGroup->group->name !!}
  &nbsp;<i class="fa fa-angle-double-right small"></i>&nbsp;
  @if ($category->subGroup->deleted_at)
    <i class="fa fa-trash-o small"></i>
  @endif
  {!! $category->subGroup->name !!}
</td>
