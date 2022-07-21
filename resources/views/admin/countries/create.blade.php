@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.country.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.countries.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.country.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.country.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('short_code') ? 'has-error' : '' }}">
                            <label class="required" for="short_code">{{ trans('cruds.country.fields.short_code') }}</label>
                            <input class="form-control" type="text" name="short_code" id="short_code" value="{{ old('short_code', '') }}" required>
                            @if($errors->has('short_code'))
                                <span class="help-block" role="alert">{{ $errors->first('short_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.country.fields.short_code_helper') }}</span>
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