@extends('navbarerp')

@section('title', '工程采购审批单')

@section('main')
@can('approval_projectsitepurchase_view')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 工程采购
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
                {{--<button type="submit" class="btn btn-default btn-sm">导出</button>--}}
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
                {{--{!! Form::label('approvaldatelabel', '审批时间:', ['class' => 'control-label']); !!}--}}
                {{--{!! Form::date('approvaldatestart', null, ['class' => 'form-control']); !!}--}}
                {{--{!! Form::label('approvaldatelabelto', '-', ['class' => 'control-label']); !!}--}}
                {{--{!! Form::date('approvaldateend', null, ['class' => 'form-control']); !!}--}}
                {{----}}
                {{--{!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}--}}

                {{--{!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}--}}
                {{--{!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}--}}
                {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人']); !!}--}}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']); !!}
            </div>
        {!! Form::close() !!}
{{--
        <form class="pull-right" action="/approval/paymentrequests/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="支付对象、对应项目名称、申请人">
            </div>
            <div class="pull-right input-group-sm">
                {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}
            </div>
            <div class="pull-right input-group-sm">
                {!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
            </div>
            @if (Auth::user()->email == "admin@admin.com")
            <div class="pull-right input-group-sm">                
                
                {!! Form::date('approvaldatestart', \Carbon\Carbon::now(), ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
                
            </div>
            {!! Form::label('approvaldatelabel', '审批时间:', ['class' => 'control-label pull-right']); !!}
                
            @endif
        </form>
--}}
    </div> 


    @if ($projectsitepurchases->count())

    <table id="userDataTable" class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请日期</th>
                <th>采购类型</th>


                <th>申请人</th>
                <th>审批状态</th>
                @if (Agent::isDesktop())
                <th style="width: 150px">操作</th>
                @endif

            </tr>
        </thead>

        <tbody>
            @foreach($projectsitepurchases as $projectsitepurchase)
                <tr>
                    <td>
                        <a href="{{ url('/approval/projectsitepurchases', $projectsitepurchase->id) }}" target="_blank">{{ $projectsitepurchase->created_at }}</a>
                    </td>
                    <td>
                        {{ $projectsitepurchase->purchasetype }}
                    </td>
                    <td>
                        {{ $projectsitepurchase->applicant->name }}
                    </td>
                    <td>
                        @if ($projectsitepurchase->approversetting_id > 0)
                            <div class="text-primary">审批中</div>
                        @elseif ($projectsitepurchase->approversetting_id == 0)
                            <div class="text-success">已通过</div>
                        @elseif ($projectsitepurchase->approversetting_id == -3)
                            <div class="text-warning">撤回中</div>
                        @elseif ($projectsitepurchase->approversetting_id == -4)
                            <div class="text-danger">已撤回</div>
                        @else
                            <div class="text-danger">未通过</div>
                        @endif
                    </td>
                    <td>
                        @if ($projectsitepurchase->approversetting_id === 0)

                            @if (isset($projectsitepurchase->purchaseorder_hxold->payments))
                                @if ($projectsitepurchase->paymentrequestapprovals->max('created_at') < $projectsitepurchase->purchaseorder_hxold->payments->max('create_date'))
                                    <div class="text-success">已付款</div>
                                @endif
                            @endif
                        @endif
                    </td>
                    @if (Agent::isDesktop())
                    <td>
                            @can('approval_paymentrequest_delete')
                        {{--{!! Form::open(array('route' => array('approval.projectsitepurchases.destroy', $projectsitepurchase->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}
                        {{--{!! Form::close() !!}--}}
                            @endcan
                    </td>
                    @endif
                </tr>
            @endforeach

        </tbody>

    </table>


    {!! $projectsitepurchases->setPath('/approval/projectsitepurchases')->appends($inputs)->links() !!}


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
        });
    </script>
@endsection