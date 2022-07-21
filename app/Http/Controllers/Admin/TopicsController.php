<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTopicRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Models\Topic;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('topic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $topics = Topic::all();

        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        abort_if(Gate::denies('topic_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.topics.create');
    }

    public function store(StoreTopicRequest $request)
    {
        $topic = Topic::create($request->all());

        return redirect()->route('admin.topics.index');
    }

    public function edit(Topic $topic)
    {
        abort_if(Gate::denies('topic_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.topics.edit', compact('topic'));
    }

    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $topic->update($request->all());

        return redirect()->route('admin.topics.index');
    }

    public function show(Topic $topic)
    {
        abort_if(Gate::denies('topic_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.topics.show', compact('topic'));
    }

    public function destroy(Topic $topic)
    {
        abort_if(Gate::denies('topic_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $topic->delete();

        return back();
    }

    public function massDestroy(MassDestroyTopicRequest $request)
    {
        Topic::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
