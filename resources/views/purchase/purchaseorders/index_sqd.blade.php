@extends('navbarerp')

@section('title', '采购订单')

@section('main')
    @can('purchase_purchaseorder_view')
    <div class="panel-heading">
        <a href="create_hx" class="btn btn-sm btn-success">新建</a>
        {{--
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div>
        --}}
    </div>

    <div class="panel-body">
        {!! Form::open(['url' => '/purchase/purchaseorders/search_hx', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {{--
            {!! Form::select('sohead_id', $poheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '--订单--']) !!}
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
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '采购订单编号']) !!}

            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            --}}
        </div>
        {!! Form::close() !!}
    </div>

    @if ($purchaseorders->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>申请日期</th>
                <th>到位日期</th>
                <th>申请人</th>
                <th>采购商品</th>
                <th>对应销售订单</th>
                <th>物料</th>
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
                        {{ \Carbon\Carbon::parse($purchaseorder->add_datetime)->toDateString() }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($purchaseorder->request_arrival_date)->toDateString() }}
                    </td>
                    <td>
                        @if (isset($purchaseorder->applicant)) {{ $purchaseorder->applicant->name }} @else - @endif
                    </td>
                    <td>
                        {{ $purchaseorder->productname }}
                    </td>
                    <td @if (isset($purchaseorder->sohead)) title="{{ $purchaseorder->sohead->number . '|' . $purchaseorder->sohead->descrip }}" @else @endif>
                        @if (isset($purchaseorder->sohead)) {{ str_limit($purchaseorder->sohead->number . '|' . $purchaseorder->sohead->descrip, 30) }} @else - @endif
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/detail_hxold') }}" target="_blank">明细</a>
                    </td>
                    <td>
                        @if ($purchaseorder->status == 20)
                            <a href="{{ URL::to('/purchase/purchaseorders/'.$purchaseorder->id.'/edit_hx') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        @endif
                            <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id) }}" class="btn btn-success btn-sm pull-left">查看</a>
                            <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/supplierquotes') }}" class="btn btn-success btn-sm pull-left">供应商报价</a>

                            {{--
                            <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/payments') }}" target="_blank" class="btn btn-success btn-sm pull-left">付款</a>
                            {!! Form::open(array('route' => array('purchase.purchaseorders.destroy', $purchaseorder->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                                {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                            --}}
                    </td>
                </tr>
            @endforeach

            {{--
            <tr class="info">
                <td>合计</td>
                <td>{{ $purchaseorders->sum('amount') }}</td>
                @if (Agent::isDesktop())
                    <td></td>
                    <td></td>
                    <td>
                        @if (Auth::user()->email == "admin@admin.com")
                            {{ $purchaseorders->sum('amount_ticketed') }}
                        @endif
                    </td>
                    <td>
                        @if (Auth::user()->email == "admin@admin.com")
                            {{ $purchaseorders->sum('amount') }}
                        @endif
                    </td>
                @endif
                <td></td>
                <td></td>
                <td></td>
                @if (Agent::isDesktop())
                    <td></td>
                @endif
            </tr>
            --}}
        </tbody>

    </table>
    {!! $purchaseorders->render() !!}
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
