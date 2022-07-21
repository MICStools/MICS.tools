@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.blocklist.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.blocklists.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.blocklist.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $blocklist->id }}
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>
                                        {{ trans('cruds.blocklist.fields.answer') }}
                                    </th>
                                    <td>
                                        @foreach($blocklist->answers as $key => $answer)
                                            <span class="label label-info">{{ $answer->text }}</span>
                                        @endforeach
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ trans('cruds.blocklist.fields.question') }}
                                    </th>
                                    <td>
                                        @foreach($blocklist->questions as $key => $question)
                                            <span class="label label-info">{{ $question->title }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.blocklists.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection