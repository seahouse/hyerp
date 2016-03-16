@extends('GOD::layouts.god')

@section('god.content')
<div class="panel panel-default">
    <div class="panel-heading">
        <label>{{ $G_trans('god.index') }}</label>
        <span class="pull-right">
            @if ($allows['create'])
            <button type="button" class="btn btn-primary btn-sm" onClick="location.href='{{action($G_controller.'@create')}}'">
                <i class="fa fa-plus"></i>{{ $G_trans('god.create') }}
            </button>
            @endif
        </span>
    </div>
    @if (!$G_isDingtalkClient)
    <div class="panel-body">
        <?php
            $searchFields = array();
            foreach ($fields as $field => $field_info) {
                if (isset($field_info['search'])) {
                    $searchFields[$field] = $field_info;
                }
            }
        ?>
        @if (isset($searchFields) and sizeof($searchFields)>0)
        <form class="form-inline pull-right" action="{{action($G_controller.'@index')}}" method="GET" role="form">
            {{ csrf_field() }}
            @foreach ($searchFields as $field => $field_info)
            <select class="form-control @if ($field==key($searchFields)) sr-only @endif" id="op-{{$field}}" name="op-{{$field}}">
                <option value="or"  @if (isset($G_input['op-'.$field]) and $G_input['op-'.$field]=='or' ) selected @endif>{{ $G_trans('god.or')  }}</option>
                <option value="and" @if (isset($G_input['op-'.$field]) and $G_input['op-'.$field]=='and') selected @endif>{{ $G_trans('god.and') }}</option>
            </select>
            <div class="form-group">
                <label class="sr-only" for="{{$field}}">{{$G_trans($field_info['show'])}}</label>
                <input type="search" class="form-control" id="{{$field}}" name="{{$field}}" placeholder="{{$G_trans($field_info['show'])}}" value="@if (isset($G_input[$field])){{$G_input[$field]}}@endif">
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-search"></i>{{ $G_trans('god.search') }}
            </button>
            <button type="button" class="btn btn-default btn-sm" onClick="javascript:$(':input', this.form).not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');this.form.submit();">
                <i class="fa fa-undo"></i>{{ $G_trans('god.reset') }}
            </button>
        </form>
        @endif
    </div>
    @endif
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                @foreach ($fields as $field => $field_info)
                <th>{{$G_trans($field_info['show'])}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($models as $model)
            <?php
                $state = "";
                if (isset($status) && isset($status['id']) && isset($status['values'])) {
                    $name = $status['id'];
                    $state = $status['values'][$model->$name];
                }
            ?>
            <tr class="{{ $state }}" onClick="location.href='{{action($G_controller.'@'.'show', $model->id)}}'">
                @foreach ($fields as $field => $field_info)
                <td>
                    {{ isset($field_info['foreign_values']) ? array_get($field_info['foreign_values'], $model->$field, $model->$field) : $model->$field }}
                </td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td class="text-center" colspan="{{sizeof($fields)+1}}">
                    {{ $G_trans('god.noRecord') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="panel-footer">
        <div class="text-center">
            {!! $models->appends($G_input)->links() !!}
        </div>
    </div>
</div>
@stop
