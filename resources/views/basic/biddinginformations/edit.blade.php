@extends('navbarerp')

@section('main')
    @can('basic_biddinginformation_edit')
    <h1>编辑</h1>
    <hr/>

    {{--<table id="tableBiddinginformation" class="table table-striped table-hover table-full-width"  width="100%">--}}
        {{--<thead>--}}
        {{--<tr>--}}
            {{--<th>名称</th>--}}
            {{--<th>数据</th>--}}
            {{--<th>下图人</th>--}}
            {{--<th>工厂</th>--}}

            {{--<th>概述</th>--}}
        {{--</tr>--}}
        {{--</thead>--}}
    {{--</table>--}}

    {!! Form::model($biddinginformation, ['method' => 'PATCH', 'action' => ['Basic\BiddinginformationController@update', $biddinginformation->id], 'class' => 'form-horizontal']) !!}
    @include('basic.biddinginformations._form',
        [
            'submitButtonText' => '提交',
            'datepay' => null,
            'requestdeliverydate' => null,
            'customer_name' => null,
            'customer_id' => null,
            'amount' => null,
            'order_number' => null,
            'order_id' => null,
            'datego' => null,
            'dateback' => null,
            'mealamount' => null,
            'ticketamount' => null,
            'amountAirfares' => null,
            'amountTrain' => null,
            'amountTaxi' => null,
            'amountOtherTicket' => null,
            'stayamount' => null,
            'otheramount' => null,
            'attr' => 'readonly',
            'attrdisable' => 'disabled',
            'btnclass' => 'hidden',
        ])

    @foreach($biddinginformation->biddinginformationitems as $biddinginformationitem)
        <div class="form-group">
            {!! Form::label($biddinginformationitem->key, $biddinginformationitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text($biddinginformationitem->key, $biddinginformationitem->value, ['class' => 'form-control']) !!}
            </div>
        </div>
    @endforeach

    <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    </div>
    </div>
    {!! Form::close() !!}

    @include('errors.list')
    @else
        无权限
    @endcan
@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
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
                    '<th>应发奖金</th>' +
                    '</tr>'+
                    '</thead>'+
                    '</table>';
            }

//            var template = Handlebars.compile($("#details-template").html());
            var tableBiddinginformation = $('#tableBiddinginformation').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('basic/biddinginformationitems/jsondata') }}",
                    "data": function (d) {
                        d.biddinginformation_id = '{{ $biddinginformation->id }}';
                        d.project_id = $('input[name=project_id]').val();       // because use jquery-editable-select.js, select control changed to input control
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
                    {"data": "key", "name": "key"},
                    {"data": "value", "name": "value"},
//                    {"data": "applicant", "name": "applicant"},
//                    {"data": "productioncompany", "name": "productioncompany"},
//                    {"data": "overview", "name": "overview"},
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
                    {"data": "created_date", "name": "mcitempurchases.created_at", "searchable": false},
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
                    {"data": "created_date", "name": "pppaymentitems.created_at"},
                    {"data": "tonnage_paowan", "name": "tonnage_paowan"},
                    {"data": "tonnage_youqi", "name": "tonnage_youqi"},
                    {"data": "tonnage_rengong", "name": "tonnage_rengong"},
                    {"data": "tonnage_maohan", "name": "tonnage_maohan"},
                    {"data": "tonnage_waixieyouqi", "name": "tonnage_waixieyouqi"},
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