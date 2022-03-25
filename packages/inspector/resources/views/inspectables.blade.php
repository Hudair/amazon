@extends('admin.layouts.master')

@section('content')
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('inspector::lang.inspectable') }}</h3>
          <div class="box-tools pull-right"></div>
        </div> <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-hover table-no-sort">
                <thead>
                    <tr>
                        <th>{{ trans('inspector::lang.type') }}</th>
                        <th>{{ trans('inspector::lang.prohibited') }}</th>
                        <th>{{ trans('inspector::lang.attempt') }}</th>
                        <th>{{ trans('inspector::lang.status') }}</th>
                        <th>{{ trans('inspector::lang.option') }}</th>
                    </tr>
                </thead>
                <tbody id="massSelectArea">
                    @foreach($inspectables as $inspectable)
                        @if(! empty($inspectable))
                            @php
                                $class = new $inspectable->inspectable_type();
                                $type = $class->getTable();
                            @endphp
                            <tr>
                                <td>{{ $type }}</td>
                                <td>{{ $inspectable->caught }} </td>
                                <td>{{ $inspectable->attempts }}</td>
                                <td>{{get_inspection_status_name($inspectable->status)}}</td>
                                <td class="row-options" style="width: 75px">
                                    <form method="POST" action="{{route('admin.inspector.approve', $inspectable->inspectable_id)}}" accept-charset="UTF-8" class="data-form">
                                        @csrf<input type="hidden" value="{{ $inspectable->inspectable_type }}" name="className">
                                        <button type="submit" class="confirm ajax-silent" title="" data-toggle="tooltip" data-placement="top" data-original-title="{{trans('app.approve')}}"><i class="fa fa-check"></i></button>
                                    </form>&nbsp;

                                    <a href="javascript:void(0)" data-link="{{ get_item_view_url($inspectable->inspectable_type, $inspectable->inspectable_id) }}" class="ajax-modal-btn"><i data-toggle="tooltip" data-placement="top" title="Detail" class="fa fa-expand"></i></a>&nbsp;
                                    <a href="{{ get_item_edit_url($inspectable->inspectable_type, $inspectable->inspectable_id) }}"><i data-toggle="tooltip" data-placement="top" title="Edit" class="fa fa-edit"></i></a>&nbsp;

                                    <form method="post" action="{{route('admin.inspector.deny', $inspectable->inspectable_id)}}" accept-charset="UTF-8" class="data-form">
                                        @csrf<input type="hidden" value="{{ $inspectable->inspectable_type  }}" name="className">
                                        <button type="submit" class="confirm ajax-silent" title="" data-toggle="tooltip" data-placement="top" data-original-title="{{trans('app.deny')}}"><i class="fa fa-trash-o"></i></button>
                                    </form>&nbsp;
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div> <!-- /.box-body -->
    </div>
@endsection
