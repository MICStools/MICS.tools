@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.recommendation.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.recommendations.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $recommendation->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.domain') }}
                                    </th>
                                    <td>
                                        {{ $recommendation->domain->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $recommendation->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.label') }}
                                    </th>
                                    <td>
                                        {{ $recommendation->label }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.text') }}
                                    </th>
                                    <td>
                                        {!! $recommendation->text !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.questions') }}
                                    </th>
                                    <td>
                                        @foreach($recommendation->questions as $key => $questions)
                                            <span class="label label-info">{{ $questions->title }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.minscore') }}
                                    </th>
                                    <td>
                                        {{ $recommendation->minscore }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.maxscore') }}
                                    </th>
                                    <td>
                                        {{ $recommendation->maxscore }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.indicator') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $recommendation->indicator ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.recommendations.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection