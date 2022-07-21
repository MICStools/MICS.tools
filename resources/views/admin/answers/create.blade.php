@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.answer.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.answers.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('order') ? 'has-error' : '' }}">
                            <label class="required" for="order">{{ trans('cruds.answer.fields.order') }}</label>
                            <input class="form-control" type="number" name="order" id="order" value="{{ old('order', '0') }}" step="1" required>
                            @if($errors->has('order'))
                                <span class="help-block" role="alert">{{ $errors->first('order') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.order_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('question') ? 'has-error' : '' }}">
                            <label class="required" for="question_id">{{ trans('cruds.answer.fields.question') }}</label>
                            <select class="form-control select2" name="question_id" id="question_id" required>
                                @foreach($questions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('question_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('question'))
                                <span class="help-block" role="alert">{{ $errors->first('question') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.question_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                            <label class="required" for="text">{{ trans('cruds.answer.fields.text') }}</label>
                            <input class="form-control" type="text" name="text" id="text" value="{{ old('text', '') }}" required>
                            @if($errors->has('text'))
                                <span class="help-block" role="alert">{{ $errors->first('text') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.text_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('weight') ? 'has-error' : '' }}">
                            <label class="required" for="weight">{{ trans('cruds.answer.fields.weight') }}</label>
                            <input class="form-control" type="number" name="weight" id="weight" value="{{ old('weight', '0') }}" step="1" required>
                            @if($errors->has('weight'))
                                <span class="help-block" role="alert">{{ $errors->first('weight') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.weight_helper') }}</span>
                        </div>
                        <!-- 
                        <div class="form-group {{ $errors->has('projects') ? 'has-error' : '' }}">
                            <label for="projects">{{ trans('cruds.answer.fields.projects') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="projects[]" id="projects" multiple>
                                @foreach($projects as $id => $project)
                                    <option value="{{ $id }}" {{ in_array($id, old('projects', [])) ? 'selected' : '' }}>{{ $project }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('projects'))
                                <span class="help-block" role="alert">{{ $errors->first('projects') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.projects_helper') }}</span>
                        </div>
                        -->
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