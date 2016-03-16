@extends('GOD::layouts.god')

@section('god.content')
<div class="panel panel-default">
    <div class="panel-heading">
        <label>{{ $G_trans('god.show') }}</label>
        <span class="pull-right">
            <form class="form-horizontal " action="{{action($G_controller . '@destroy', $model->id)}}" method="POST" role="form" onsubmit="return confirm('{{ $G_trans('god.confirm_delete_record') }}');">
                {{ csrf_field() }} {{ method_field('DELETE') }}
                @if ($allows['update'] && !$allows['approve'])
                <button type="button" class="btn btn-primary btn-sm" onClick="location.href='{{action($G_controller.'@edit', $model->id)}}'">
                    {{ $G_trans('god.edit') }}
                </button>
                @endif
                @if ($allows['delete'])
                <button type="submit" class="btn btn-danger btn-sm">
                    {{ $G_trans('god.destroy') }}
                </button>
                @endif
                <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.back();">
                    {{ $G_trans('god.back') }}
                </button>
            </form>
        </span>
    </div>
    <div class="panel-body">
        @foreach ($fields as $field => $field_info)
        <?php
            $value = isset($field_info['foreign_values']) ? array_get($field_info['foreign_values'], $model->$field, $model->$field) : $model->$field;
        ?>

        <div class="form-group">
            <label for="{{$field}}" class="col-sm-2 control-label">{{$G_trans($field_info['show'])}}</label>
            <div class="col-sm-10">
                @if (in_array($field, ['password']))
                <input type="password" class="form-control" id="{{$field}}" name="{{$field}}" value="{{$value}}" readonly>
                @else
                <input type="text" class="form-control" id="{{$field}}" name="{{$field}}" value="{{$value}}" readonly>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="panel-footer text-center">
        @if ($allows['approve'])
        <form class="form-horizontal" id="approval" name="approval" action="{{action($G_controller . '@approve', $model->id)}}" method="POST" role="form">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <input type="hidden" id="result" name="result" value="">
            <button type="button" class="btn btn-primary btn-sm" onClick="javascript:document.getElementById ('result').value=1;this.form.submit();">
                {{ $G_trans('god.pass') }}
            </button>
            <button type="button" class="btn btn-primary btn-sm" onClick="javascript:document.getElementById ('result').value=0;this.form.submit();">
                {{ $G_trans('god.reject') }}
            </button>
        </form>
        @endif
    </div>
</div>
@stop
