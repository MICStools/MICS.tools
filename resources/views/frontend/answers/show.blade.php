@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.answer.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.answers.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.answer.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $answer->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.answer.fields.order') }}
                                    </th>
                                    <td>
                                        {{ $answer->order }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.answer.fields.question') }}
                                    </th>
                                    <td>
                                        {{ $answer->question->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.answer.fields.text') }}
                                    </th>
                                    <td>
                                        {{ $answer->text }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.answer.fields.weight') }}
                                    </th>
                                    <td>
                                        {{ $answer->weight }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.answer.fields.projects') }}
                                    </th>
                                    <td>
                                        @foreach($answer->projects as $key => $projects)
                                            <span class="label label-info">{{ $projects->shortname }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.answers.index') }}">
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