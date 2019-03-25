@extends('navbarerp')

@section('title', '钉钉日志')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">钉钉 -- 钉钉日志

        </div> 
    </div>
    
    <div class="panel-body">
        {{--<a href="{{ URL::to('approval/approvaltypes/create') }}" class="btn btn-sm btn-success">新建</a>--}}
{{--        <form class="pull-right" action="/approval/items/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form> --}}
    </div> 

    
    @if ($dtlogs->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>创建时间</th>
                <th>发起人</th>
                <th>日志模板</th>
                <th style="width: 120px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dtlogs as $dtlog)
                <tr>
                    <td>
                        {{ $dtlog->create_time }}
                    </td>
                    <td>
                        {{ $dtlog->creator_name }}
                    </td>
                    <td>
                        {{ $dtlog->template_name }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id) }}" class="btn btn-success btn-sm pull-left" target="_blank">查看</a>
                        {{--<a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>--}}
                        {{--{!! Form::open(array('route' => array('dingtalk.dtlogs.destroy', $dtlog->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}
                        {{--{!! Form::close() !!}--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $dtlogs->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection
