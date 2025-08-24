@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.project.title_singular') }} — Trashed
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Project">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>{{ trans('cruds.project.fields.id') }}</th>
                                    <th>{{ trans('cruds.project.fields.name') }}</th>
                                    <th>{{ trans('cruds.project.fields.shortname') }}</th>
                                    <th>{{ trans('cruds.project.fields.slug') }}</th>
                                    <th>{{ trans('cruds.project.fields.user') }}</th>
                                    <th>{{ trans('cruds.user.fields.email') }}</th>
                                    <th>{{ trans('cruds.project.fields.featured') }}</th>
                                    <th>{{ trans('cruds.project.fields.training') }}</th>
{{--
                                    <th>{{ trans('cruds.project.fields.logo') }}</th>
                                    <th>{{ trans('cruds.project.fields.banner') }}</th>
                                    <th>{{ trans('cruds.project.fields.startdate') }}</th>
                                    <th>{{ trans('cruds.project.fields.enddate') }}</th>
                                    <th>{{ trans('cruds.project.fields.contact') }}</th>
                                    <th>{{ trans('cruds.project.fields.contactdetails') }}</th>
                                    <th>{{ trans('cruds.project.fields.cost') }}</th>
                                    <th>{{ trans('cruds.project.fields.funding') }}</th>
                                    <th>{{ trans('cruds.project.fields.uri') }}</th>
                                    <th>{{ trans('cruds.project.fields.organisers') }}</th> 
--}}
                                    <th>Deleted at</th> {{-- NEW --}}
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $key => $project)
                                    <tr data-entry-id="{{ $project->id }}">
                                        <td></td>
                                        <td>{{ $project->id ?? '' }}</td>
                                        <td>{{ $project->name ?? '' }}</td>
                                        <td>{{ $project->shortname ?? '' }}</td>
                                        <td>{{ $project->slug ?? '' }}</td>
                                        <td>{{ $project->user->name ?? '' }}</td>
                                        <td>{{ $project->user->email ?? '' }}</td>
                                        <td>
                                            <span style="display:none">{{ $project->featured ?? '' }}</span>
                                            <input type="checkbox" disabled {{ $project->featured ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $project->training ?? '' }}</span>
                                            <input type="checkbox" disabled {{ $project->training ? 'checked' : '' }}>
                                        </td>
{{--
                                        <td>
                                             @if($project->logo)
                                                <a href="{{ $project->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $project->logo->getUrl('thumb') }}">
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->banner)
                                                <a href="{{ $project->banner->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $project->banner->getUrl('thumb') }}">
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $project->startdate ?? '' }}</td>
                                        <td>{{ $project->enddate ?? '' }}</td>
                                        <td>{{ $project->contact ?? '' }}</td>
                                        <td>{{ $project->contactdetails ?? '' }}</td>
                                        <td>{{ $project->cost ?? '' }}</td>
                                        <td>{{ $project->funding ?? '' }}</td>
                                        <td>{{ $project->uri ?? '' }}</td>
                                        <td>
                                            @foreach($project->organisers as $key => $item)
                                                <span class="label label-info label-many">{{ $item->name }}</span>
                                            @endforeach
                                        </td>
 --}}
                                        <td>{{ optional($project->deleted_at)->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @can('project_edit')
                                                {{-- Update slug (inline) --}}
                                                <form action="{{ route('admin.projects.updateSlug', $project->id) }}" method="POST" style="display:inline-block; margin-right:6px;">
                                                    @csrf
                                                    <div class="input-group input-group-sm" style="max-width: 260px;">
                                                        <input type="text" name="slug" class="form-control" value="{{ $project->slug }}" placeholder="slug">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-xs btn-info" type="submit">Update slug</button>
                                                        </span>
                                                    </div>
                                                </form>
                                            @endcan

                                            @can('project_delete')
                                                {{-- Restore --}}
                                                <form action="{{ route('admin.projects.restore', $project->id) }}" method="POST" style="display:inline-block; margin-right:6px;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-secondary">Restore</button>
                                                </form>

                                                {{-- Force delete --}}
                                                <form action="{{ route('admin.projects.forceDelete', $project->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger">Force delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
      // Keep DataTables config but remove the mass soft-delete button
      let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

      $.extend(true, $.fn.dataTable.defaults, {
        orderCellsTop: true,
        order: [[ 1, 'desc' ]], // still sort by ID
        pageLength: 100,
      });

      let table = $('.datatable-Project:not(.ajaxTable)').DataTable({ buttons: dtButtons })

      $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
          $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
      });
    })
</script>
@endsection
