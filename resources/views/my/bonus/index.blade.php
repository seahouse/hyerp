@extends('navbarerp')

@section('title', '我的奖金')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">我的 -- 奖金
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

        @if (Auth::user()->email === "admin@admin.com")
        <form class="pull-right" action="/approval/paymentrequests/export" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">导出</button>
            </div>
        </form>
        @endif

        {!! Form::open(['url' => '/my/bonus', 'class' => 'pull-right form-inline']) !!}
            <div class="form-group-sm">
                {!! Form::label('receivedatelabel', '收款时间:', ['class' => 'control-label']) !!}
                {!! Form::date('receivedatestart', null, ['class' => 'form-control']) !!}
                {!! Form::label('receiveldatelabelto', '-', ['class' => 'control-label']) !!}
                {!! Form::date('receivedateend', null, ['class' => 'form-control']) !!}
                {{--
                {!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}

                {!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
                {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人']); !!}
                --}}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            </div>
        {!! Form::close() !!}
    </div> 


    @if ($items->count())
    <?php $totalbonus = 0.0; ?>
    <table id="userDataTable" class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>订单名称</th>
                <th>订单金额</th>

                <th>区间收款</th>
                <th>收款日期</th>
                <th>奖金系数</th>
                <th>应发奖金</th>
                {{--
                <th>合同金额</th>

                <th>申请人</th>
                <th>审批状态</th>
                <th>付款状态</th>
                @if (Agent::isDesktop())
                <th style="width: 150px">操作</th>
                @endif
                --}}
            </tr>
        </thead>

        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        {{ $item->sohead->projectjc }}
                    </td>
                    <td>
                        {{ $item->sohead->amount }}
                    </td>
                    <td>
                        {{ $item->amount }}
                    </td>
                    <td>
                        {{ substr($item->date, 0, 10) }}
                    </td>
                    <td>
                        {{ $item->sohead->getBonusfactorByPolicy() }}
                    </td>
                    <td>
                        {{--{{ dd(array_first($item->sohead->getAmountpertenthousandBySohead())) }}--}}
                        <?php
                            $bonus = $item->amount * $item->sohead->getBonusfactorByPolicy() * array_first($item->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                            $totalbonus += $bonus;
                        ?>
                        {{ $bonus }}
                    </td>
                    {{--
                    <td>
                        {{ $item->sohead->receiptpayments->sum('amount') / $item->sohead->amount }}
                    </td>
                    --}}
                    {{--
                    <td>
                        @if (isset($paymentrequest->purchaseorder_hxold->amount)) {{ $paymentrequest->purchaseorder_hxold->amount }} @else @endif
                    </td>
                    <td>
                        {{ $paymentrequest->applicant->name }}
                    </td>
                    <td>
                        @if ($paymentrequest->approversetting_id > 0)
                            <div class="text-primary">审批中</div>
                        @elseif ($paymentrequest->approversetting_id == 0)
                            <div class="text-success">已通过</div>
                        @elseif ($paymentrequest->approversetting_id == -3)
                            <div class="text-warning">撤回中</div>
                        @elseif ($paymentrequest->approversetting_id == -4)
                            <div class="text-danger">已撤回</div>
                        @else
                            <div class="text-danger">未通过</div>
                        @endif
                    </td>
                    <td>
                        @if ($paymentrequest->approversetting_id === 0)

                            @if (isset($paymentrequest->purchaseorder_hxold->payments))
                                @if ($paymentrequest->paymentrequestapprovals->max('created_at') < $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
                                    <div class="text-success">已付款</div>
                                @endif
                            @endif
                        @endif
                    </td>
                    @if (Agent::isDesktop())
                    <td>
                    @can('approval_paymentrequest_payment_create')
                        <a href="{{ url('/approval/paymentrequests/' . $paymentrequest->id . '/pay') }}" target="_blank" class="btn btn-success btn-sm pull-left
                        @if ($paymentrequest->approversetting_id === 0)
                            @if (isset($paymentrequest->purchaseorder_hxold->payments))
                                @if ($paymentrequest->paymentrequestapprovals->max('created_at') > $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
                                    abled
                                @else
                                    disabled
                                @endif
                            @else
                                disabled
                            @endif
                        @else
                            disabled
                        @endif
                        ">付款</a>
                    @endcan

                            @can('approval_paymentrequest_delete')
                        {!! Form::open(array('route' => array('approval.paymentrequests.destroy', $paymentrequest->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                            @endcan

                    </td>
                    @endif
                    --}}
                </tr>
            @endforeach

            <tr class="info">
                <td>合计</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    {{--{{ number_format($items->sum('bonus'), 2) }}--}}
                    {{ $totalbonus }}
                </td>
                {{--
                <td>
                @if (Auth::user()->email == "admin@admin.com")
                    {{ $purchaseorders->sum('amount') }}
                @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                @if (Agent::isDesktop())
                    <td></td>
                @endif
                --}}
            </tr>

            <tr class="success">
                <td colspan="6">注：此数据为参考数据，最后奖金以实发为准。</td>
                {{--
                <td></td>
                <td>
                @if (isset($totalamount))
                    {{ $totalamount }}
                @endif
                </td>
                <td></td>
                    <td></td>
                <td>

                </td>
                <td>

                </td>
                <td></td>
                <td></td>
                <td></td>
                @if (Agent::isDesktop())
                    <td></td>
                @endif
                --}}
            </tr>
        </tbody>

    </table>

    @if (count($input) > 0)
        {!! $items->setPath('/my/bonus')->appends($input)->links() !!}
    @else
        {!! $items->setPath('/my/bonus')->links() !!}
    @endif


    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif

@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    {{--<script type="text/javascript" src="/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>--}}
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

            {{--
            $('#userDataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ url('approval/paymentrequests/indexjson') }}",
                "columns": [
                    {"data": "created_at", "name": "created_at"},
                ]
            });
            --}}
        });
    </script>
@endsection