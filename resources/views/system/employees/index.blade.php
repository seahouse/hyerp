@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="employees/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/depts') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'部门管理', [], 'layouts'}}</a>
            <a href="{{ URL::to('system/images') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'图片管理', [], 'layouts'}}</a>
        </div>
<!--         <h2> -->
<!--             {{ ('物料') }} -->
<!--         </h2> -->
    </div>
    

    @if ($employees->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>姓名</th>
                <th>部门</th>
                <th>入职日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>
                        {{ $employee->number }}
                    </td>
                    <td>
                        {{ $employee->name }}
                    </td>
                    <td>
                        @if (isset($employee->dept->name)) {{ $employee->dept->name }} @endif
                    </td>
                    <td>
                        {{ $employee->startdate }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/employees/'.$employee->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('system.employees.destroy', $employee->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $employees->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
