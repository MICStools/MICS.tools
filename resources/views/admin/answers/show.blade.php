@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.answer.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.answers.index') }}">
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
                            <a class="btn btn-default" href="{{ route('admin.answers.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#answer_blocklists" aria-controls="answer_blocklists" role="tab" data-toggle="tab">
                            {{ trans('cruds.blocklist.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="answer_blocklists">
                        @includeIf('admin.answers.relationships.answerBlocklists', ['blocklists' => $answer->answerBlocklists])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection