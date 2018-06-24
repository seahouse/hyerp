@extends('navbarerp')

@section('title', '下发图纸审批单列表')

@section('main')
@can('approval_issuedrawing_view')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 下发图纸
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

        {{--
        {!! Form::open(['url' => '/approval/issuedrawing/search', 'class' => 'pull-right form-inline']) !!}
            <div class="form-group-sm">
                {!! Form::label('approvaldatelabel', '审批时间:', ['class' => 'control-label']); !!}
                {!! Form::date('approvaldatestart', null, ['class' => 'form-control']); !!}
                {!! Form::label('approvaldatelabelto', '-', ['class' => 'control-label']); !!}
                {!! Form::date('approvaldateend', null, ['class' => 'form-control']); !!}
                
                {!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}

                {!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
                {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人']); !!}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']); !!}
            </div>
        {!! Form::close() !!}
        --}}

    </div>

    @if ($issuedrawings->count())

    <table id="userDataTable" class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请日期</th>
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
            @foreach($issuedrawings as $issuedrawing)
                <tr>
                    <td>
                        @if (Agent::isDesktop() && (Auth::user()->email == "wangai@huaxing-east.com" || Auth::user()->email == "shenhaixia@huaxing-east.com"))
                            <a href="{{ url('/approval/issuedrawing/' . $issuedrawing->id . '/printpage') }}" target="_blank">{{ $issuedrawing->created_at }}</a>
                        @else
                            <a href="{{ url('/approval/issuedrawing', $issuedrawing->id) }}" target="_blank">{{ $issuedrawing->created_at }}</a>
                        @endif
                    </td>
                    <td>
                        {{ $issuedrawing->tonnage }}
                    </td>
                    @if (Agent::isDesktop())
                    <td title="@if (isset($issuedrawing->sohead_hxold->descrip)) {{ $issuedrawing->sohead_hxold->descrip }} @else @endif">
                        @if (isset($issuedrawing->sohead_hxold->projectjc)) {{ str_limit($issuedrawing->sohead_hxold->projectjc, 40) }} @else @endif
                    </td>
                    @endif
                    <td>
                        {{ $issuedrawing->applicant->name }}
                    </td>
                    <td>
                        @if ($issuedrawing->status == 1)
                            <div class="text-primary">审批中</div>
                        @elseif ($issuedrawing->status == 0)
                            <div class="text-success">已通过</div>
                        @elseif ($issuedrawing->status == -1)
                            <div class="text-warning">已拒绝</div>
                        @elseif ($issuedrawing->status == -2)
                            <div class="text-danger">已撤回</div>
                        @else
                            <div class="text-danger">--</div>
                        @endif
                    </td>
                    @if (Agent::isDesktop())
                    <td>
                    @can('approval_issuedrawing_modifyweight')
                        <a href="{{ url('/approval/issuedrawing/' . $issuedrawing->id . '/modifyweight') }}" target="_blank" class="btn btn-success btn-sm pull-left
                        @if ($issuedrawing->status == 0)
                            @else
                                disabled
                        @endif
                        ">修改重量</a>
                    @endcan

                            @can('approval_issuedrawing_delete')
                                {{--
                        {!! Form::open(array('route' => array('approval.issuedrawing.destroy', $issuedrawing->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
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
                <td>{{ $issuedrawings->sum('tonnage') }}</td>
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


    @if (isset($key))
        {!! $issuedrawings->setPath('/approval/issuedrawing')->appends([
            'key' => $key, 
            'approvalstatus' => $inputs['approvalstatus'], 
            'paymentstatus' => $inputs['paymentstatus'],
            'approvaldatestart' => $inputs['approvaldatestart'],
            'approvaldateend' => $inputs['approvaldateend'],
            'paymentmethod' => $inputs['paymentmethod']
        ])->links() !!}
    @else
        {!! $issuedrawings->setPath('/approval/issuedrawing')->links() !!}
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