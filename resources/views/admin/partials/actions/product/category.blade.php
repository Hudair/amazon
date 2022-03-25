@foreach($product->categories as $category)
	<span class="label label-outline">{{ $category->name }}</span>
	@if($loop->iteration > 2)
		<span class="text-primary indent10">
			{!! trans('app.and_in_more_categories', ['count' => $product->categories->count()]) !!}
		</span>

		@break
	@endif
@endforeach
