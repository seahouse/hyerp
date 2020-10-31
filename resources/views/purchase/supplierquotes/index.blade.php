@extends('navbarerp')

@section('title', '供应商报价')

@section('main')
    @can('purchase_supplierquote_view')
    <div class="panel-heading">
        <a href="{{ url('purchase/supplierquotes/createbypohead', $pohead_id) }}" class="btn btn-sm btn-success">新建</a>
        {{--
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div>
        --}}
    </div>

    <div class="panel-body">
        {!! Form::open(['url' => '/purchase/supplierquotes/search_hx', 'class' => 'pull-right form-inline']) !!}
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

    @if ($poheadquotes->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>供应商</th>
                <th>报价金额</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($poheadquotes as $poheadquote)
                <tr>
                    <td>
                        {{ $poheadquote->supplier->name }}
                    </td>
                    <td>
                        {{ $poheadquote->quote }}
                    </td>
                    <td>
                        {!! Form::open(array('route' => array('purchase.supplierquotes.destroy', $poheadquote->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                        {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach

            {{--
            <tr class="info">
                <td>合计</td>
                <td>{{ $poheadquotes->sum('amount') }}</td>
                @if (Agent::isDesktop())
                    <td></td>
                    <td></td>
                    <td>
                        @if (Auth::user()->email == "admin@admin.com")
                            {{ $poheadquotes->sum('amount_ticketed') }}
                        @endif
                    </td>
                    <td>
                        @if (Auth::user()->email == "admin@admin.com")
                            {{ $poheadquotes->sum('amount') }}
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
    {!! $poheadquotes->render() !!}
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
