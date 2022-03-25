<script type="text/javascript">
  // var jq214 = jQuery.noConflict(true);
  ;
  (function($, window, document) {
    $(document).ready(function() {
      let count = 0;

      $('#addMilestone').on('click', function() {
        count = count + 1;

        let milestoneDOM = '<div class="row" id="virtual_milestone' + count + '"><div class="col-sm-6"><div class="form-group"><div class="input-group"><span class="input-group-addon">{{ config('system_settings.currency_symbol', '$') }}</span>{!! Form::number('milestones[amounts][]', null, ['min' => 0, 'steps' => 'any', 'class' => 'form-control', 'placeholder' => trans('dynamicCommission::lang.milestone_amount'), 'required']) !!}<span class="input-group-addon">{{ trans('dynamicCommission::lang.and_up') }}</span></div><div class="help-block with-errors"></div></div></div><div class="col-sm-4 nopadding-left"><div class="form-group"><div class="input-group">{!! Form::number('milestones[commissions][]', 0, ['min' => 0, 'steps' => 'any', 'class' => 'form-control', 'placeholder' => trans('dynamicCommission::lang.commission'), 'required']) !!}<span class="input-group-addon">{{ trans('app.percent') }}</span></div><div class="help-block with-errors"></div></div></div><div class="col-sm-2 nopadding-left"><button type="button" onclick="removeMilestone(\'virtual_milestone' + count + '\')" class="btn btn-danger"><i class="fa fa-times"></i></button></div></div>';

        $('#virtual_milestones').append(milestoneDOM);
      });
    });
  }(window.jQuery, window, document));

  function removeMilestone(id) {
    document.getElementById(id).remove();
  }
</script>
