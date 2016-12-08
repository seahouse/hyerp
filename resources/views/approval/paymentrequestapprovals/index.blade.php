@extends('navbarerp')

@section('title', '供应商付款单审批')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 报销
{{--            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div> --}}
        </div>
    </div>
    
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

    
    @if ($paymentrequestapprovals->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>审批时间</th>
                <th>审批人</th>
                <th>审批状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentrequestapprovals as $paymentrequestapproval)
                <tr>
                    <td>
                        <a href="{{ url('/approval/paymentrequests', $paymentrequestapproval->paymentrequest_id) }}" target="_blank">{{ $paymentrequestapproval->created_at }}</a>
                    </td>
                    <td>
                        {{ $paymentrequestapproval->approver->name }}
                    </td>
                    <td>
                        {{ $paymentrequestapproval->status }}
                    </td>
                    <td>
{{--
                        <a href="{{ URL::to('/approval/reimbursements/'.$reimbursement->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('approval.reimbursements.destroy', $reimbursement->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $paymentrequestapprovals->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection
