@extends('navbarerp')

@section('title', '采购订单')

@section('main')
    <div class="panel-heading">
        {{--
        <a href="purchaseorders/create" class="btn btn-sm btn-success">新建</a>
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div>
        --}}
    </div>

    <div class="panel-body">
        {!! Form::open(['url' => '/purchase/purchaseorders/search_hx', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
{{--
            {!! Form::label('arrivaldatelabel', '到货时间:', ['class' => 'control-label']) !!}
            {!! Form::date('datearravalfrom', null, ['class' => 'form-control']) !!}
            {!! Form::label('arrivaldatelabelto', '-', ['class' => 'control-label']) !!}
            {!! Form::date('datearravalto', null, ['class' => 'form-control']) !!}

            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '对应项目名称']) !!}
            {!! Form::label('signdatelabel', '签订日期:', ['class' => 'control-label']) !!}
            {!! Form::date('signdatefrom', null, ['class' => 'form-control']) !!}
            {!! Form::label('signdatelabelto', '-', ['class' => 'control-label']) !!}
            {!! Form::date('signdateto', null, ['class' => 'form-control']) !!}
            {!! Form::select('arrivalstatus', array(0 => '未到货', 1 => '部分到货', 2 => '全部到货'), null, ['class' => 'form-control', 'placeholder' => '--到货状态--']) !!}
            {!! Form::select('paidstatus', array(0 => '未付款', 1 => '部分付款', 2 => '全部付款'), null, ['class' => 'form-control', 'placeholder' => '--付款状态--']) !!}
            {!! Form::select('ticketedstatus', array(0 => '未开票', 1 => '部分开票', 2 => '全部开票'), null, ['class' => 'form-control', 'placeholder' => '--开票状态--']) !!}
            {!! Form::text('batch', null, ['class' => 'form-control', 'placeholder' => '批号']) !!}
--}}
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '采购订单编号']) !!}

            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    @if ($purchaseorders->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>合同金额</th>
                <th>供应商</th>
                <th>到货比例</th>
                <th>对应销售订单</th>
                <th>入库记录</th>
                {{--
                <th>物料</th>
                --}}
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseorders as $purchaseorder)
                <tr>
                    <td>
                        {{ $purchaseorder->number }}
                    </td>
                    <td>
                        {{ $purchaseorder->amount }}
                    </td>
                    <td>
                        {{ $purchaseorder->custinfo_name }}
                    </td>
                    <td>
                        {{ $purchaseorder->arrival_percent }}
                    </td>
                    <td>
                        {{ $purchaseorder->sohead->number . '|' . $purchaseorder->sohead->descrip }}
                        {{--
                        @if (isset($purchaseorder->sohead->number)) {{ $purchaseorder->sohead->number }} @endif
                        --}}
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/receiptorders_hx') }}" target="_blank" class="btn btn-default btn-sm">查看</a>
                    </td>
                    {{--
                    <td>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/detail') }}" target="_blank">明细</a>
                    </td>
                    --}}
                    <td>
                        {{--
                        <a href="{{ URL::to('/purchase/purchaseorders/'.$purchaseorder->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/receiving') }}" class="btn btn-success btn-sm pull-left">收货</a>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/payments') }}" target="_blank" class="btn btn-success btn-sm pull-left">付款</a>
                        {!! Form::open(array('route' => array('purchase.purchaseorders.destroy', $purchaseorder->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                        --}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $purchaseorders->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
