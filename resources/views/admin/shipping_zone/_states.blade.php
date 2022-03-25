<div class="modal-dialog modal-sm">
    <div class="modal-content">
        {!! Form::model($shipping_zone, ['method' => 'PUT', 'route' => ['admin.shipping.shippingZone.updateStates', $shipping_zone->id, $country], 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            {{ trans('app.states') }}
        </div>
        <div class="modal-body">
            <div class="form-group">
              <div class="input-group input-group-lg">
                <span class="input-group-addon no-border"> <i class="fa fa-search text-muted"></i> </span>
                {!! Form::text('', null, ['id' => 'search_this', 'class' => 'form-control no-border', 'placeholder' => trans('app.placeholder.search')]) !!}
                <span class="input-group-addon no-border"> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{{ trans('help.shipping_zone_select_states') }}"></i> </span>
              </div>
            </div>

            <table class="table table-striped" id="search_table">
                <tbody>
                    @foreach(get_business_area_of($country) as $state)
                        <tr>
                            <td>
                                {!! Form::checkbox('states[]', $state->id, in_array($state->id, $shipping_zone->state_ids), ['id' => $state->id, 'class' => 'icheckbox_line', (! $state->in_active_business_area) ? 'disabled' : '']) !!}
                                {!! Form::label($state->name, $state->name, ['class' => 'indent5']) !!}

                                @unless($state->in_active_business_area)
                                    <i class="fa fa-question-circle pull-right" style="margin-top: -23px; margin-right: 10px; position:relative; z-index: 999" data-toggle="tooltip" title="{{ trans('help.not_in_business_area') }}"></i>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            {!! Form::submit(trans('app.update'), ['class' => 'btn btn-flat btn-new']) !!}
        </div>
        {!! Form::close() !!}
    </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->