@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.question.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.questions.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $question->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.order') }}
                                    </th>
                                    <td>
                                        {{ $question->order }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.domain') }}
                                    </th>
                                    <td>
                                        {{ $question->domain->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Question::TYPE_RADIO[$question->type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $question->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.text') }}
                                    </th>
                                    <td>
                                        {!! $question->text !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.help') }}
                                    </th>
                                    <td>
                                        {!! $question->help !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.question.fields.information') }}
                                    </th>
                                    <td>
                                        {!! $question->information !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.questions.index') }}">
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
                        <a href="#question_answers" aria-controls="question_answers" role="tab" data-toggle="tab">
                            {{ trans('cruds.answer.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#question_blocklists" aria-controls="question_blocklists" role="tab" data-toggle="tab">
                            {{ trans('cruds.blocklist.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="question_answers">
                        @includeIf('admin.questions.relationships.questionAnswers', ['answers' => $question->questionAnswers])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="question_blocklists">
                        @includeIf('admin.questions.relationships.questionBlocklists', ['blocklists' => $question->questionBlocklists])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection