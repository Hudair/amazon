<div class="modal fade" id="shipToModal" tabindex="-1" role="dialog" aria-labelledby="shipToModal" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content p-2">
      <div class="modal-header p-3">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-title text-center">
          <h4>
            <i class="fa fa-map-marker-alt"></i>
            {{ trans('theme.choose_delivery_location') }}
          </h4>
        </div>

        <div class="d-flex flex-column text-center">
          {!! Form::open(['route' => ['register'], 'data-toggle' => 'validator', 'id' => 'shipToForm']) !!}
          {{ Form::hidden('cart', null, ['id' => 'cartinfo']) }} {{-- For the carts page --}}

          <div class="row select-box-wrapper">
            <div class="form-group col-md-12">
              <label for="shipTo_country" class="text-left">
                {{ trans('theme.country') }}:
              </label>
              <select name="ship_to" id="shipTo_country" required="required">
                @foreach ($business_areas as $country)
                  <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
              </select>
              <div class="help-block with-errors"></div>
            </div>
          </div>

          <div class="row select-box-wrapper hidden" id="state_id_select_wrapper">
            <div class="form-group col-md-12">
              <label for="shipTo_state" class="text-left">
                {{ trans('theme.placeholder.state') }}:
              </label>
              <select name="state_id" id="shipTo_state" class="selectBoxIt"></select>
              <div class="help-block with-errors"></div>
            </div>
          </div>

          <p class="small text-left"><i class="fas fa-info-circle"></i> {!! trans('theme.delivery_locations_info') !!}</p>

          <input class="btn btn-primary btn-block btn-lg btn-round mt-3" type="submit" value="{{ trans('theme.button.submit') }}">
          {!! Form::close() !!}
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <div class="signup-section">
        </div>
      </div>
    </div>
  </div>
</div> <!-- /#passwordResetModal -->
