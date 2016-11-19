@extends('app')

@section('title', '我发起的')

@section('main')
    
    {!! Form::open(['url' => '/approval/mindexmy/search', 'method' => 'post', 'role' => 'search']) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control']) !!}
                    <span class="input-group-btn">
                        {!! Form::submit('查找', ['class' => 'btn btn-default']) !!}
                    </span>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    @include('approval._list',
        [
            'href_pre' => '/approval/reimbursements/mshow/', 'href_suffix' => '',
            'href_pre_paymentrequest' => '/approval/paymentrequests/'
        ])

    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/mindexmy')->appends(['key' => $key])->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/mindexmy')->links() !!}
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
