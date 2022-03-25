<div class="modal fade" id="disputeAppealModal" tabindex="-1" role="dialog" aria-labelledby="disputeAppealModal" aria-hidden="true">
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
            {{-- <i class="fa fa-"></i> --}}
            {{ trans('theme.appeal_dispute') }}
          </h4>
        </div>

        <div class="d-flex flex-column">
          {!! Form::model($order->dispute, ['method' => 'POST', 'route' => ['dispute.appeal', $order->dispute], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}

          @include('theme::partials._reply')

          <button type="submit" class="btn btn-primary btn-block btn-lg btn-round mt-3">
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
</div> <!-- / #disputeAppealModal -->
