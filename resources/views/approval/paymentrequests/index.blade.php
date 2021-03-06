
@extends('navbarerp')

@section('title', '供应商付款')

@section('main')
@can('approval_paymentrequest_view')
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

        @if (Auth::user()->email === "admin@admin.com")
        <form class="pull-right form-inline" action="/approval/paymentrequests/export" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">导出</button>
            </div>
        </form>
        @endif

        {{--
                <div class="pull-right">
                    <button class="btn btn-default btn-sm" id="btnExport">导出</button>
                </div>
        --}}

        {!! Form::open(['url' => '/approval/paymentrequests/search', 'class' => 'pull-right form-inline']) !!}
            <div class="form-group-sm">
                {!! Form::label('approvaldatelabel', '审批时间:', ['class' => 'control-label']) !!}
                {!! Form::date('approvaldatestart', null, ['class' => 'form-control']) !!}
                {!! Form::label('approvaldatelabelto', '-', ['class' => 'control-label']) !!}
                {!! Form::date('approvaldateend', null, ['class' => 'form-control']) !!}
                {!! Form::select('approver_id', $approvers_paymentrequest, null, ['class' => 'form-control', 'placeholder' => '--审批人--']) !!}
                
                {!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}

                {!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
                {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']) !!}
                {!! Form::select('company_id', $companyList, null, ['class' => 'form-control', 'placeholder' => '--采购公司--']) !!}
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人、说明']) !!}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
                <a class="btn btn-default btn-sm" id="btnPrint">打印</a>
            </div>
        {!! Form::close() !!}
    </div> 


    @if ($paymentrequests->count())

    <table id="userDataTable" class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>选择</th>
                <th>申请日期</th>
                <th>采购公司</th>
                <th>支付对象</th>

                <th>本次请款额</th>
                @if (Agent::isDesktop())
                <th>付款方式</th>
                <th>对应项目</th>
                <th>已开票金额</th>
                <th>合同金额</th>
                @endif

                <th>申请人</th>
                <th>审批状态</th>
                <th>付款状态</th>
                @if (Agent::isDesktop())
                <th style="width: 150px">操作</th>
                @endif

            </tr>
        </thead>

        <tbody>
            @foreach($paymentrequests as $paymentrequest)
                <tr>
                    <td>
                        {{ Form::checkbox('select', $paymentrequest->id) }}
                    </td>
                    <td>
                        @if (Agent::isDesktop() && (Auth::user()->email == "wangai@huaxing-east.com" || Auth::user()->email == "shenhaixia@huaxing-east.com"))
                            <a href="{{ url('/approval/paymentrequests/' . $paymentrequest->id . '/printpage') }}" target="_blank">{{ $paymentrequest->created_at }}</a>
                        @else
                            <a href="{{ url('/approval/paymentrequests', $paymentrequest->id) }}" target="_blank">{{ $paymentrequest->created_at }}</a>
                        @endif
                    </td>
                    <td>
                        @if (isset($paymentrequest->purchaseorder_hxold->companyname))  {{ $paymentrequest->purchaseorder_hxold->companyname }} @else @endif
                    </td>
                    <td>
                        @if (isset($paymentrequest->supplier_hxold->name))  {{ $paymentrequest->supplier_hxold->name }} @else @endif
                    </td>
                    <td>
                        {{ $paymentrequest->amount }}
                    </td>
                    @if (Agent::isDesktop())
                    <td>
                        {{ $paymentrequest->paymentmethod }}
                    </td>
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
                        @if ($paymentrequest->approversetting_id == 0)
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
                </tr>
            @endforeach

            <tr class="info">
                <td>合计</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $paymentrequests->sum('amount') }}</td>
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

            <tr class="success">
                <td>汇总</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    {{ $totalamount }}
                </td>
@if (Agent::isDesktop())
                <td></td>
                    <td></td>
                <td>

                </td>
                <td>

                </td>
@endif
                <td></td>
                <td></td>
                <td></td>
                @if (Agent::isDesktop())
                    <td></td>
                @endif
            </tr>
        </tbody>

    </table>


    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/paymentrequests')->appends($inputs)->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/paymentrequests')->links() !!}
    @endif



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

            $("#btnPrint").click(function() {
                var checkvalues = [];
                $('table input:checkbox').each(function (i) {
                    if ($(this).is(':checked') == true)
                        checkvalues.push($(this).val());
                });
                if (checkvalues.length > 0)
                {
                    {{--alert('{{ url('approval/paymentrequests/printmulti') . '?ids=' }}' + checkvalues.join(","));--}}
                    {{--window.location.href = '{{ url('approval/paymentrequests/printmulti') . '?ids=' }}' + checkvalues.join(",");--}}
                    window.open("{{ url('approval/paymentrequests/printmulti') . '?ids=' }}" + checkvalues.join(","), "_blank");
                }
                else
                    alert('没有选择任何对象。');
            });
        });
    </script>
@endsection