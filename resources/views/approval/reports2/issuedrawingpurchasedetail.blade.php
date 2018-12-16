@extends('navbarerp')

@section('title', '下图申购结算报表')

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
    @if (Auth::user()->isSuperAdmin())
    <div class="panel-heading">
        {{--
        <div class="panel-title">我的 -- 奖金
                        <div class="pull-right">
                            <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                            <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
                        </div>
        </div>
        --}}
    </div>

    <div class="panel-body">
        {{--
                <a href="{{ URL::to('approval/items/create') }}" class="btn btn-sm btn-success">新建</a>
        --}}
        {!! Form::open(['url' => '/approval/report2/issuedrawingpurchasedetailexport3', 'class' => 'pull-right form-inline', 'id' => 'formExport3']) !!}
        <div class="form-group-sm">
            {!! Form::hidden('sohead_id', null) !!}
            {!! Form::button('按项目导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport3']) !!}
        </div>
        {!! Form::close() !!}

        {!! Form::open(['url' => '/approval/report2/issuedrawingpurchasedetailexport2', 'class' => 'pull-right form-inline', 'id' => 'formExport2']) !!}
        <div class="form-group-sm">
            {!! Form::hidden('sohead_id', null) !!}
            {!! Form::button('按工厂导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport2']) !!}
        </div>
        {!! Form::close() !!}

        <form class="pull-right form-inline" action="/approval/report2/issuedrawingpurchasedetailexport" method="post" id="formExport">
            {!! csrf_field() !!}
            <input type="hidden" value="aaa" name="sohead_id" id="sohead_id">
            <div class="pull-right">
                <button type="button" class="btn btn-default btn-sm" id="btnExport">按订单导出</button>
            </div>
        </form>

        {!! Form::open(['url' => '/approval/reports2/issuedrawingpurchasedetail', 'class' => 'pull-right form-inline', 'id' => 'frmSearch']) !!}
        <div class="form-group-sm">
            {!! Form::select('sohead_id', $poheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '--订单--']) !!}
            {!! Form::label('issuedrawingdatelabel', '下图时间:', ['class' => 'control-label']) !!}
            {!! Form::date('issuedrawingdatestart', null, ['class' => 'form-control']) !!}
            {!! Form::label('issuedrawingdatelabelto', '-', ['class' => 'control-label']) !!}
            {!! Form::date('issuedrawingdateend', null, ['class' => 'form-control']) !!}
            {{--
            {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人']); !!}
            --}}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>


    <?php $totalbonus = 0.0; ?>
    <?php $totalaamountperiod = 0.0; ?>
    <table id="tableIssuedrawing" class="table table-striped table-hover table-full-width"  width="100%">
        <thead>
        <tr>
            <th>下图日期</th>
            <th>吨位</th>
            <th>下图人</th>
            <th>工厂</th>

            <th>概述</th>
        </tr>
        </thead>
    </table>

    <table id="tableMcitempurchase" class="table table-striped table-hover table-full-width"  width="100%">
        <thead>
        <tr>
            <th>申购日期</th>
            <th>申购工厂</th>
            <th>申购吨位</th>
            <th>用途</th>

        </tr>
        </thead>
    </table>

    <table id="tablePppayment" class="table table-striped table-hover table-full-width"  width="100%">
        <thead>
        <tr>
            <th>结算日期</th>
            <th>抛丸</th>
            <th>油漆</th>
            <th>人工</th>
            <th>铆焊</th>
            <th>工厂</th>
            <th>制作概述</th>
            <th>支付日期</th>
            <th>申请人</th>
            <th>吨位</th>
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
    @else
        无权限。
    @endif

@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    {{--<script type="text/javascript" src="/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>--}}
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnExport").click(function() {
                $("form#formExport").find('#sohead_id').val($('select[name=sohead_id]').val());
                $("form#formExport").submit();
                {{--return;--}}
                {{--alert($("form#formExport").find('#sohead_id').val());--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: "{{ url('approval/report2/issuedrawingpurchasedetailexport') }}",--}}
                     {{--data: $("form#formExport").serialize(),--}}
                    {{--error:function(xhr, ajaxOptions, thrownError){--}}
                        {{--alert('error');--}}
                    {{--},--}}
                    {{--success:function(result){--}}
                        {{--alert("导出成功:" + result);--}}
                    {{--},--}}
                {{--});--}}
            });

            $("#btnExport2").click(function() {
                $("form#formExport2").find('#sohead_id').val($('select[name=sohead_id]').val());
                $("form#formExport2").submit();
            });

            $("#btnExport3").click(function() {
                $("form#formExport3").find('#sohead_id').val($('select[name=sohead_id]').val());
                $("form#formExport3").submit();
            });

            function format ( d ) {
                // `d` is the original data object for the row
                return '<table class="table details-table" id="detail-' + d.id + '">'+
                    '<thead>'+
                    '<tr>' +
                    '<th>收款日期</th>' +
                    '<th>收款金额</th>' +
                    '<th>奖金系数</th>' +
                    '<th>应发奖金</th>' +
                    '</tr>'+
                    '</thead>'+
                    '</table>';
            }

