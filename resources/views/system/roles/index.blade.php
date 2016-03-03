@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="roles/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/permissions') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'权限管理', [], 'layouts'}}</a>
        </div>
    </div> 
    

    @if ($roles->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>名称</th>
                <th>显示名称</th>
                <th>描述</th>
                <th>权限</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>
                        {{ $role->name }}
                    </td>
                    <td>
                        {{ $role->display_name }}
                    </td>
                    <td>
                        {{ $role->description }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/roles/'.$role->id.'/permissions') }}">明细</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/roles/'.$role->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('system.roles.destroy', $role->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $roles->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
