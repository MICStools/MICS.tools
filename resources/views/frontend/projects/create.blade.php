@extends('layouts.frontend')
@section('page_title','Create a new project - MICS.tools')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 pt-2"><h2>Create a project page</h2></div>
        <div class="col-md-12"><p>Connect your project, yourself and your cause to the worldwide community of citizen science. For troubleshooting, see the <a href="https://about.mics.tools/how-to">how-to guide</a>; for any other questions, see <a href="https://about.mics.tools/wtf">where to find help</a>.</p></div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4 p-0 card" style="background-image: url('/createproject1.svg'); background-size: cover; background-repeat: no-repeat;">
            &nbsp;
        </div>
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.projects.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.project.fields.name') }} <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span></label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required" for="shortname">{{ trans('cruds.project.fields.shortname') }} <span class="help-block">{{ trans('cruds.project.fields.shortname_helper') }}</span></label>
                            <input class="form-control" type="text" name="shortname" id="shortname" value="{{ old('shortname', '') }}" required>
                            @if($errors->has('shortname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('shortname') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required" for="slug">{{ trans('cruds.project.fields.slug') }} <span class="help-block">{{ trans('cruds.project.fields.slug_helper') }}</span></label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                  <div class="input-group-text"><span class="font-italic">{{ request()->getSchemeAndHttpHost(); }}/projects/</span></div>
                                </div>
                                <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                            </div>
                            @if($errors->has('slug'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('slug') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group" hidden>
                            <label class="required" for="user_id">{{ trans('cruds.project.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.project.fields.description') }} <span class="help-block">{{ trans('cruds.project.fields.description_helper') }}</span></label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group" hidden>
                            <label for="shortdescription">{{ trans('cruds.project.fields.shortdescription') }}</label>
                            <textarea class="form-control ckeditor" name="shortdescription" id="shortdescription">{!! old('shortdescription') !!}</textarea>
                            @if($errors->has('shortdescription'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('shortdescription') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.project.fields.shortdescription_helper') }}</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="logo">{{ trans('cruds.project.fields.logo') }} <span class="help-block">{{ trans('cruds.project.fields.logo_helper') }}</span></label>
                            <div class="needsclick dropzone" id="logo-dropzone">
                            </div>
                            @if($errors->has('logo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('logo') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="banner">{{ trans('cruds.project.fields.banner') }} <span class="help-block">{{ trans('cruds.project.fields.banner_helper') }}</span></label>
                            <div class="needsclick dropzone" id="banner-dropzone">
                            </div>
                            @if($errors->has('banner'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('banner') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="roles">Topics <span class="help-block">(the areas of citizen science that this project relates to)</span></label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="topics[]" id="topics" multiple>
                                @foreach($topics as $id => $topic)
                                    <option value="{{ $id }}" {{ in_array($id, old('topics', [])) ? 'selected' : '' }}>{{ $topic }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('topics'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('topics') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="startdate">{{ trans('cruds.project.fields.startdate') }} <span class="help-block">{{ trans('cruds.project.fields.startdate_helper') }}</span></label>
                            <input class="form-control date" type="text" name="startdate" id="startdate" value="{{ old('startdate') }}">
                            @if($errors->has('startdate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('startdate') }}
                                </div>
                            @endif
                            
                        </div>
                        <div class="form-group">
                            <label for="enddate">{{ trans('cruds.project.fields.enddate') }} <span class="help-block">{{ trans('cruds.project.fields.enddate_helper') }}</span></label>
                            <input class="form-control date" type="text" name="enddate" id="enddate" value="{{ old('enddate') }}">
                            @if($errors->has('enddate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enddate') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="contact">{{ trans('cruds.project.fields.contact') }} <span class="help-block">{{ trans('cruds.project.fields.contact_helper') }}</span></label>
                            <input class="form-control" type="text" name="contact" id="contact" value="{{ old('contact', '') }}">
                            @if($errors->has('contact'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('contact') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="contactdetails">{{ trans('cruds.project.fields.contactdetails') }} <span class="help-block">{{ trans('cruds.project.fields.contactdetails_helper') }}</span></label>
                            <input class="form-control" type="text" name="contactdetails" id="contactdetails" value="{{ old('contactdetails', '') }}">
                            @if($errors->has('contactdetails'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('contactdetails') }}
                                </div>
                            @endif
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
                        <div class="form-group">
                            <label for="uri">{{ trans('cruds.project.fields.uri') }} <span class="help-block">{{ trans('cruds.project.fields.uri_helper') }}</span></label>
                            <input class="form-control" type="text" name="uri" id="uri" value="{{ old('uri', '') }}">
                            @if($errors->has('uri'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('uri') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="organisers">{{ trans('cruds.project.fields.organisers') }} <span class="help-block">{{ trans('cruds.project.fields.organisers_helper') }}</span></label>
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
                                <div class="invalid-feedback">
                                    {{ $errors->first('organisers') }}
                                </div>
                            @endif
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
    url: '{{ route('frontend.projects.storeMedia') }}',
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
    url: '{{ route('frontend.projects.storeMedia') }}',
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
<script>
    $('#shortname').change(function(e) {
      $.get('/check_slug', 
        { 'shortname': $(this).val() }, 
        function( data ) {
          $('#slug').val(data.slug);
        }
      );
    });
  </script>
@endsection