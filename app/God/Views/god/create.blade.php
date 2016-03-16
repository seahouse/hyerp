@extends('GOD::layouts.god')

@section('god.content')
<form class="form-horizontal" action="{{action($G_controller . '@store')}}" method="POST" autocomplete="on" role="form">
    {{ csrf_field() }}
    <div class="panel panel-default">
        <div class="panel-heading">
            <label>{{ $G_trans('god.create') }}</label>
            <span class="pull-right">
                <button type="submit" class="btn btn-primary btn-sm">
                    {{ $G_trans('god.store') }}
                </button>
                <button type="reset"  class="btn btn-default btn-sm">
                    {{ $G_trans('god.reset') }}
                </button>
                <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.back();">
                    {{ $G_trans('god.back') }}
                </button>
            </span>
        </div>
        <div class="panel-body">
            @foreach ($fields as $field => $field_info)
            <div class="form-group">
                <label for="{{$field}}" class="col-sm-2 control-label">{{$G_trans($field_info['show'])}}</label>
                <div class="col-sm-10">

                    @if (isset($field_info['foreign_values']))
                    <select class="form-control" id="{{$field}}" name="{{$field}}">
                        <option selected>{{ $G_trans('god.please_select') }}</option>
                        @foreach ($field_info['foreign_values'] as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="text" class="form-control" id="{{$field}}" name="{{$field}}" placeholder="{{ $G_trans('god.please_input') }}" value="{{old($field)}}">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="panel-footer text-right">
        </div>
    </div>
</form>
@stop
