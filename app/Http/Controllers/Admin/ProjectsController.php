<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Country;
use App\Models\Project;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::with(['user', 'organisers', 'participants', 'observers', 'created_by', 'media'])->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $organisers = Country::pluck('name', 'id');
        $participants = Country::pluck('name', 'id');
        $observers = Country::pluck('name', 'id');

        return view('admin.projects.create', compact('users', 'organisers', 'participants', 'observers'));
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->all());
        $project->organisers()->sync($request->input('organisers', []));
        $project->participants()->sync($request->input('participants', []));
        $project->observers()->sync($request->input('observers', []));
        if ($request->input('logo', false)) {
            $project->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
        }

        if ($request->input('banner', false)) {
            $project->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $project->id]);
        }

        return redirect()->route('admin.projects.index');
    }

    public function edit(Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $organisers = Country::pluck('name', 'id');
        $participants = Country::pluck('name', 'id');
        $observers = Country::pluck('name', 'id');

        $project->load('user', 'organisers', 'participants', 'observers', 'created_by');

        return view('admin.projects.edit', compact('users', 'organisers', 'participants', 'observers', 'project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());
        $project->organisers()->sync($request->input('organisers', []));
        $project->participants()->sync($request->input('participants', []));
        $project->observers()->sync($request->input('observers', []));
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

        return redirect()->route('admin.projects.index');
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->load('user', 'organisers', 'participants', 'observers', 'created_by', 'projectResults', 'projectsAnswers');

        return view('admin.projects.show', compact('project'));
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

    // Soft-deleted (trashed) project management functionality
    public function trashed()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::onlyTrashed()->with('user')->get();

        return view('admin.projects.trashed', compact('projects'));
    }

    public function updateSlug(Request $request, $id)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project = Project::onlyTrashed()->findOrFail($id);

        $request->validate([
            'slug' => 'required|string|unique:projects,slug,' . $project->id,
        ]);

        $project->slug = $request->input('slug');
        $project->save();

        return redirect()->route('admin.projects.trashed')->with('success', 'Slug updated.');
    }

    public function restore($id)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();

        return redirect()->route('admin.projects.trashed')->with('success', 'Project restored.');
    }

    public function forceDelete($id)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project = Project::onlyTrashed()
            ->with(['organisers', 'participants', 'observers', 'topics'])
            ->findOrFail($id);

        DB::transaction(function () use ($project) {
            // 1) Delete hasMany children that have FKs to projects.id
            // Results (adjust relation name/table if needed)
            // If Result uses SoftDeletes, hard delete them:
            if (method_exists($project, 'projectResults')) {
                $project->projectResults()->withTrashed()->forceDelete();
            }

            // 2) Detach all many-to-many pivots
            $project->organisers()->detach();
            $project->participants()->detach();
            $project->observers()->detach();
            $project->topics()->detach();
            if (method_exists($project, 'projectsAnswers')) {
                $project->projectsAnswers()->detach();
            }

            // 3) Remove media (Spatie)
            try {
                // safest: delete all model media records/files
                $project->media()->each->delete();
            } catch (\Throwable $e) {
                // non-fatal
            }

            // 4) Finally remove the project row
            $project->forceDelete();
        });

        return redirect()->route('admin.projects.trashed')->with('success', 'Project permanently deleted.');
    }

}
