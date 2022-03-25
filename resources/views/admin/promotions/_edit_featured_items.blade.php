<div class="modal-dialog modal-md">
  <div class="modal-content">
    {!! Form::open(['route' => 'admin.update.featuredItems', 'method' => 'PUT', 'id' => 'form', 'data-toggle' => 'validator']) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      {{ trans('app.featured_items') }}
    </div>
    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('featured_items', trans('app.featured_items')) !!}
        <select style="width: 100%" name="featured_items[]" multiple="multiple" class="form-control searchInventoryForSelect">
          @if (!empty($featured_items))
            @foreach ($featured_items as $key => $product)
              <option value="{{ $key }}" selected> {{ $product }} </option>
            @endforeach
          @endif
        </select>
        <div class="help-block with-errors"></div>
      </div>
    </div>
    <div class="modal-footer">
      {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
    </div>
    {!! Form::close() !!}
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
