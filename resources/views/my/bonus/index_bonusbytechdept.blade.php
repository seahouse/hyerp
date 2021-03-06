@extends('navbarerp')

@section('title', '技术人员佣金')

<style>
    td.details-control {
        background: url('/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('/resources/details_close.png') no-repeat center center;
    }
</style>

@section('main')
    <div class="panel-heading">
        <div class="panel-title">技术人员佣金报表
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


        {!! Form::open(['url' => '/my/bonus/byorder', 'class' => 'pull-right form-inline', 'id' => 'frmSearch']) !!}
        <div class="form-group-sm">
            {{--{!! Form::select('salesmanager', $salesmanagerList_hxold, null, ['class' => 'form-control', 'placeholder' => '--销售经理--', 'id' => 'salesmanager']) !!}--}}
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


    <?php $totalbonus = 0.0; ?>
    <?php $totalaamountperiod = 0.0; ?>
    <table id="userDataTable" class="table table-striped table-hover table-full-width"  width="100%">
        <thead>
        <tr>
            <th>项目主设</th>
            <th>订单总金额</th>
            <th>区间收款</th>
            <th>应发佣金</th>
            <th>已发总佣金</th>
            <th>区间已发佣金</th>

            {{--<th>奖金系数</th>--}}
            {{--<th>应发奖金</th>--}}
            {{--<th>已发奖金</th>--}}
            {{--<th>支付奖金</th>--}}
        </tr>
        </thead>
    </table>

    {{--
    @if (count($input) > 0)
        {!! $items->setPath('/my/bonus')->appends($input)->links() !!}
    @else
        {!! $items->setPath('/my/bonus')->links() !!}
    @endif
    --}}

<p>
    2017年佣金统计区间：2017/1/24~2018/2/8<br>
    2016年佣金统计区间：2016/1/1~2017/1/23
</p>

@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    {{--<script type="text/javascript" src="/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>--}}
    <script type="text/javascript">
        jQuery(document).ready(function(e) {

            function format ( d ) {
                // `d` is the original data object for the row
                return '<table class="table details-table" id="detail-' + d.id + '">'+
                    '<thead>'+
                    '<tr>' +
                    '<th>收款日期</th>' +
                    '<th>收款金额</th>' +
                    '<th>奖金系数</th>' +
                    '<th>应发佣金</th>' +
                    '</tr>'+
                    '</thead>'+
                    '</table>';
            }

//            var template = Handlebars.compile($("#details-template").html());
            var table = $('#userDataTable').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('my/bonus/indexjsonbytechdept') }}",
                    "data": function (d) {
                        d.salesmanager = $('select[name=salesmanager]').val();
                        d.receivedatestart = $('input[name=receivedatestart]').val();
                        d.receivedateend = $('input[name=receivedateend]').val();
                    }
                },
                "columns": [
                    {"data": "name", "name": "name"},
                    {"data": "orderamounttotal", "name": "orderamounttotal"},
                    {"data": "receiptamountperiod", "name": "receiptamountperiod"},
                    {"data": "bonus", "name": "bonus"},
                    {"data": "bonuspaid", "name": "bonuspaid"},
                    {"data": "bonuspaidperiod", "name": "bonuspaidperiod"},
//                    {"data": "bonus", "name": "bonus"},
//                    {"data": "bonuspaid", "name": "bonuspaid"},
//                    {"data": "paybonus", "name": "paybonus"},
                ],
//                "fnCreatedRow": function(nRow, aData, iDataIndex) {
//                    $('td:eq(0)', nRow).html("<span class='row-details row-details-close' data_id='" + aData[1] + "'></span>&nbsp;" + aData[0]);
//                }
            });

            $('#userDataTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = 'detail-' + row.data().id;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
//                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    tr.addClass('shown');
                    tr.next().find('td').addClass('no-padding bg-gray');
                }
            } );

            function initTable(tableId, data) {
                $('#' + tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    {{--ajax: "{{ url('my/bonus/detailjsonbyorder') }}/" + data.id,--}}
                    "ajax": {
                        "url": "{{ url('my/bonus/detailjsonbyorder') }}/" + data.id,
                        "data": function (d) {
                            d.receivedatestart = $('input[name=receivedatestart]').val();
                            d.receivedateend = $('input[name=receivedateend]').val();
                        }
                    },
                    columns: [
                        { data: 'receiptdate', name: 'receiptdate' },
                        { data: 'amount', name: 'amount' },
                        { data: 'bonusfactor', name: 'bonusfactor' },
                        { data: 'bonus', name: 'bonus' },
                    ]
                })
            };

            $('#frmSearch').on('submit', function (e) {
                table.draw();
                e.preventDefault();
            })
        });
    </script>
@endsection