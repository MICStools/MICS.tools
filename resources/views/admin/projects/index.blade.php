@extends('layouts.admin')
@section('content')
<div class="content">
    @can('project_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.projects.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.project.title_singular') }}
                </a>

                {{-- Button to view soft-deleted (trashed) projects --}}
                <a class="btn btn-default pull-right" href="{{ route('admin.projects.trashed') }}" title="View soft-deleted Projects">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.project.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Project">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.shortname') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.slug') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.featured') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.training') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.logo') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.banner') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.startdate') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.enddate') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.contact') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.contactdetails') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.cost') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.funding') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.uri') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.project.fields.organisers') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $key => $project)
                                    <tr data-entry-id="{{ $project->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $project->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->shortname ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->slug ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->user->email ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $project->featured ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $project->featured ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $project->training ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $project->training ? 'checked' : '' }}>
                                        </td>
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
                                        <td>
                                            {{ $project->startdate ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->enddate ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->contact ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->contactdetails ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->cost ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->funding ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->uri ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($project->organisers as $key => $item)
                                                <span class="label label-info label-many">{{ $item->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('project_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.projects.show', $project->slug) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('project_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.projects.edit', $project->slug) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('project_delete')
                                                <form action="{{ route('admin.projects.destroy', $project->slug) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('project_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.projects.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Project:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection