@can('massDelete', \App\Models\Category::class)
  <td>
    @unless($category->products_count)
      <input id="{{ $category->id }}" type="checkbox" class="massCheck">
    @endunless
  </td>
@endcan
