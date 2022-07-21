@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.question.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.questions.update", [$question->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('order') ? 'has-error' : '' }}">
                            <label class="required" for="order">{{ trans('cruds.question.fields.order') }}</label>
                            <input class="form-control" type="number" name="order" id="order" value="{{ old('order', $question->order) }}" step="1" required>
                            @if($errors->has('order'))
                                <span class="help-block" role="alert">{{ $errors->first('order') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.order_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                            <label class="required" for="domain_id">{{ trans('cruds.question.fields.domain') }}</label>
                            <select class="form-control select2" name="domain_id" id="domain_id" required>
                                @foreach($domains as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('domain_id') ? old('domain_id') : $question->domain->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domain'))
                                <span class="help-block" role="alert">{{ $errors->first('domain') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.domain_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.question.fields.type') }}</label>
                            @foreach(App\Models\Question::TYPE_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="type_{{ $key }}" name="type" value="{{ $key }}" {{ old('type', $question->type) === (string) $key ? 'checked' : '' }} required>
                                    <label for="type_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('type'))
                                <span class="help-block" role="alert">{{ $errors->first('type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.question.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $question->title) }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                            <label for="text">{{ trans('cruds.question.fields.text') }}</label>
                            <textarea class="form-control ckeditor" name="text" id="text">{!! old('text', $question->text) !!}</textarea>
                            @if($errors->has('text'))
                                <span class="help-block" role="alert">{{ $errors->first('text') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.text_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('help') ? 'has-error' : '' }}">
                            <label for="help">{{ trans('cruds.question.fields.help') }}</label>
                            <textarea class="form-control ckeditor" name="help" id="help">{!! old('help', $question->help) !!}</textarea>
                            @if($errors->has('help'))
                                <span class="help-block" role="alert">{{ $errors->first('help') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.help_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('information') ? 'has-error' : '' }}">
                            <label for="information">{{ trans('cruds.question.fields.information') }}</label>
                            <textarea class="form-control ckeditor" name="information" id="information">{!! old('information', $question->information) !!}</textarea>
                            @if($errors->has('information'))
                                <span class="help-block" role="alert">{{ $errors->first('information') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.question.fields.information_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.questions.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $question->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection