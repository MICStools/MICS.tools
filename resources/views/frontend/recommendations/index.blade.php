@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('recommendation_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.recommendations.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.recommendation.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.recommendation.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Recommendation">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.domain') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.questions') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.minscore') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recommendation.fields.maxscore') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recommendations as $key => $recommendation)
                                    <tr data-entry-id="{{ $recommendation->id }}">
                                        <td>
                                            {{ $recommendation->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recommendation->domain->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recommendation->title ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($recommendation->questions as $key => $item)
                                                <span>{{ $item->title }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $recommendation->minscore ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recommendation->maxscore ?? '' }}
                                        </td>
                                        <td>
                                            @can('recommendation_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.recommendations.show', $recommendation->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('recommendation_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.recommendations.edit', $recommendation->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('recommendation_delete')
                                                <form action="{{ route('frontend.recommendations.destroy', $recommendation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('recommendation_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.recommendations.massDestroy') }}",
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
  let table = $('.datatable-Recommendation:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection