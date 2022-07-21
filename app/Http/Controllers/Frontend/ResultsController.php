<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyResultRequest;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Models\Domain;
use App\Models\Project;
use App\Models\Result;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('result_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $results = Result::with(['domain', 'project'])->get();

        return view('frontend.results.index', compact('results'));
    }

    public function create()
    {
        abort_if(Gate::denies('result_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $projects = Project::pluck('shortname', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.results.create', compact('domains', 'projects'));
    }

    public function store(StoreResultRequest $request)
    {
        $result = Result::create($request->all());

        return redirect()->route('frontend.results.index');
    }

    public function edit(Result $result)
    {
        abort_if(Gate::denies('result_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $projects = Project::pluck('shortname', 'id')->prepend(trans('global.pleaseSelect'), '');

        $result->load('domain', 'project');

        return view('frontend.results.edit', compact('domains', 'projects', 'result'));
    }

    public function update(UpdateResultRequest $request, Result $result)
    {
        $result->update($request->all());

        return redirect()->route('frontend.results.index');
    }

    public function show(Result $result)
    {
        abort_if(Gate::denies('result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $result->load('domain', 'project');

        return view('frontend.results.show', compact('result'));
    }

    public function destroy(Result $result)
    {
        abort_if(Gate::denies('result_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $result->delete();

        return back();
    }

    public function massDestroy(MassDestroyResultRequest $request)
    {
        Result::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
