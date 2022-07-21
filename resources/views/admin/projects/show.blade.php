@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.project.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.projects.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $project->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $project->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.shortname') }}
                                    </th>
                                    <td>
                                        {{ $project->shortname }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $project->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $project->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.description') }}
                                    </th>
                                    <td>
                                        {!! $project->description !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.shortdescription') }}
                                    </th>
                                    <td>
                                        {!! $project->shortdescription !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.featured') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $project->featured ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.logo') }}
                                    </th>
                                    <td>
                                        @if($project->logo)
                                            <a href="{{ $project->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $project->logo->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.banner') }}
                                    </th>
                                    <td>
                                        @if($project->banner)
                                            <a href="{{ $project->banner->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $project->banner->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.startdate') }}
                                    </th>
                                    <td>
                                        {{ $project->startdate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.enddate') }}
                                    </th>
                                    <td>
                                        {{ $project->enddate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.contact') }}
                                    </th>
                                    <td>
                                        {{ $project->contact }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.contactdetails') }}
                                    </th>
                                    <td>
                                        {{ $project->contactdetails }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.cost') }}
                                    </th>
                                    <td>
                                        {{ $project->cost }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.funding') }}
                                    </th>
                                    <td>
                                        {{ $project->funding }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.uri') }}
                                    </th>
                                    <td>
                                        {{ $project->uri }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.organisers') }}
                                    </th>
                                    <td>
                                        @foreach($project->organisers as $key => $organisers)
                                            <span class="label label-info">{{ $organisers->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.projects.index') }}">
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
                        <a href="#project_results" aria-controls="project_results" role="tab" data-toggle="tab">
                            {{ trans('cruds.result.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#projects_answers" aria-controls="projects_answers" role="tab" data-toggle="tab">
                            {{ trans('cruds.answer.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="project_results">
                        @includeIf('admin.projects.relationships.projectResults', ['results' => $project->projectResults])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="projects_answers">
                        @includeIf('admin.projects.relationships.projectsAnswers', ['answers' => $project->projectsAnswers])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection