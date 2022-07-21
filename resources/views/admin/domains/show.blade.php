@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.domain.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.domains.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.domain.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $domain->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.domain.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $domain->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.domain.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $domain->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.domain.fields.background') }}
                                    </th>
                                    <td>
                                        @if($domain->background)
                                            <a href="{{ $domain->background->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $domain->background->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.domain.fields.primarycolour') }}
                                    </th>
                                    <td>
                                        {{ $domain->primarycolour }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.domain.fields.secondarycolour') }}
                                    </th>
                                    <td>
                                        {{ $domain->secondarycolour }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.domains.index') }}">
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
                        <a href="#domain_questions" aria-controls="domain_questions" role="tab" data-toggle="tab">
                            {{ trans('cruds.question.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="domain_questions">
                        @includeIf('admin.domains.relationships.domainQuestions', ['questions' => $domain->domainQuestions])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection