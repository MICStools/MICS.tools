<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Country;
use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Support\Str;

use Gate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;


class ProjectsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::with(['user', 'organisers', 'topics', 'created_by', 'media'])->get();

        return view('frontend.projects.index', compact('projects'));
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::where('id', Auth::id())->pluck('name', 'id'); //User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $organisers = Country::pluck('name', 'id');

        $topics = Topic::all()->pluck('name', 'id');

        return view('frontend.projects.create', compact('users', 'organisers', 'topics'));
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->all());
        $project->organisers()->sync($request->input('organisers', []));
        if ($request->input('logo', false)) {
            $project->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
        }

        if ($request->input('banner', false)) {
            $project->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $project->id]);
        }

        //return redirect()->route('frontend.projects.index');
        //return view('frontend.projects.show', compact('project'));
        return redirect()->route('frontend.projects.show', $project->slug);
    }

    public function edit($project_slug)
    {
        $project = Project::where('slug', $project_slug)->with(['user', 'organisers', 'created_by', 'topics'])->first();

        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $organisers = Country::pluck('name', 'id');

        $topics = Topic::pluck('name','id');

        $project->load('user', 'organisers', 'topics', 'created_by');

        return view('frontend.projects.edit', compact('users', 'organisers', 'topics', 'project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        //ddd($request);
        $project->update($request->all());
        $project->organisers()->sync($request->input('organisers', []));
        $project->topics()->sync($request->input('topics', []));
        if ($request->input('logo', false)) {
            if (!$project->logo || $request->input('logo') !== $project->logo->file_name) {
                if ($project->logo) {
                    $project->logo->delete();
                }
                $project->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($project->logo) {
            $project->logo->delete();
        }

        if ($request->input('banner', false)) {
            if (!$project->banner || $request->input('banner') !== $project->banner->file_name) {
                if ($project->banner) {
                    $project->banner->delete();
                }
                $project->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
            }
        } elseif ($project->banner) {
            $project->banner->delete();
        }

        //return redirect()->route('frontend.projects.index');
        return redirect()->route('frontend.projects.show', ['project' => $project->slug]);
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Project::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('project_create') && Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Project();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function check_slug(Request $request)
    {
        $slug = Str::of($request->shortname)->slug('-');
        if ('create' == $slug) {
            
        }
        return response()->json(['slug' => $slug]);
    }
}
