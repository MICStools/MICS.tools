<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBlocklistRequest;
use App\Http\Requests\StoreBlocklistRequest;
use App\Http\Requests\UpdateBlocklistRequest;
use App\Models\Answer;
use App\Models\Blocklist;
use App\Models\Question;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlocklistController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('blocklist_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blocklists = Blocklist::with(['questions', 'answers'])->get();

        return view('admin.blocklists.index', compact('blocklists'));
    }

    public function create()
    {
        abort_if(Gate::denies('blocklist_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questions = Question::pluck('title', 'id');

        $answers = Answer::pluck('text', 'id');

        return view('admin.blocklists.create', compact('questions', 'answers'));
    }

    public function store(StoreBlocklistRequest $request)
    {
        $blocklist = Blocklist::create($request->all());
        $blocklist->questions()->sync($request->input('questions', []));
        $blocklist->answers()->sync($request->input('answers', []));

        return redirect()->route('admin.blocklists.index');
    }

    public function edit(Blocklist $blocklist)
    {
        abort_if(Gate::denies('blocklist_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $questions = Question::pluck('title', 'id');

        $answers = Answer::pluck('text', 'id');

        $blocklist->load('questions', 'answers');

        return view('admin.blocklists.edit', compact('questions', 'answers', 'blocklist'));
    }

    public function update(UpdateBlocklistRequest $request, Blocklist $blocklist)
    {
        $blocklist->update($request->all());
        $blocklist->questions()->sync($request->input('questions', []));
        $blocklist->answers()->sync($request->input('answers', []));

        return redirect()->route('admin.blocklists.index');
    }

    public function show(Blocklist $blocklist)
    {
        abort_if(Gate::denies('blocklist_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blocklist->load('questions', 'answers');

        return view('admin.blocklists.show', compact('blocklist'));
    }

    public function destroy(Blocklist $blocklist)
    {
        abort_if(Gate::denies('blocklist_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blocklist->delete();

        return back();
    }

    public function massDestroy(MassDestroyBlocklistRequest $request)
    {
        Blocklist::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
