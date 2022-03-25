@if($product->featureImage)
	<img src="{{ get_storage_file_url(optional($product->featureImage)->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.featured_image') }}">
@else
	<img src="{{ get_storage_file_url(optional($product->image)->path, 'tiny') }}" class="img-sm" alt="{{ trans('app.image') }}">
@endif