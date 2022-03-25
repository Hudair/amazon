@if($product->shop && Auth::user()->isFromPlatform())
	<img src="{{ get_storage_file_url(optional($product->shop->logo)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
	<p class="indent10">
		@can('view', $product->shop)
			<a href="javascript:void(0)" data-link="{{ route('admin.vendor.shop.show', $product->shop->id) }}"  class="ajax-modal-btn">{{ $product->shop->name }}</a>
		@else
			{{ $product->shop->name }}
		@endcan
	</p>
@endif