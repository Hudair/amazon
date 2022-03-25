<div class="modal fade" id="disputeResponseModal" tabindex="-1" role="dialog" aria-labelledby="disputeResponseModal" aria-hidden="true">
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
            {{ trans('theme.dispute') }}
          </h4>
        </div>

        <div class="d-flex flex-column">
          {!! Form::model($order->dispute, ['method' => 'POST', 'route' => ['dispute.response', $order->dispute], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
          {{-- <div class="row select-box-wrapper">
            <div class="form-group">
              {!! Form::label('status', trans('theme.status') . '*') !!}
              {!! Form::select('status', \App\Helpers\ListHelper::dispute_statuses(), null, ['class' => 'selectBoxIt', 'required']) !!}
              <div class="help-block with-errors"></div>
            </div>
          </div> --}}

          @include('theme::partials._reply')

          <div class="form-group my-4">
            <label>
              {!! Form::checkbox('solved', null, null, ['class' => 'i-check']) !!} {{ trans('theme.mark_as_solved') }}
            </label>
          </div>

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
</div> <!-- / #disputeResponseModal -->
