<div class="modal-dialog modal-md modal-dialog-centered" role="document">
  <div class="modal-content p-2">
    <div class="modal-header p-3">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-title text-center">
        <h4>
          {{ trans('theme.button.add_new_address') }}
        </h4>
      </div>

      <div class="d-flex flex-column">
        {!! Form::open(['route' => 'my.address.save', 'data-toggle' => 'validator']) !!}
        @if (isset($address_types))
          <div class="form-group">
            {!! Form::select('address_type', $address_types, null, ['class' => 'form-control flat', 'placeholder' => trans('theme.placeholder.address_type') . '*', 'required']) !!}
            <div class="help-block with-errors"></div>
          </div>
        @endif

        @include('partials.address_form')

        <button type="submit" class="btn btn-primary btn-block btn-lg btn-round mt-3">
          {{-- <i class="fas fa-save"></i> --}}
          {{ trans('theme.button.submit') }}
        </button>
        {!! Form::close() !!}
      </div>
      <small class="help-block text-muted text-left mt-4">* {{ trans('theme.help.required_fields') }}</small>
    </div>

    <div class="modal-footer d-flex justify-content-center">
      <div class="signup-section">
      </div>
    </div>
  </div>
</div>
