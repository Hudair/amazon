<link href="{{ mix('css/fileinput.css') }}" rel="stylesheet">
<script src="{{ mix('js/fileinput.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/locales/LANG.js"></script> --}}

<!-- page script -->
<script type="text/javascript">
  ;
  (function($, window, document) {
    $(document).ready(function() {
      var formData = new FormData();

      var footerTemplate = '<div class="file-thumbnail-footer">\n' +
        '    <small>{size}</small> {actions}\n' +
        '</div>';

      var actionsTemplate = '<div class="file-actions">\n' +
        '    <div class="file-footer-buttons">\n' +
        '       {download} {zoom} {other} {delete}' +
        '    </div>\n' +
        '    {drag}\n' +
        '    <div class="clearfix"></div>\n' +
        '</div>';

      var deleteTemplate = '<button type="button" class="kv-file-remove btn btn-kv btn-default btn-flat btn-xs" data-toggle="tooltip" data-placement="top" title="{{ trans('app.remove') }}" {dataUrl}{dataKey}><i class="fa fa-trash"></i></button>\n';

      var downloadTemplate = '<a class="kv-file-download btn btn-default btn-flat btn-xs" data-toggle="tooltip" data-placement="top" title="{{ trans('app.download') }}" href="{downloadUrl}" download="" target="_blank"><i class="fa fa-download"></i></a>\n';

      var zoomTemplate = '<button type="button" class="kv-file-zoom btn btn-default btn-flat btn-xs"  data-toggle="tooltip" data-placement="top" title="{{ trans('app.preview') }}"><i class="fa fa-search-plus"></i></button>';

      var dragTemplate = '<span class="file-drag-handle {dragClass}" data-toggle="tooltip" data-placement="top" title="{{ trans('app.move') }}"><i class="fa fa-arrows"></i></span>';

      var initialPreview = [<?= isset($preview) ? $preview['urls'] : '' ?>];

      var initialPreviewConfig = [<?= isset($preview) ? $preview['configs'] : '' ?>];

      $("#dropzone-input").fileinput({
        uploadUrl: "{{ route('image.upload') }}",
        uploadExtraData: function() {
          var extra = {};
          extra['model_id'] = formData.get('model_id');
          extra['model_name'] = formData.get('model_name');
          extra['redirect_url'] = formData.get('redirect_url');
          return extra;
        },
        showUpload: false,
        enableResumableUpload: true,
        resumableUploadOptions: {
          // testUrl: "/site/test-file-chunks",
          chunkSize: {{ config('image.chunk_size', 1024) }},
        },
        dropZoneEnabled: true,
        browseOnZoneClick: true,
        dropZoneTitle: "{{ trans('app.drag_n_drop_here') }}",
        showClose: false,
        showRemove: false,
        showCaption: false,
        maxFilePreviewSize: 25600,
        minFileSize: {{ getAllowedMinImgSize() }},
        maxFileSize: {{ getAllowedMaxImgSize() }},
        minFileCount: {{ getMinNumberOfRequiredImgsForInventory() }},
        maxTotalFileCount: {{ getMaxNumberOfImgsForInventory() }},
        allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png'],
        msgFilesTooLess: "{!! trans('help.number_of_img_upload_required') !!}",
        msgTotalFilesTooMany: "{!! trans('help.number_of_img_upload_exceeded') !!}",
        msgInvalidFileExtension: "{!! trans('help.msg_invalid_file_extension') !!}",
        msgSizeTooLarge: "{!! trans('help.msg_invalid_file_too_learge') !!}",
        dragSettings: {
          animation: 300,
          onUpdate: function(evt) {
            console.log(evt);
          },
        },
        initialPreview: initialPreview,
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreviewDownloadUrl: "{{ url('download/{key}') }}",
        initialPreviewConfig: initialPreviewConfig,
        layoutTemplates: {
          footer: footerTemplate,
          actions: actionsTemplate,
          actionDelete: deleteTemplate,
          actionDownload: downloadTemplate,
          actionZoom: zoomTemplate,
          actionDrag: dragTemplate
        },
      }).on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
          $.confirm({
            title: "{{ trans('app.confirmation') }}",
            content: "{{ trans('app.are_you_sure') }}",
            type: 'red',
            buttons: {
              'confirm': {
                text: '{{ trans('app.proceed') }}',
                keys: ['enter'],
                btnClass: 'btn-red',
                action: function() {
                  resolve();
                }
              },
              'cancel': {
                text: '{{ trans('app.cancel') }}',
                action: function() {
                  notie.alert(2, "{{ trans('messages.canceled') }}", 3);
                }
              },
            }
          });
        });
      }).on('filedeleted', function() {
        setTimeout(function() {
          notie.alert(1, "{{ trans('messages.file_deleted') }}", 3);
        }, 900);
      }).on('filesorted', function(event, params) {
        var sortUrl = "{{ route('image.sort') }}";
        var max = Math.max(params.oldIndex, params.newIndex);
        var min = Math.min(params.oldIndex, params.newIndex);
        var order = {};
        var stack = params.stack;
        for (k in stack) {
          if (k >= min && k <= max)
            order[stack[k].key] = k;
        };

        // Update the database using AJAX
        $.post(sortUrl, order, function(theResponse, status) {
          notie.alert(1, "{{ trans('responses.reordered') }}", 2);
        });
      }).on('fileuploaded', function(event, previewId, index, fileId) {
        console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
      }).on('fileuploaderror', function(event, previewId, index, fileId) {
        console.log('File Upload Error', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
      }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
        console.log('File Batch Uploaded', preview, config, tags, extraData);
        window.location.href = extraData.redirect_url;
      });

      $('div.btn.btn-primary.btn-file').hide();

      $('#form-ajax-upload').on('submit', function(event) {
        $(this).find(":submit").prop("disabled", true);

        var action = $(this).attr('action');
        var data = $("#form-ajax-upload").serializeArray();

        // Reset the key_features fields to avoid duplicate entry
        formData.delete('key_features[]');

        $.each(data, function(key, input) {
          formData.append(input.name, input.value);
        });

        if ($("#uploadBtn").val()) {
          var file = $("#uploadBtn")[0].files[0];
          formData.append("images[feature]", file);
        }

        $.ajax({
            url: action,
            type: "POST",
            datatype: "json",
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
          })
          .done(function(result) {
            formData.append('model_id', result.id);
            formData.append('model_name', result.model);
            formData.append('redirect_url', result.redirect);

            var node = $('#dropzone-input');
            if (node.fileinput("getFilesCount") > 0) { // Upload only if there is files
              node.fileinput('upload').fileinput('disable');
            } else {
              window.location.href = result.redirect;
            }
          })
          .fail(function(xhr) {
            $("#form-ajax-upload").find(":submit").removeAttr("disabled");
            var err = '';
            if (401 === xhr.status) {
              window.location = "{{ route('login') }}";
            } else if (422 === xhr.status) {
              notie.alert(3, "{{ trans('responses.form_validation_failed') }}", 3);
              var response = xhr.responseJSON;

              $.each(response.errors, function(key, input) {
                err += input + '<br/>';
              });
            } else {
              err += "{{ trans('responses.error') }}";
            }

            var msg = '<div class="alert alert-danger alert-dismissible">' +
              '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
              '<h4><i class="icon fa fa-warning"></i>{{ trans('app.error') }}</h4>' +
              '<p id="global-alert-msg">' + err + '</p>' +
              '</div>';
            $("section.content").prepend(msg);
          });

        return false;
      });
    });
  }(window.jQuery, window, document));
</script>
