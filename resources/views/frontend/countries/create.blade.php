@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.country.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.countries.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.country.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.country.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="short_code">{{ trans('cruds.country.fields.short_code') }}</label>
                            <input class="form-control" type="text" name="short_code" id="short_code" value="{{ old('short_code', '') }}" required>
                            @if($errors->has('short_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('short_code') }}
                                </div>
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