//            var template = Handlebars.compile($("#details-template").html());
            var tableIssuedrawing = $('#tableIssuedrawing').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('approval/report2/issuedrawingjson') }}",
                    "data": function (d) {
                        d.sohead_id = $('select[name=sohead_id]').val();
                        d.issuedrawingdatestart = $('input[name=issuedrawingdatestart]').val();
                        d.issuedrawingdateend = $('input[name=issuedrawingdateend]').val();
                    }
                },
                "columns": [
//                    {
//                        "orderable":      false,
//                        "searchable":      false,
//                        "data":           null,
//                        "defaultContent": ''
//                    },
                    {"data": "created_at", "name": "created_at"},
                    {"data": "tonnage", "name": "tonnage"},
                    {"data": "applicant", "name": "applicant"},
                    {"data": "productioncompany", "name": "productioncompany"},
                    {"data": "overview", "name": "overview"},
//                    {"data": "bonusfactor", "name": "bonusfactor"},
//                    {"data": "bonus", "name": "bonus"},
//                    {"data": "bonuspaid", "name": "bonuspaid"},
//                    {"data": "paybonus", "name": "paybonus"},
                ],
//                "fnCreatedRow": function(nRow, aData, iDataIndex) {
//                    $('td:eq(0)', nRow).html("<span class='row-details row-details-close' data_id='" + aData[1] + "'></span>&nbsp;" + aData[0]);
//                }
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    });
                }
            });

            $('#tableIssuedrawing tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tableIssuedrawing.row(tr);
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

            var tableMcitempurchase = $('#tableMcitempurchase').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('approval/report2/mcitempurchasejson') }}",
                    "data": function (d) {
                        d.sohead_id = $('select[name=sohead_id]').val();
                        d.receivedatestart = $('input[name=receivedatestart]').val();
                        d.receivedateend = $('input[name=receivedateend]').val();
                    }
                },
                "columns": [
                    {"data": "created_date", "name": "created_date", "searchable": false},
                    {"data": "manufacturingcenter", "name": "manufacturingcenter"},
                    {"data": "totalweight", "name": "totalweight", "searchable": false},
                    {"data": "detailuse", "name": "detailuse"},
//                    {"data": "overview", "name": "overview"},
//                    {"data": "bonusfactor", "name": "bonusfactor"},
//                    {"data": "bonus", "name": "bonus"},
//                    {"data": "bonuspaid", "name": "bonuspaid"},
//                    {"data": "paybonus", "name": "paybonus"},
                ],
//                "fnCreatedRow": function(nRow, aData, iDataIndex) {
//                    $('td:eq(0)', nRow).html("<span class='row-details row-details-close' data_id='" + aData[1] + "'></span>&nbsp;" + aData[0]);
//                }
            });

            var tablePppayment = $('#tablePppayment').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('approval/report2/pppaymentjson') }}",
                    "data": function (d) {
                        d.sohead_id = $('select[name=sohead_id]').val();
                        d.receivedatestart = $('input[name=receivedatestart]').val();
                        d.receivedateend = $('input[name=receivedateend]').val();
                    }
                },
                "columns": [
                    {"data": "created_date", "name": "created_date"},
                    {"data": "tonnage_paowan", "name": "tonnage_paowan"},
                    {"data": "tonnage_youqi", "name": "tonnage_youqi"},
                    {"data": "tonnage_rengong", "name": "tonnage_rengong"},
                    {"data": "tonnage_maohan", "name": "tonnage_maohan"},
                    {"data": "productioncompany", "name": "productioncompany"},
                    {"data": "productionoverview", "name": "productionoverview"},
                    {"data": "paymentdate", "name": "paymentdate"},
                    {"data": "applicant", "name": "applicant"},
                    {"data": "tonnage", "name": "tonnage"},
                ],
//                "fnCreatedRow": function(nRow, aData, iDataIndex) {
//                    $('td:eq(0)', nRow).html("<span class='row-details row-details-close' data_id='" + aData[1] + "'></span>&nbsp;" + aData[0]);
//                }
            });


            $('#frmSearch').on('submit', function (e) {
                tableIssuedrawing.draw();
                tableMcitempurchase.draw();
                tablePppayment.draw();
                e.preventDefault();
            })
        });
    </script>
@endsection