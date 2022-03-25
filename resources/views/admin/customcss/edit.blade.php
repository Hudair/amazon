@extends('admin.layouts.master')

@section('content')
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('app.custom_styling') }}</h3>
    </div> <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-md-7">
          @if (config('app.demo') == true)
            <div class="alert alert-warning">
              <h4><i class="fa fa-info"></i> {{ trans('app.info') }}</h4>
              {{ trans('messages.demo_restriction') }}
            </div>
          @else
            {!! Form::open(['route' => 'admin.appearance.custom_css.update', 'id' => 'form', 'data-toggle' => 'validator']) !!}
            <div class="form-group">
              {!! Form::label('theme_custom_css', trans('app.custom_css') . '*') !!}
              {!! Form::textarea('theme_custom_css', get_from_option_table('theme_custom_styling', ''), ['class' => 'form-control', 'rows' => '10', 'placeholder' => trans('help.custom_css_help_text')]) !!}
              <div class="help-block with-errors"></div>
            </div>

            <p class="help-block small">
              <i class="fa fa-question-circle"></i> {{ trans('help.custom_css_help_text') }}
            </p>

            {!! Form::submit(trans('app.form.save'), ['class' => 'btn btn-lg btn-flat btn-new pull-right  ']) !!}
            {!! Form::close() !!}
          @endif
        </div> <!-- /.col-md-7 -->
        <div class="col-md-5 nopadding-left">
          @include('admin.customcss._css_example')
        </div> <!-- /.col-md-5 -->
      </div> <!-- /.row -->
      <div class="spacer20"></div>
    </div> <!-- /.box-body -->
  </div> <!-- /.box -->
@endsection
