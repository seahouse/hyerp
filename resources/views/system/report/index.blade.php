@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        @if (Auth::user()->email === "admin@admin.com")
        <a href="report/create" class="btn btn-sm btn-success">新建</a>
        @endif
        {{--
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/depts') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'部门管理', [], 'layouts'}}</a>
        </div>
        --}}
    </div>
    
    @if ($reports->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>名称</th>
                <th>模块</th>
                <th>描述</th>
                <th>统计</th>
                <th width="120">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>
                        {{ $report->name }}
                    </td>
                    <td>
                        {{ $report->module }}
                    </td>
                    <td>
                        {{ $report->descrip }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/report/'.$report->id.'/statistics') }}" class="btn btn-success btn-sm" target="_blank">统计</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/report/'.$report->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('system.report.destroy', $report->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $reports->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
