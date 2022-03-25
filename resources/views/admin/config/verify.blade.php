@extends('admin.layouts.master')

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('app.verification') }}</h3>
        </div> <!-- /.box-header -->
        <div class="box-body">

          <h3>{!! trans('messages.how_id_verification_helps') !!}</h3>
          <p>{!! trans('messages.verification_intro') !!}</p>

          <h3>{{ trans('messages.verified_business_name_like') }}: </h3>
          <p class="lead">
            <img src="{{ get_storage_file_url(optional($config->shop->logo)->path, 'tiny') }}" class="img-circle img-sm" alt="{{ trans('app.logo') }}">
            <strong>{{ get_site_title() }}</strong>
            <img src="{{ get_verified_badge() }}" class="verified-badge img-xs" data-toggle="tooltip" data-placement="top" title="{{ trans('help.verified_seller') }}" alt="verified-badge">
          </p>

          <h3>{!! trans('messages.how_the_verification_process_works') !!}</h3>
          <p>{!! trans('messages.verification_process') !!}</p>

          <h3>{!! trans('messages.what_the_verification_documents_need') !!}</h3>
          <p>{!! trans('messages.verification_documents') !!}</p>

          <span class="spacer30"></span>
        </div> <!-- /.box-body -->
      </div> <!-- /.box -->
    </div> <!-- /.col-md-8 -->

    <div class="col-md-4 nopadding-left">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('app.upload_documents') }}</h3>
        </div> <!-- /.box-header -->
        <div class="box-body">

          @if (count($config->attachments))
            <div class="form-group">
              <label>{{ trans('app.uploaded_documents') }}</label>
              <ul class="list-group">
                @foreach ($config->attachments as $attachment)
                  <li class="list-group-item small">
                    <a href="{{ route('attachment.download', $attachment) }}">
                      <i class="fa fa-cloud-download"></i>
                      {{ $attachment->name }}
                    </a>
                    <small>
                      ({{ get_formated_file_size($attachment->size) }})
                    </small>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif

          {!! Form::open(['route' => 'admin.setting.verify', 'files' => true, 'id' => 'form', 'data-toggle' => 'validator']) !!}
          <span class="spacer10"></span>

          <div class="row">
            <div class="col-md-9 nopadding-right">
              <input id="uploadFile" placeholder="{{ trans('app.upload_documents') }}" class="form-control" disabled="disabled" style="height: 28px;" />
              <div class="help-block with-errors"><i class="fa fa-info"></i> {{ trans('help.select_all_verification_documents') }}</div>
            </div>
            <div class="col-md-3 nopadding-left">
              <div class="fileUpload btn btn-primary btn-block btn-flat">
                <span>{{ trans('app.form.upload') }} </span>
                <input type="file" name="documents[]" multiple="true" id="uploadBtn" class="upload" />
              </div>
            </div>
          </div>

          <span class="spacer10"></span>

          {!! Form::submit(trans('app.submit'), ['class' => 'btn btn-flat btn-lg btn-block btn-new']) !!}
          {!! Form::close() !!}
          <span class="spacer30"></span>
        </div> <!-- /.box-body -->
      </div> <!-- /.box -->
    </div> <!-- /.col-md-4 -->
  </div>
@endsection
