@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.answer.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.answers.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="order">{{ trans('cruds.answer.fields.order') }}</label>
                            <input class="form-control" type="number" name="order" id="order" value="{{ old('order', '0') }}" step="1" required>
                            @if($errors->has('order'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.order_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="question_id">{{ trans('cruds.answer.fields.question') }}</label>
                            <select class="form-control select2" name="question_id" id="question_id" required>
                                @foreach($questions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('question_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('question'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('question') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.question_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="text">{{ trans('cruds.answer.fields.text') }}</label>
                            <input class="form-control" type="text" name="text" id="text" value="{{ old('text', '') }}" required>
                            @if($errors->has('text'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('text') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.text_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="weight">{{ trans('cruds.answer.fields.weight') }}</label>
                            <input class="form-control" type="number" name="weight" id="weight" value="{{ old('weight', '0') }}" step="1" required>
                            @if($errors->has('weight'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('weight') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.weight_helper') }}</span>
                        </div>
                        <div class="form-group">
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
                                <div class="invalid-feedback">
                                    {{ $errors->first('projects') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.answer.fields.projects_helper') }}</span>
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