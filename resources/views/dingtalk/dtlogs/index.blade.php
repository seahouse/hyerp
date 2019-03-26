@extends('navbarerp')

@section('title', '钉钉日志')

@section('main')
    @can('dingtalk_dtlogs_view')
    <div class="panel-heading">
        <div class="panel-title">钉钉 -- 钉钉日志

        </div> 
    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/dingtalk/dtlogs/search', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {!! Form::label('createdatelabel', '发起时间:', ['class' => 'control-label']) !!}
            {!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}
            {!! Form::label('createdatelabelto', '-', ['class' => 'control-label']) !!}
            {!! Form::date('createdateend', null, ['class' => 'form-control']) !!}
            {!! Form::select('creator_name', $dtlog_creatornames, null, ['class' => 'form-control', 'placeholder' => '--发起人--']) !!}

            {!! Form::select('template_name', $dtlog_templatenames, null, ['class' => 'form-control', 'placeholder' => '--日志模板--']) !!}

            {{--{!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}--}}
            {{--{!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}--}}
            {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人']); !!}--}}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
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

    {!! $dtlogs->setPath('/dingtalk/dtlogs')->appends([
        'createdatestart' => isset($inputs['createdatestart']) ? $inputs['createdatestart'] : null ,
        'createdateend' => isset($inputs['createdateend']) ? $inputs['createdateend'] : null,
        'creator_name' => isset($inputs['creator_name']) ? $inputs['creator_name'] : null,
        'template_name' => isset($inputs['template_name']) ? $inputs['template_name'] : null,
    ])->links() !!}

    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif

    @else
        无权限
    @endcan
@endsection
