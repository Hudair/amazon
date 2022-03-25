<div class="modal-dialog modal-md">
  <div class="modal-content">
    <div class="modal-body" style="padding: 0px;">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 5px; right: 10px; z-index: 9;">×</button>
      <div class="card hovercard">
        <div class="card-background">
          <img src="{{ get_storage_file_url(optional($deliveryboy->image)->path, 'small') }}" class="card-bkimg img-circle" alt="{{ trans('app.avatar') }}">
        </div>
        <div class="useravatar">
          <img src="{{ get_avatar_src($deliveryboy, 'small') }}" class="img-circle" alt="{{ trans('app.avatar') }}">
        </div>
        <div class="card-info">
          <span class="card-title">{{ $deliveryboy->getName() }}</span>
        </div>
      </div>

      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs nav-justified">
          <li class="active"><a href="#tab_1" data-toggle="tab">
              {{ trans('app.basic_info') }}
            </a></li>
          <li><a href="#tab_3" data-toggle="tab">
              {{ trans('app.contact') }}
            </a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <table class="table">
              @if ($deliveryboy->nice_name)
                <tr>
                  <th>{{ trans('app.full_name') }}: </th>
                  <td>{{ $deliveryboy->nice_name }}</td>
                </tr>
              @endif
              @if ($deliveryboy->shop)
                <tr>
                  <th>{{ trans('app.shop') }}: </th>
                  <td>{{ $deliveryboy->shop->name }}</td>
                </tr>
              @endif
              @if ($deliveryboy->dob)
                <tr>
                  <th>{{ trans('app.dob') }}: </th>
                  <td>{!! date('F j, Y', strtotime($deliveryboy->dob)) . '<small> (' . get_age($deliveryboy->dob) . ')</small>' !!}</td>
                </tr>
              @endif
              @if ($deliveryboy->sex)
                <tr>
                  <th>{{ trans('app.sex') }}: </th>
                  <td>{!! get_formated_gender($deliveryboy->sex) !!}</td>
                </tr>
              @endif
              <tr>
                <th>{{ trans('app.status') }}: </th>
                <td>{{ $deliveryboy->active ? trans('app.active') : trans('app.inactive') }}</td>
              </tr>
              @if ($deliveryboy->created_at)
                <tr>
                  <th>{{ trans('app.member_since') }}: </th>
                  <td>{{ $deliveryboy->created_at->diffForHumans() }}</td>
                </tr>
              @endif
            </table>
          </div> <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_3">
            <table class="table">
              <tr>
                <th class="text-right">{{ trans('app.phone_number') }}:</th>
                <td style="width: 75%;">{{ $deliveryboy->phone_number }}</td>
              </tr>
              <tr>
                <th class="text-right">{{ trans('app.email') }}:</th>
                <td style="width: 75%;">{{ $deliveryboy->email }}</td>
              </tr>
              <tr>
                <th class="text-right">{{ trans('app.address') }}:</th>
                <td style="width: 75%;">{{ $deliveryboy->address }}</td>
              </tr>
            </table>
          </div> <!-- /.tab-pane -->
        </div> <!-- /.tab-content -->
      </div>
    </div>
  </div> <!-- / .modal-content -->
</div> <!-- / .modal-dialog -->
