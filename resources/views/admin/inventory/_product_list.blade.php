<div class="admin-user-widget">
  <span class="admin-user-widget-img">
    <img src="{{ get_catalog_featured_img_src($product->id, 'small') }}" class="thumbnail" alt="{{ trans('app.image') }}">
  </span>
  <div class="admin-user-widget-content">
    <span class="admin-user-widget-title">
      {{ $product->name }}
    </span>
    <span class="admin-user-widget-text text-muted">
      {{ $product->gtin_type ?? 'GTIN: ' }} {{ ': ' . $product->gtin }}
    </span>
    <span class="admin-user-widget-text text-muted">
      {{ trans('app.model_number') . ': ' . $product->model_number }}
    </span>
    <span class="admin-user-widget-text text-muted">
      {{ trans('app.brand') . ': ' . $product->brand }}
    </span>

    @can('view', $product)
      <a href="javascript:void(0)" data-link="{{ route('admin.catalog.product.show', $product->id) }}" class="ajax-modal-btn small">{{ trans('app.view_detail') }}</a>
    @endcan

    <span class="option-btn" style=" margin-top: -50px;">

      @if ($product->hasAttributes())
        <a href="javascript:void(0)" data-link="{{ route('admin.stock.inventory.setVariant', $product->id) }}" class="ajax-modal-btn btn bg-olive btn-flat">{{ trans('app.add_to_inventory_with_variant') }}</a>
      @endif

      <a href="{{ route('admin.stock.inventory.add', $product->id) }}" class="btn bg-purple btn-flat">{{ trans('app.add_to_inventory') }}</a>
    </span>
  </div> <!-- /.admin-user-widget-content -->
</div> <!-- /.admin-user-widget -->
