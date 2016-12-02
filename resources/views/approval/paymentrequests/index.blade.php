@extends('navbarerp')

@section('title', '供应商付款')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 供应商付款
{{--            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div> --}}
        </div>
    </div>
    
    <div class="panel-body">
{{--
        <a href="{{ URL::to('approval/items/create') }}" class="btn btn-sm btn-success">新建</a>
--}}
{{--
        <form class="pull-right" action="/approval/paymentrequests/export" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">导出</button>
            </div>
        </form>

        <div class="pull-right">
            <button class="btn btn-default btn-sm" id="btnExport">导出</button>
        </div>
--}}
        <form class="pull-right" action="/approval/paymentrequests/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="支付对象、对应项目名称、申请人">    
            </div>
        </form>
    </div> 

    
    @if ($paymentrequests->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请日期</th>
                <th>支付对象</th>
{{--
                <th>报销编号</th>
--}}
                <th>本次请款额</th>
                @if (Agent::isDesktop())
                <th>对应项目</th>
                <th>已开票金额</th>
                <th>合同金额</th>
                @endif

                <th>申请人</th>
                <th>审批状态</th>
                @if (Agent::isDesktop())
                <th style="width: 120px">操作</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($paymentrequests as $paymentrequest)
                <tr>
                    <td>
                        <a href="{{ url('/approval/paymentrequests', $paymentrequest->id) }}" target="_blank">{{ $paymentrequest->created_at }}</a>
                    </td>
                    <td>
                        @if (isset($paymentrequest->supplier_hxold->name))  {{ $paymentrequest->supplier_hxold->name }} @else @endif
                    </td>
{{--
                    <td>
                        {{ $paymentrequest->number }}
                    </td>
--}}
                    <td>
                        {{ $paymentrequest->amount }}
                    </td>
                    @if (Agent::isDesktop())
                    <td title="@if (isset($paymentrequest->purchaseorder_hxold->descrip)) {{ $paymentrequest->purchaseorder_hxold->descrip }} @else @endif">
                        @if (isset($paymentrequest->purchaseorder_hxold->descrip)) {{ str_limit($paymentrequest->purchaseorder_hxold->descrip, 40) }} @else @endif
                    </td>
                    <td>
                        @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed)) {{ $paymentrequest->purchaseorder_hxold->amount_ticketed }} @else @endif
                    </td>
                    <td>
                        @if (isset($paymentrequest->purchaseorder_hxold->amount)) {{ $paymentrequest->purchaseorder_hxold->amount }} @else @endif
                    </td>
                    @endif
                    <td>
                        {{ $paymentrequest->applicant->name }}
                    </td>
                    <td>
                        @if ($paymentrequest->approversetting_id > 0) <div class="text-primary">审批中</div> @elseif ($paymentrequest->approversetting_id == 0) <div class="text-success">已通过</div> @else <div class="text-danger">未通过</div> @endif
                    </td>
                    @if (Agent::isDesktop())
                    <td>
{{--                        <a href="{{ URL::to('/approval/paymentrequests/'.$paymentrequest->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('approval.paymentrequests.destroy', $paymentrequest->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!} --}}
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>

    </table>
    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/paymentrequests')->appends(['key' => $key])->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/paymentrequests')->links() !!}
    @endif
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnExport").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('approval/paymentrequests/export') }}",
                    // data: $("form#formAddVendbank").serialize(),
                    // dataType: "json",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert('error');
                    },
                    success:function(result){
                        alert("导出成功:" + result);
                    },
                }); 
            });
        });
    </script>
@endsection