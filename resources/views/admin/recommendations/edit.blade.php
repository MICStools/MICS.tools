@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.recommendation.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.recommendations.update", [$recommendation->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                            <label class="required" for="domain_id">{{ trans('cruds.recommendation.fields.domain') }}</label>
                            <select class="form-control select2" name="domain_id" id="domain_id" required>
                                @foreach($domains as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('domain_id') ? old('domain_id') : $recommendation->domain->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domain'))
                                <span class="help-block" role="alert">{{ $errors->first('domain') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recommendation.fields.domain_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.recommendation.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $recommendation->title) }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recommendation.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
                          <label for="label">{{ trans('cruds.recommendation.fields.label') }}</label>
                          <input class="form-control" type="text" name="label" id="label" value="{{ old('label', $recommendation->label) }}">
                          @if($errors->has('label'))
                              <span class="help-block" role="alert">{{ $errors->first('label') }}</span>
                          @endif
                          <span class="help-block">{{ trans('cruds.recommendation.fields.label_helper') }}</span>
                      </div>
                        <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                            <label for="text">{{ trans('cruds.recommendation.fields.text') }}</label>
                            <textarea class="form-control ckeditor" name="text" id="text">{!! old('text', $recommendation->text) !!}</textarea>
                            @if($errors->has('text'))
                                <span class="help-block" role="alert">{{ $errors->first('text') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recommendation.fields.text_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('questions') ? 'has-error' : '' }}">
                            <label for="questions">{{ trans('cruds.recommendation.fields.questions') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="questions[]" id="questions" multiple>
                                @foreach($questions as $id => $question)
                                    <option value="{{ $id }}" {{ (in_array($id, old('questions', [])) || $recommendation->questions->contains($id)) ? 'selected' : '' }}>{{ $question }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('questions'))
                                <span class="help-block" role="alert">{{ $errors->first('questions') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recommendation.fields.questions_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('minscore') ? 'has-error' : '' }}">
                            <label class="required" for="minscore">{{ trans('cruds.recommendation.fields.minscore') }}</label>
                            <input class="form-control" type="number" name="minscore" id="minscore" value="{{ old('minscore', $recommendation->minscore) }}" step="1" required>
                            @if($errors->has('minscore'))
                                <span class="help-block" role="alert">{{ $errors->first('minscore') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recommendation.fields.minscore_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('maxscore') ? 'has-error' : '' }}">
                            <label class="required" for="maxscore">{{ trans('cruds.recommendation.fields.maxscore') }}</label>
                            <input class="form-control" type="number" name="maxscore" id="maxscore" value="{{ old('maxscore', $recommendation->maxscore) }}" step="1" required>
                            @if($errors->has('maxscore'))
                                <span class="help-block" role="alert">{{ $errors->first('maxscore') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.recommendation.fields.maxscore_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('indicator') ? 'has-error' : '' }}">
                          <div>
                              <input type="hidden" name="indicator" value="0">
                              <input type="checkbox" name="indicator" id="indicator" value="1" {{ $recommendation->indicator || old('indicator', 0) === 1 ? 'checked' : '' }}>
                              <label for="indicator" style="font-weight: 400">{{ trans('cruds.recommendation.fields.indicator') }}</label>
                          </div>
                          @if($errors->has('indicator'))
                              <span class="help-block" role="alert">{{ $errors->first('indicator') }}</span>
                          @endif
                          <span class="help-block">{{ trans('cruds.recommendation.fields.indicator_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.recommendations.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $recommendation->id ?? 0 }}');
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