{{ trans('app.of_total', ['first' => $paginator->firstItem(), 'last' => $paginator->lastItem(), 'total' => $paginator->total()]) }}

<div class="btn-group">
	@if ($paginator->onFirstPage())
		<button type="button" class="btn btn-default btn-sm disabled"><i class="fa fa-chevron-left"></i></button>
    @else
		<a href="{{ $paginator->previousPageUrl() }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
    @endif

	@if ($paginator->hasMorePages())
		<a href="{{ $paginator->nextPageUrl() }}" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
    @else
		<button type="button" class="btn btn-default btn-sm disabled"><i class="fa fa-chevron-right"></i></button>
    @endif
</div>