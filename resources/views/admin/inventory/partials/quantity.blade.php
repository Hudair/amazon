@if(Gate::allows('update', $inventory))
    <a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.editQtt', $inventory->id) }}" class="ajax-modal-btn qtt-{{$inventory->id}}" data-toggle="tooltip" data-placement="top" title="{{ trans('app.update') }}">
        {{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}
    </a>
@else
    {{ ($inventory->stock_quantity > 0) ? $inventory->stock_quantity : trans('app.out_of_stock') }}
@endif