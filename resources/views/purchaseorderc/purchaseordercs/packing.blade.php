@extends('navbarerp')

@section('title', '采购订单打包')

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

    </div>
    

    <table id="tablePacking" class="table table-striped table-hover table-full-width">
        <thead>
            <tr>
                <th>面料序列号</th>
                <th>物料代码</th>
                <th>数量</th>
                <th>单位</th>
                <th>面料尺寸</th>
                <th>运输方式</th>
                <th>单价</th>
                <th>发货日期</th>
                <th>已发数量</th>
                <th>打包数量</th>
            </tr>
        </thead>

    </table>

    {!! Form::button('打印标签', ['class' => 'btn btn-primary']) !!}
    {!! Form::button('打包发送', ['class' => 'btn btn-primary']) !!}
@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {


//            $("#btnExport").click(function() {
//                $("form#formExport").find('#sohead_id').val($('input[name=sohead_id]').val());
//                $("form#formExport").submit();
//            });
//
//            $("#btnExport2").click(function() {
//                $("form#formExport2").find('#sohead_id').val($('input[name=sohead_id]').val());
//                $("form#formExport2").submit();
//            });
//
//            $("#btnExport3").click(function() {
//                $("form#formExport3").find('#project_id').val($('input[name=project_id]').val());
//                $("form#formExport3").submit();
//            });

//            function format ( d ) {
//                // `d` is the original data object for the row
//                return '<table class="table details-table" id="detail-' + d.id + '">'+
//                    '<thead>'+
//                    '<tr>' +
//                    '<th>收款日期</th>' +
//                    '<th>收款金额</th>' +
//                    '<th>奖金系数</th>' +
//                    '<th>应发奖金</th>' +
//                    '</tr>'+
//                    '</thead>'+
//                    '</table>';
//            }

            var tablePacking = $('#tablePacking').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('purchaseorderc/purchaseordercs/' . $id . '/detailjson') }}",
                    "data": function (d) {
                        {{--d.soheadc_id = {{ $id }};--}}
//                        d.project_id = $('input[name=project_id]').val();       // because use jquery-editable-select.js, select control changed to input control
//                        d.issuedrawingdatestart = $('input[name=issuedrawingdatestart]').val();
//                        d.issuedrawingdateend = $('input[name=issuedrawingdateend]').val();
                    }
                },
                "columns": [
//                    {
//                        "orderable":      false,
//                        "searchable":      false,
//                        "data":           null,
//                        "defaultContent": ''
//                    },
                    {"data": "fabric_sequence_no", "name": "fabric_sequence_no"},
                    {"data": "material_code", "name": "material_code"},
                    {"data": "quantity", "name": "quantity"},
                    {"data": "unit", "name": "unit"},
                    {"data": "fabric_width", "name": "fabric_width"},
                    {"data": "transportation_method_type_code", "name": "transportation_method_type_code"},
                    {"data": "unit_price", "name": "unit_price"},
                    {"data": "shipment_date", "name": "shipment_date"},
                    {"data": "packedcount", "name": "packedcount"},
                    {"data": "packingcount", "name": "packingcount"},
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
                        d.sohead_id = $('input[name=sohead_id]').val();
                        d.project_id = $('input[name=project_id]').val();
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
                        d.sohead_id = $('input[name=sohead_id]').val();
                        d.project_id = $('input[name=project_id]').val();
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
