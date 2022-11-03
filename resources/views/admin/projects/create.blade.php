@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.project.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.projects.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.project.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('shortname') ? 'has-error' : '' }}">
                            <label class="required" for="shortname">{{ trans('cruds.project.fields.shortname') }}</label>
                            <input class="form-control" type="text" name="shortname" id="shortname" value="{{ old('shortname', '') }}" required>
                            @if($errors->has('shortname'))
                                <span class="help-block" role="alert">{{ $errors->first('shortname') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.shortname_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                            <label class="required" for="slug">{{ trans('cruds.project.fields.slug') }}</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                            @if($errors->has('slug'))
                                <span class="help-block" role="alert">{{ $errors->first('slug') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.slug_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('user') ? 'has-error' : '' }}">
                            <label class="required" for="user_id">{{ trans('cruds.project.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <span class="help-block" role="alert">{{ $errors->first('user') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.project.fields.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('shortdescription') ? 'has-error' : '' }}">
                            <label for="shortdescription">{{ trans('cruds.project.fields.shortdescription') }}</label>
                            <textarea class="form-control ckeditor" name="shortdescription" id="shortdescription">{!! old('shortdescription') !!}</textarea>
                            @if($errors->has('shortdescription'))
                                <span class="help-block" role="alert">{{ $errors->first('shortdescription') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.shortdescription_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('featured') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', 0) == 1 ? 'checked' : '' }}>
                                <label for="featured" style="font-weight: 400">{{ trans('cruds.project.fields.featured') }}</label>
                            </div>
                            @if($errors->has('featured'))
                                <span class="help-block" role="alert">{{ $errors->first('featured') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.featured_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
                            <label for="logo">{{ trans('cruds.project.fields.logo') }}</label>
                            <div class="needsclick dropzone" id="logo-dropzone">
                            </div>
                            @if($errors->has('logo'))
                                <span class="help-block" role="alert">{{ $errors->first('logo') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.logo_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('banner') ? 'has-error' : '' }}">
                            <label for="banner">{{ trans('cruds.project.fields.banner') }}</label>
                            <div class="needsclick dropzone" id="banner-dropzone">
                            </div>
                            @if($errors->has('banner'))
                                <span class="help-block" role="alert">{{ $errors->first('banner') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.banner_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('startdate') ? 'has-error' : '' }}">
                            <label for="startdate">{{ trans('cruds.project.fields.startdate') }}</label>
                            <input class="form-control date" type="text" name="startdate" id="startdate" value="{{ old('startdate') }}">
                            @if($errors->has('startdate'))
                                <span class="help-block" role="alert">{{ $errors->first('startdate') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.startdate_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('enddate') ? 'has-error' : '' }}">
                            <label for="enddate">{{ trans('cruds.project.fields.enddate') }}</label>
                            <input class="form-control date" type="text" name="enddate" id="enddate" value="{{ old('enddate') }}">
                            @if($errors->has('enddate'))
                                <span class="help-block" role="alert">{{ $errors->first('enddate') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.enddate_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                            <label for="contact">{{ trans('cruds.project.fields.contact') }}</label>
                            <input class="form-control" type="text" name="contact" id="contact" value="{{ old('contact', '') }}">
                            @if($errors->has('contact'))
                                <span class="help-block" role="alert">{{ $errors->first('contact') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.contact_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contactdetails') ? 'has-error' : '' }}">
                            <label for="contactdetails">{{ trans('cruds.project.fields.contactdetails') }}</label>
                            <input class="form-control" type="text" name="contactdetails" id="contactdetails" value="{{ old('contactdetails', '') }}">
                            @if($errors->has('contactdetails'))
                                <span class="help-block" role="alert">{{ $errors->first('contactdetails') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.contactdetails_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cost">{{ trans('cruds.project.fields.cost') }} <span class="help-block">{{ trans('cruds.project.fields.cost_helper') }}</span></label>
                            <input class="form-control" type="text" name="cost" id="cost" value="{{ old('cost', '') }}">
                            @if($errors->has('cost'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cost') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="funding">{{ trans('cruds.project.fields.funding') }} <span class="help-block">{{ trans('cruds.project.fields.funding_helper') }}</span></label>
                            <input class="form-control" type="text" name="funding" id="funding" value="{{ old('funding', '') }}">
                            @if($errors->has('funding'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('funding') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('uri') ? 'has-error' : '' }}">
                            <label for="uri">{{ trans('cruds.project.fields.uri') }}</label>
                            <input class="form-control" type="text" name="uri" id="uri" value="{{ old('uri', '') }}">
                            @if($errors->has('uri'))
                                <span class="help-block" role="alert">{{ $errors->first('uri') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.uri_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('organisers') ? 'has-error' : '' }}">
                            <label for="organisers">{{ trans('cruds.project.fields.organisers') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="organisers[]" id="organisers" multiple>
                                @foreach($organisers as $id => $organiser)
                                    <option value="{{ $id }}" {{ in_array($id, old('organisers', [])) ? 'selected' : '' }}>{{ $organiser }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('organisers'))
                                <span class="help-block" role="alert">{{ $errors->first('organisers') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.organisers_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('participants') ? 'has-error' : '' }}">
                            <label for="participants">{{ trans('cruds.project.fields.participants') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="participants[]" id="participants" multiple>
                                @foreach($participants as $id => $participant)
                                    <<option value="{{ $id }}" {{ in_array($id, old('participants', [])) ? 'selected' : '' }}>{{ $participant }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('participants'))
                                <span class="help-block" role="alert">{{ $errors->first('participants') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.participants_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('observers') ? 'has-error' : '' }}">
                            <label for="observers">{{ trans('cruds.project.fields.observers') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="observers[]" id="observers" multiple>
                                @foreach($observers as $id => $observer)
                                    <option value="{{ $id }}" {{ in_array($id, old('observers', [])) ? 'selected' : '' }}>{{ $observer }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('observers'))
                                <span class="help-block" role="alert">{{ $errors->first('observers') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.observers_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.projects.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $project->id ?? 0 }}');
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

<script>
    Dropzone.options.logoDropzone = {
    url: '{{ route('admin.projects.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="logo"]').remove()
      $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="logo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($project) && $project->logo)
      var file = {!! json_encode($project->logo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    Dropzone.options.bannerDropzone = {
    url: '{{ route('admin.projects.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="banner"]').remove()
      $('form').append('<input type="hidden" name="banner" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="banner"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($project) && $project->banner)
      var file = {!! json_encode($project->banner) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="banner" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection