@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.blocklist.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.blocklists.store") }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group {{ $errors->has('answers') ? 'has-error' : '' }}">
                            <label class="required" for="answers">{{ trans('cruds.blocklist.fields.answer') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="answers[]" id="answers" multiple required>
                                @foreach($answers as $id => $answer)
                                    <option value="{{ $id }}" {{ in_array($id, old('answers', [])) ? 'selected' : '' }}>{{ $answer }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('answers'))
                                <span class="help-block" role="alert">{{ $errors->first('answers') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.blocklist.fields.answer_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('questions') ? 'has-error' : '' }}">
                            <label class="required" for="questions">{{ trans('cruds.blocklist.fields.question') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="questions[]" id="questions" multiple required>
                                @foreach($questions as $id => $question)
                                    <option value="{{ $id }}" {{ in_array($id, old('questions', [])) ? 'selected' : '' }}>{{ $question }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('questions'))
                                <span class="help-block" role="alert">{{ $errors->first('questions') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.blocklist.fields.question_helper') }}</span>
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