@extends('navbarerp')

@section('title', '采购申请单')

@section('main')
<div class="panel-heading">
    {{--<a href="prheads/create" class="btn btn-sm btn-success">新建</a>--}}

    {{-- <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
</div> --}}
</div>

<div class="panel-body">

    {!! Form::open(['url' => '/purchase/prheads/search', 'class' => 'pull-right form-inline']) !!}
    <div class="form-group-sm">
        {{--{!! Form::label('approvaldatelabel', '审批时间:', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::date('approvaldatestart', null, ['class' => 'form-control']) !!}--}}
        {{--{!! Form::label('approvaldatelabelto', '-', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::date('approvaldateend', null, ['class' => 'form-control']) !!}--}}

        {{--{!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}--}}

        {{--{!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}--}}
        {{--{!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']) !!}--}}
        {!! Form::select('purchase_type', $purchase_types, null, ['class' => 'form-control', 'placeholder' => '--采购类型--']) !!}
        {!! Form::text('projectname', null, ['class' => 'form-control', 'placeholder' => '对应项目']) !!}
        {!! Form::text('productname', null, ['class' => 'form-control', 'placeholder' => '商品名称']) !!}
        {!! Form::text('applicant', null, ['class' => 'form-control', 'placeholder' => '申请人']) !!}
        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '编号']) !!}
        {!! Form::text('business_id', null, ['class' => 'form-control', 'placeholder' => '审批单号']) !!}
        {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        {{--<a class="btn btn-default btn-sm" id="btnPrint">打印</a>--}}
    </div>
    {!! Form::close() !!}
</div>

@if ($prheads->count())
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>编号</th>
            <th>申请人</th>
            <th>对应项目</th>
            @if(!Auth::user()->hasRole('supplier'))
            <th>类型</th>
            <th>对应审批编号</th>
            <th>物料</th>
            <th>供应商</th>
            @endif
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($prheads as $prhead)
        <tr>
            <td>
                {{ $prhead->number }}
            </td>
            <td>
                {{ $prhead->applicant->name }}
            </td>
            <td>
                {{ $prhead->sohead->number . '!' . $prhead->sohead->descrip }}
            </td>
            @if(!Auth::user()->hasRole('supplier'))
            <td>
                {{ $prhead->type }}
            </td>
            <td>
                {{ $prhead->associated_business_id() }}
            </td>
            <td>
                <a href="{{ URL::to('/purchase/pritems/') . '?prhead_id=' . $prhead->id }}" target="_blank">明细</a>
            </td>
            <td>
                @foreach($prhead->suppliers as $s)
                @if($s->selected) &#10004 @endif{{ $s->item->name }}<br>
                @endforeach
            </td>
            @endif
            <td>
                @if(Auth::user()->hasRole('supplier'))
                <a href="{{ URL::to('/purchase/prheads/') . '/' . $prhead->id . '/quote' }}" class="btn btn-success btn-sm pull-left">报价</a>
                @else
                <a href="{{ URL::to('/purchase/prtypes/') . '?prhead_id=' . $prhead->id }}" class="btn btn-success btn-sm pull-left">分组</a>
                <a href="{{ URL::to('/purchase/prheads/') . '/' . $prhead->id . '/edit' }}" class="btn btn-success btn-sm pull-left">供应商管理</a>
                {!! Form::open(array('route' => array('purchase.prheads.destroy', $prhead->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                {!! Form::close() !!}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{!! $prheads->setPath('/purchase/prheads')->appends($inputs)->links() !!}
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{'无记录', [], 'layouts'}}
</div>
@endif


@stop