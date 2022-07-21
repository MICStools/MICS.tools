@extends('layouts.admin')
@section('content')
<div class="content">
    @can('domain_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.domains.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.domain.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.domain.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Domain">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.domain.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.domain.fields.order') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.domain.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.domain.fields.slug') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.domain.fields.background') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($domains as $key => $domain)
                                    <tr data-entry-id="{{ $domain->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $domain->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $domain->order ?? '' }}
                                        </td>
                                        <td>
                                            {{ $domain->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $domain->slug ?? '' }}
                                        </td>
                                        <td>
                                            @if($domain->background)
                                                <a href="{{ $domain->background->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $domain->background->getUrl('thumb') }}">
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('domain_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.domains.show', $domain->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('domain_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.domains.edit', $domain->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('domain_delete')
                                                <form action="{{ route('admin.domains.destroy', $domain->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('domain_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.domains.massDestroy') }}",
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
  let table = $('.datatable-Domain:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection