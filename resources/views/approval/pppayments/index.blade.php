@extends('navbarerp')

@section('title', '生产加工结算单列表')

@section('main')
@can('approval_pppayment_view')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 生产加工结算单
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


        {!! Form::open(['url' => '/approval/issuedrawing/search', 'class' => 'pull-right form-inline', 'id' => 'frmCondition']) !!}
            <div class="form-group-sm">
                {!! Form::label('createdatelabel', '申请时间:', ['class' => 'control-label']) !!}
                {!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}
                {!! Form::label('approvaldatelabelto', '-', ['class' => 'control-label']) !!}
                {!! Form::date('createdateend', null, ['class' => 'form-control']) !!}

                {{--
                {!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}

                {!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
                {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}
                --}}
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '审批编号']) !!}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
                @if (Auth::user()->email == "admin@admin.com")
                    {!! Form::button('同步审批单状态到ERP', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_synchronize_status_to_erp']) !!}
                @endif
            </div>
        {!! Form::close() !!}


    </div>

    @if ($pppayments->count())

    <table id="userDataTable" class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请日期</th>
                <th>编号</th>
                <th>吨数</th>

                @if (Agent::isDesktop())
                <th>对应项目</th>
                @endif

                <th>申请人</th>
                <th>审批状态</th>
                @if (Agent::isDesktop())
                <th style="width: 150px">操作</th>
                @endif

            </tr>
        </thead>

        <tbody>
            @foreach($pppayments as $pppayment)
                <tr>
                    <td>
                        @if (Agent::isDesktop() && (Auth::user()->email == "wangai@huaxing-east.com" || Auth::user()->email == "shenhaixia@huaxing-east.com"))
                            <a href="{{ url('/approval/pppayment/' . $pppayment->id . '/printpage') }}" target="_blank">{{ $pppayment->created_at }}</a>
                        @else
                            <a href="{{ url('/approval/pppayment', $pppayment->id) }}" target="_blank">{{ $pppayment->created_at }}</a>
                        @endif
                    </td>
                    <td>
                        {{ $pppayment->business_id }}
                    </td>
                    <td>
                        {{ $pppayment->tonnage }}
                    </td>
                    @if (Agent::isDesktop())
                        <td title="@if (isset($pppayment->sohead_hxold->descrip)) {{ $pppayment->sohead_hxold->descrip }} @else @endif">
                            @if (isset($pppayment->sohead_hxold->projectjc)) {{ str_limit($pppayment->sohead_hxold->projectjc, 40) }} @else @endif
                        </td>
                    @endif
                    <td>
                        {{ isset($pppayment->applicant->name) ? $pppayment->applicant->name : '' }}
                    </td>
                    <td>
                        @if ($pppayment->status == 1)
                            <div class="text-primary">审批中</div>
                        @elseif ($pppayment->status == 0)
                            <div class="text-success">已通过</div>
                        @elseif ($pppayment->status == -1)
                            <div class="text-warning">已拒绝</div>
                        @elseif ($pppayment->status == -2)
                            <div class="text-danger">已撤回</div>
                        @else
                            <div class="text-danger">--</div>
                        @endif
                    </td>
                    @if (Agent::isDesktop())
                        <td>
                            @can('approval_issuedrawing_modifyweight')
                                <a href="{{ url('/approval/issuedrawing/' . $pppayment->id . '/modifyweight') }}" target="_blank" class="btn btn-success btn-sm pull-left
                        @if ($pppayment->status == 0)
                                @else
                                        disabled
                                @endif
                                        ">修改重量</a>
                            @endcan

                            @can('approval_issuedrawing_delete')
                                {{--
                        {!! Form::open(array('route' => array('approval.issuedrawing.destroy', $pppayment->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                        --}}
                            @endcan

                        </td>
                    @endif
                </tr>
            @endforeach

            <tr class="info">
                <td>合计</td>
                <td>{{ $pppayments->sum('tonnage') }}</td>
@if (Agent::isDesktop())
                <td></td>
@endif
                <td></td>
                <td></td>
                @if (Agent::isDesktop())
                    <td></td>
                @endif
            </tr>

@if (Auth::user()->email == "admin@admin.com")
            <tr class="success">
                <td>汇总</td>
                <td>
                @if (isset($totalamount))
                    {{ $totalamount }}
                @endif
                </td>
@if (Agent::isDesktop())
                <td></td>
@endif
                <td></td>
                <td></td>
                @if (Agent::isDesktop())
                    <td></td>
                @endif
            </tr>
@endif
        </tbody>

    </table>


    {!! $pppayments->setPath('/approval/issuedrawing')->appends($inputs)->links() !!}


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
                    url: "{{ url('approval/issuedrawings/export') }}",
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

            $("#btn_synchronize_status_to_erp").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('approval/pppayment/synchronize_status_to_erp') !!}",
                    data : $('#frmCondition').serialize(),
                    success: function(result) {
                        // alert(result);
                        // alert(result.errmsg);
                        if (result.errcode == 0)
                        {
                            alert(result.errmsg);
                        }
                        else
                            alert(JSON.stringify(result));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });

            {{--
            $('#userDataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ url('approval/issuedrawings/indexjson') }}",
                "columns": [
                    {"data": "created_at", "name": "created_at"},
                ]
            });
            --}}
        });
    </script>
@endsection