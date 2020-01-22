@extends('navbarerp')

@section('title', '工资条')

@section('main')
    <div class="panel-heading">
        <a href="annualbonussheet/create" class="btn btn-sm btn-success">新建</a>
        <a href="annualbonussheet/import" class="btn btn-sm btn-success">导入</a>
    </div>

    <div class="panel-body">
        {{--{!! Form::open(['url' => '/shipment/salarysheet/export', 'class' => 'pull-right']) !!}--}}
            {{--{!! Form::submit('Export', ['class' => 'btn btn-default btn-sm']) !!}--}}
        {{--{!! Form::close() !!}--}}

        {!! Form::open(['url' => '/system/annualbonussheet/search', 'class' => 'pull-right form-inline', 'id' => 'frmSearch']) !!}
        <div class="form-group-sm">
            {!! Form::label('salary_datestartlabel', '奖金日期:', ['class' => 'control-label']) !!}
            {!! Form::date('salary_datestart', null, ['class' => 'form-control']) !!}
            {!! Form::label('salary_datelabelto', '-', ['class' => 'control-label']) !!}
            {!! Form::date('salary_dateend', null, ['class' => 'form-control']) !!}

            {{--{!! Form::label('amount_for_customer', 'Amount for Customer:', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::select('amount_for_customer_opt', ['>=' => '>=', '<=' => '<=', '=' => '='], null, ['class' => 'form-control']) !!}--}}
            {{--{!! Form::text('amount_for_customer', null, ['class' => 'form-control', 'placeholder' => 'Amount for Customer']) !!}--}}

            {{--{!! Form::select('invoice_number_type', ['JPTEEA' => 'JPTEEA', 'JPTEEB' => 'JPTEEB'], null, ['class' => 'form-control', 'placeholder' => '--Invoice No. Type--']) !!}--}}

            {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => 'Invoice No.,Contact No.,Customer']) !!}--}}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            {!! Form::button('发送奖金条', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSendAnnualbonussheet']) !!}
        </div>
        {!! Form::close() !!}

        @if ($annualbonussheets->count())
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>工资日期</th>
                    <th>姓名</th>
                    <th>部门</th>
                    <th>实发奖金</th>
                    <th>钉钉绑定状态</th>
                    {{--<th>编织类型</th>--}}
                    {{--<th>目的地</th>--}}
                    {{--<th>供应商名称</th>--}}
                    <th>导入时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($annualbonussheets as $annualbonussheet)
                    <tr>
                        <td>
                            {{ $annualbonussheet->salary_date }}
                        </td>
                        <td>
                            {{ $annualbonussheet->username }}
                        </td>
                        <td>
                            {{ $annualbonussheet->department }}
                        </td>
                        <td>
                            {{ $annualbonussheet->actual_amount }}
                        </td>
                        <td>
                            @if (isset($annualbonussheet->user->dtuserid)) 是 @else 否 @endif
                        </td>
                        {{--<td>--}}
                        {{--{{ $purchaseorder->weave_type }}--}}
                        {{--</td>--}}
                        {{--<td>--}}
                        {{--{{ $purchaseorder->destination_country }}--}}
                        {{--</td>--}}
                        {{--<td>--}}
                        {{--{{ $purchaseorder->supplier_name }}--}}
                        {{--</td>--}}
                        <td>
                            {{ $annualbonussheet->created_at }}
                        </td>
                        <td>
                            <a href="{{ URL::to('/system/annualbonussheet/'.$annualbonussheet->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                            {{--<a href="{{ URL::to('/shipment/shipments/'.$salarysheet->id.'/export') }}" class="btn btn-success btn-sm pull-left">导出</a>--}}
                            {!! Form::open(array('route' => array('system.annualbonussheet.destroy', $annualbonussheet->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            {{--{!! $salarysheets->render() !!}--}}
            {!! $annualbonussheets->setPath('/system/annualbonussheet')->appends($inputs)->links() !!}
        @else
            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{'无记录(No Record)', [], 'layouts'}}
            </div>
        @endif
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnSendAnnualbonussheet").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('system/annualbonussheet/sendannualbonussheet') }}",
                    data: $("form#frmSearch").serialize(),
//                    dataType: "json",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert('error');
                    },
                    success:function(result){
//                        location.href = result;
                        alert(result);
                    },
                });
            });

            $("#btnExportPVH").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('shipment/shipments/exportpvh') }}",
                    data: $("form#frmSearch").serialize(),
//                    dataType: "json",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert('error');
                    },
                    success:function(result){
//                        alert(result);
                        location.href = result;
//                        alert("导出成功.");
                    },
                });
            });
        });
    </script>
@endsection
