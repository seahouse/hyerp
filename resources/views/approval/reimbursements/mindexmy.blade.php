@extends('app')

@section('title', '我发起的')

@section('main')
    
{{--    <div class="panel-body">
        <a href="{{ URL::to('approval/items/create') }}" class="btn btn-sm btn-success">新建</a>
        <form class="pull-right" action="/approval/items/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form>

    </div> --}}

    @if ($reimbursements->count())
        @foreach($reimbursements as $reimbursement)
        <div class="reimbList list-group">
            <a href="{{ url('/approval/reimbursements/mshow', $reimbursement->id) }}" class="list-group-item">
                {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}
                @if (Auth::user()->dingtalkGetUser()->avatar == '')
                    <div class='col-xs-2 col-sm-2 name'>{{ Auth::user()->dingtalkGetUser()->name }}</div>
                @else
                    <div class='col-xs-2 col-sm-2'><img class="name img" src="{{ Auth::user()->dingtalkGetUser()->avatar }}" /></div>
                @endif
                <div class='col-xs-7 col-sm-7 content'>
                    <div title="{{ $reimbursement->applicant->name }}的报销" class="title">{{ $reimbursement->applicant->name }}的报销</div>
                    {{-- 以下的代码判断说明：如果审批id大于0，则显示“待审批”，否则显示“审批完成”。 --}}
                    {{-- 当状态为“审批完成”时，字体为灰色 --}}
                    @if ($reimbursement->approversetting_id > 0)
                        <div class="statusTodo">待审批</div>
                    @else
                        <div class="statusDone">审批完成</div>      {{-- 此时，字体要修改为灰色 --}}
                    @endif
                </div>
                <div class='col-xs-3 col-sm-3 time'><span >{{ $reimbursement->created_at }}</span></div>
            </a>
        </div>     
        @endforeach
    @endif

{{--    @if ($reimbursements->count())    
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请日期</th>
                <th>报销编号</th>
                <th>报销金额</th>
                <th style="width: 120px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reimbursements as $reimbursement)
                <tr>
                    <td>
                        <a href="{{ url('/approval/reimbursements', $reimbursement->id) }}">{{ $reimbursement->date }}</a>
                    </td>
                    <td>
                        {{ $reimbursement->number }}
                    </td>
                    <td>
                        {{ $reimbursement->amount }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/approval/reimbursements/'.$reimbursement->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('approval.reimbursements.destroy', $reimbursement->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $reimbursements->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    --}}

@endsection
