@extends('navbarerp')

@section('title', '销售订单')

@section('main')
    @can('sales_salesorder_view')
    <div class="panel-heading">
        <div class="panel-title">销售 -- 销售订单
            {{--
            <div class="pull-right">
                <a href="{{ URL::to('sales/salesreps') }}" class="btn btn-sm btn-success">{{'销售代表管理'}}</a>
                <a href="{{ URL::to('sales/terms') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'付款条款管理', [], 'layouts'}}</a>
            </div>
            --}}
        </div>
    </div>
    
    <div class="panel-body">
        {{--
        <a href="{{ URL::to('/sales/salesorders/create') }}" class="btn btn-sm btn-success">新建</a>
           --}}
        {!! Form::open(['url' => '/sales/salesorderhx/search', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '订单编号、项目名称']) !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}

        {{--<form class="pull-right" action="/sales/salesorders/search" method="post">--}}
            {{--{!! csrf_field() !!}--}}
            {{--<div class="pull-right">--}}
                {{--<button type="submit" class="btn btn-default btn-sm">查找</button>--}}
            {{--</div>--}}
            {{--<div class="pull-right input-group-sm">--}}
                {{--<input type="text" class="form-control" name="key" placeholder="Search">    --}}
            {{--</div>--}}
        {{--</form>--}}
        
    </div>

    @if ($salesorders->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>客户</th>
                <th>工程名称</th>
                <th>订单日期</th>
                <th>图纸物料清单</th>
                {{--
                <th>物料</th>
                --}}
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesorders as $salesorder)
                <tr>
                    <td>
                        {{ $salesorder->number }}
                    </td>
                    <td>
                        @if (isset($salesorder->custinfo->name)) {{ $salesorder->custinfo->name }} @endif
                    </td>
                    <td>
                        {!! $salesorder->descrip !!}
                    </td>
                    <td>
                        {{ substr($salesorder->orderdate, 0, 10) }}
                    </td>
                    <td>
                        <a class="btn btn-sm btn-default" href={!! URL("sales/salesorderhx/" . $salesorder->id . "/dwgbom") !!} role="button" target="_blank">物料清单</a>
                    </td>
    {{--
    <td>
        @if (isset($salesorder->salesrep->name)) {{ $salesorder->salesrep->name }} @endif
    </td>
    <td>
        <a href="{{ URL::to('/sales/soitems/' . $salesorder->id . '/list') }}" target="_blank">明细</a>
    </td>
    --}}
    <td>
        @can('sales_salesorder_edit')
            {{-- 如果换行, 就2个按钮分别加float:left --}}
            <a href="{{ URL::to('/sales/salesorderhx/'.$salesorder->id.'/edit') }}" class="btn btn-success btn-sm pull-left" style="margin-right: 2px">编辑</a>
        @endcan
        {{--
        @can('sales_salesorder_checktaxrateinput')
            <a href="{{ URL::to('/sales/salesorderhx/'.$salesorder->id.'/checktaxrateinput') }}" class="btn btn-success btn-sm pull-left" target="_blank">检查税率输入</a>
        @endcan
            --}}

        {{--
        <a href="{{ URL::to('/sales/salesorders/' . $salesorder->id . '/ship') }}" class="btn btn-success btn-sm pull-left">发货</a>
        <a href="{{ URL::to('/sales/salesorders/' . $salesorder->id . '/receiptpayments') }}" target="_blank" class="btn btn-success btn-sm pull-left">收款</a>
        {!! Form::open(array('route' => array('sales.salesorders.destroy', $salesorder->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
        {!! Form::close() !!}
        --}}
    </td>
</tr>
@endforeach
</tbody>

</table>
    @if (isset($inputs))
        {!! $salesorders->setPath('/sales/salesorderhx')->appends($inputs)->links() !!}
    @else
        {!! $salesorders->setPath('/sales/salesorderhx')->links() !!}
    @endif
@else
<div class="alert alert-warning alert-block">
<i class="fa fa-warning"></i>
{{'无记录', [], 'layouts'}}
</div>
@endif

    @else
        无权限。
    @endcan
@endsection

@section('script')
    @if (Agent::isDesktop())
        <script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function(e) {



//                console.log(DingTalkPC.ua.isInDingTalk);
                if (DingTalkPC.ua.isInDingTalk)
                {
                    $("a").attr("target", "_self");

                }



            });
        </script>
    @endif
@endsection