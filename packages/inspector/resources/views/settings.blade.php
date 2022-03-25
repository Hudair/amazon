@extends('admin.layouts.master')

@section('content')
	<div class="box">
	    <div class="box-header with-border">
	      <h3 class="box-title">{{ trans('inspector::lang.inspector_settings') }}</h3>
	      <div class="box-tools pull-right"></div>
	    </div> <!-- /.box-header -->
	    <div class="box-body">
	    	<div class="spacer20"></div>
	    	<div class="row">
		    	{!! Form::open(['route' => ['admin.package.config.update', 'inspector'], 'files' => true, 'class' => 'form-horizontal', 'id' => 'form', 'data-toggle' => '	validator']) !!}
			    	<div class="col-sm-9 nopadding-right">
						<div class="form-group">
						  	<div class="col-sm-5 text-right">
								{!! Form::label('inspector_keywords', trans('inspector::lang.inspector_keywords') . ':*', ['class' => 'with-help control-label']) !!}
								<i class="fa fa-question-circle" data-toggle="tooltip" title="{{ trans('inspector::lang.enter_your_keywords') }}"></i>
							</div>
						  	<div class="col-sm-7 nopadding-left">
						  		@php
						  			$inspector_keywords = get_from_option_table('inspector_keywords', []);
						  			$inspector_keywords = array_combine($inspector_keywords, $inspector_keywords);
						  		@endphp
						        {!! Form::select('inspector_keywords[]', $inspector_keywords, array_keys($inspector_keywords), ['class' => 'form-control select2-keywords', 'multiple' => 'multiple', 'placeholder' => trans('inspector::lang.inspector_keywords'), 'required']) !!}
							  	<div class="help-block with-errors"></div>
						  	</div>
						</div>
			          	{{-- <div class="form-group">
				            {!! Form::label('inspector_models[]', trans('inspector::lang.models') . ':*', ['class' => 'with-help col-sm-5 control-label']) !!}
						  	<div class="col-sm-7 nopadding-left">
					            {!! Form::select('inspector_models[]', $models , get_from_option_table('inspector_models', []), ['class' => 'form-control select2-normal', 'multiple' => 'multiple', 'required']) !!}
					            <div class="help-block with-errors">{{ trans('inspector::lang.select_models') }}</div>
					        </div>
			          	</div> --}}
			          	{!! Form::submit(trans('app.update'), ['class' => 'btn btn-lg btn-flat btn-new pull-right']) !!}
			    	</div>

			        <div class="col-sm-3 nopadding-left">
					</div>
		        {!! Form::close() !!}
	    	</div>
	    	<div class="spacer20"></div>
	    </div> <!-- /.box-body -->
	</div> <!-- /.box -->
@endsection
