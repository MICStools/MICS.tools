@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.result.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.results.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                            <label class="required" for="domain_id">{{ trans('cruds.result.fields.domain') }}</label>
                            <select class="form-control select2" name="domain_id" id="domain_id" required>
                                @foreach($domains as $id => $entry)
                                    <option value="{{ $id }}" {{ old('domain_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domain'))
                                <span class="help-block" role="alert">{{ $errors->first('domain') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.result.fields.domain_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('project') ? 'has-error' : '' }}">
                            <label class="required" for="project_id">{{ trans('cruds.result.fields.project') }}</label>
                            <select class="form-control select2" name="project_id" id="project_id" required>
                                @foreach($projects as $id => $entry)
                                    <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('project'))
                                <span class="help-block" role="alert">{{ $errors->first('project') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.result.fields.project_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('score') ? 'has-error' : '' }}">
                            <label class="required" for="score">{{ trans('cruds.result.fields.score') }}</label>
                            <input class="form-control" type="number" name="score" id="score" value="{{ old('score', '0') }}" step="1" required>
                            @if($errors->has('score'))
                                <span class="help-block" role="alert">{{ $errors->first('score') }}</span>
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