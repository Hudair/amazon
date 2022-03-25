<td>
  <h5>
    {{ $category->name }}
    @if ($category->featured)
      <small class="label label-primary indent10">{{ trans('app.featured') }}</small>
    @endif
    @unless($category->active)
      <span class="label label-default indent5 small">{{ trans('app.inactive') }}</span>
    @endunless
  </h5>
  @if ($category->description)
    <span class="excerpt-td small">
      {!! Str::limit($category->description, 200) !!}
    </span>
  @endif
</td>
