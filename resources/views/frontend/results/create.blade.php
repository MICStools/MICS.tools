@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.result.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.results.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="domain_id">{{ trans('cruds.result.fields.domain') }}</label>
                            <select class="form-control select2" name="domain_id" id="domain_id" required>
                                @foreach($domains as $id => $entry)
                                    <option value="{{ $id }}" {{ old('domain_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domain'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('domain') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.result.fields.domain_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="project_id">{{ trans('cruds.result.fields.project') }}</label>
                            <select class="form-control select2" name="project_id" id="project_id" required>
                                @foreach($projects as $id => $entry)
                                    <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('project'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('project') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.result.fields.project_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="score">{{ trans('cruds.result.fields.score') }}</label>
                            <input class="form-control" type="number" name="score" id="score" value="{{ old('score', '0') }}" step="1" required>
                            @if($errors->has('score'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('score') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.result.fields.score_helper') }}</span>
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