<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDomainRequest;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DomainsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('domain_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::with(['media'])->get();

        return view('admin.domains.index', compact('domains'));
    }

    public function create()
    {
        abort_if(Gate::denies('domain_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.domains.create');
    }

    public function store(StoreDomainRequest $request)
    {
        $domain = Domain::create($request->all());

        if ($request->input('background', false)) {
            $domain->addMedia(storage_path('tmp/uploads/' . basename($request->input('background'))))->toMediaCollection('background');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $domain->id]);
        }

        return redirect()->route('admin.domains.index');
    }

    public function edit(Domain $domain)
    {
        abort_if(Gate::denies('domain_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $domain->update($request->all());

        if ($request->input('background', false)) {
            if (!$domain->background || $request->input('background') !== $domain->background->file_name) {
                if ($domain->background) {
                    $domain->background->delete();
                }
                $domain->addMedia(storage_path('tmp/uploads/' . basename($request->input('background'))))->toMediaCollection('background');
            }
        } elseif ($domain->background) {
            $domain->background->delete();
        }

        return redirect()->route('admin.domains.index');
    }

    public function show(Domain $domain)
    {
        abort_if(Gate::denies('domain_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->load('domainQuestions');

        return view('admin.domains.show', compact('domain'));
    }

    public function destroy(Domain $domain)
    {
        abort_if(Gate::denies('domain_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->delete();

        return back();
    }

    public function massDestroy(MassDestroyDomainRequest $request)
    {
        Domain::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('domain_create') && Gate::denies('domain_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Domain();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
