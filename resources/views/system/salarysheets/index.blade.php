@extends('navbarerp')

@section('title', '工资条')

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} ">
@endsection

@section('main')
<div class="panel-heading">
    {{--<a href="salarysheet/create" class="btn btn-sm btn-success">新建</a>--}}
    <a href="salarysheet/import" class="btn btn-sm btn-success">导入</a>
</div>

<div class="panel-body">
    {{--{!! Form::open(['url' => '/shipment/salarysheet/export', 'class' => 'pull-right']) !!}--}}
    {{--{!! Form::submit('Export', ['class' => 'btn btn-default btn-sm']) !!}--}}
    {{--{!! Form::close() !!}--}}

    {!! Form::open(['url' => '/system/salarysheet/search', 'class' => 'pull-right form-inline', 'id' => 'frmSearch']) !!}
    <div class="form-group-sm">
        {!! Form::label('salary_datestartlabel', '工资月份:', ['class' => 'control-label']) !!}
        {!! Form::text('salary_datestart', null, ['class' => 'form-control']) !!}
        {!! Form::label('salary_datelabelto', '-', ['class' => 'control-label']) !!}
        {!! Form::text('salary_dateend', null, ['class' => 'form-control']) !!}
        {!! Form::label('salary_batch', '导入批次', ['class' => 'control-label']) !!}
        {!! Form::text('salary_batch', null, ['class' => 'form-control']) !!}

        {{--{!! Form::label('amount_for_customer', 'Amount for Customer:', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::select('amount_for_customer_opt', ['>=' => '>=', '<=' => '<=', '=' => '='], null, ['class' => 'form-control']) !!}--}}
        {{--{!! Form::text('amount_for_customer', null, ['class' => 'form-control', 'placeholder' => 'Amount for Customer']) !!}--}}

        {{--{!! Form::select('invoice_number_type', ['JPTEEA' => 'JPTEEA', 'JPTEEB' => 'JPTEEB'], null, ['class' => 'form-control', 'placeholder' => '--Invoice No. Type--']) !!}--}}

        {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => 'Invoice No.,Contact No.,Customer']) !!}--}}
        {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        {{-- {!! Form::button('发送工资条', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSendSalarysheet']) !!}--}}
    </div>
    {!! Form::close() !!}

    @if ($salarysheets->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>工资月份</th>
                <th>姓名</th>
                <th>部门</th>
                <th>实发工资</th>
                <th>钉钉绑定状态</th>
                <th>反馈</th>
                {{--<th>目的地</th>--}}
                {{--<th>供应商名称</th>--}}
                <th>导入时间</th>
                <th>导入批次</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salarysheets as $salarysheet)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($salarysheet->salary_date)->format('Y-m') }}
                </td>
                <td>
                    {{ $salarysheet->username }}
                </td>
                <td>
                    {{ $salarysheet->department }}
                </td>
                <td>
                    {{ $salarysheet->actualsalary_amount }}
                </td>
                <td>
                    @if (isset($salarysheet->user->dtuserid)) 是 @else 否 @endif
                </td>
                <td>
                    @if (isset($salarysheet->salarysheetreply))
                    @if ($salarysheet->salarysheetreply->status == 0)
                    确认：{{ $salarysheet->salarysheetreply->message }}
                    @elseif($salarysheet->salarysheetreply->status == -1)
                    异议：{{ $salarysheet->salarysheetreply->message }}
                    @endif
                    @else
                    -
                    @endif
                </td>
                {{--<td>--}}
                {{--{{ $purchaseorder->destination_country }}--}}
                {{--</td>--}}
                {{--<td>--}}
                {{--{{ $purchaseorder->supplier_name }}--}}
                {{--</td>--}}
                <td>
                    {{ $salarysheet->created_at }}
                </td>
                <td>{{ $salarysheet->batch }}</td>
                <td>
                    {{--<a href="{{ URL::to('/system/salarysheet/'.$salarysheet->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>--}}
                    {{--<a href="{{ URL::to('/shipment/shipments/'.$salarysheet->id.'/export') }}" class="btn btn-success btn-sm pull-left">导出</a>--}}
                    {!! Form::open(array('route' => array('system.salarysheet.destroy', $salarysheet->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                    {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
    {{--{!! $salarysheets->render() !!}--}}
    {!! $salarysheets->setPath('/system/salarysheet')->appends($inputs)->links() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录(No Record)', [], 'layouts'}}
    </div>
    @endif
</div>

<div class="panel panel-footer">
    {!! Form::open(['url' => '/system/salarysheet/sendsalarysheet', 'class' => 'pull-right form-inline', 'id' => 'frmSendSalarysheet']) !!}
    <div class="form-group-sm">
        {!! Form::label('salary_date', '发送工资月份:', ['class' => 'control-label']) !!}
        {!! Form::text('salary_date', date('Y-m'), ['class' => 'form-control']) !!}
        {!! Form::label('send_batch', '批次:', ['class' => 'control-label']) !!}
        {!! Form::text('send_batch', null, ['class' => 'form-control']) !!}

        {!! Form::submit('发送工资条', ['class' => 'btn btn-primary', 'id' => 'btnSendSalarysheet']) !!}
        {{-- {!! Form::button('发送工资条', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSendSalarysheet']) !!}--}}
    </div>
    {!! Form::close() !!}
</div>

<div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">发送工资条确认</h4>
            </div>
            <div class="modal-body">
                <p>
                <div id="dataDefine">

                </div>
                </p>
                <form id="formAccept">

                </form>
            </div>
            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('继续发送', ['class' => 'btn btn-sm', 'id' => 'btnSubmitContinue']) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(e) {
        $("#btnSendSalarysheet").click(function() {
            $('#submitModal').modal('toggle');
            var batch = $("input[name='send_batch']").val();
            var msg = `确定发送[${$("input[name='salary_date']").val()}]的${batch === ""? "所有批次" : "批次" + batch}的工资条吗？`;
            $("#dataDefine").empty().append(msg);
            return false;
        });

        $("#btnSubmitContinue").click(function() {
            $("form#frmSendSalarysheet").submit();
        });

        $("input[name='salary_datestart']").datetimepicker({
            format: 'yyyy-mm',
            autoclose: true,
            startView: 3,
            minView: 3,
            language: "zh-CN",
            // format: 'yyyy-mm-dd hh:ii'
        });

        $("input[name='salary_dateend']").datetimepicker({
            format: 'yyyy-mm',
            autoclose: true,
            startView: 3,
            minView: 3,
            language: "zh-CN",
            //                format: 'yyyy-mm-dd hh:ii'
        });

        $("input[name='salary_date']").datetimepicker({
            format: 'yyyy-mm',
            autoclose: true,
            startView: 3,
            minView: 3,
            language: "zh-CN",
            //                format: 'yyyy-mm-dd hh:ii'
        });
    });
</script>
@endsection