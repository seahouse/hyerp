@extends('navbarerp')

@section('head')
    <link href="{{ asset('css/jquery-editable-select.css') }}" rel="stylesheet" type="text/css" />
@endsection

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
            'attr' => '',
            'attrdisable' => 'disabled',
            'btnclass' => 'hidden',
        ])

    <div id="dynamicSelectWrapper">
    @foreach($biddinginformation->biddinginformationitems()->orderBy('sort')->get() as $biddinginformationitem)
        <div class="form-group">
            {!! Form::label($biddinginformationitem->key, $biddinginformationitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-4 col-sm-6'>
                <?php $biddinginformationdefinefield = $biddinginformationitem->biddinginformationdefinefield; ?>
                @if (isset($biddinginformationdefinefield))
                    @if ($biddinginformationdefinefield->type == 2)
                        <?php $arr = explode(',', $biddinginformationdefinefield->select_strings); ?>
                            {!! Form::select($biddinginformationitem->key, array_combine($arr, $arr), $biddinginformationitem->value, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
                    @else
                        {{--{!! Form::text($biddinginformationitem->key, $biddinginformationitem->value, ['class' => 'form-control']) !!}--}}
                            {!! Form::text($biddinginformationitem->key, $biddinginformationitem->value, ['class' => 'form-control dynamicSelect', 'data-value' => $biddinginformationitem->value, 'data-name' => $biddinginformationitem->key]) !!}
                    @endif
                @else
                    {!! Form::text($biddinginformationitem->key, $biddinginformationitem->value, ['class' => 'form-control']) !!}
                @endif
            </div>
            <div class='col-xs-4 col-sm-4'>
                @can('basic_biddinginformation_remark')
                    @if (strlen($biddinginformationitem->remark) > 0)
                        {!! Form::textarea($biddinginformationitem->key . '_remark', $biddinginformationitem->remark, ['class' => 'form-control', 'rows' => 3]) !!}
                    @else
                        {!! Form::text($biddinginformationitem->key . '_remark', $biddinginformationitem->remark, ['class' => 'form-control', 'placeholder' => '备注/批注']) !!}
                    @endif
                @endcan
            </div>
        </div>
    @endforeach
    </div>

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

    <div class="modal fade" id="selectOrderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">关联销售订单</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '项目编号、项目名称', 'id' => 'keyProject']) !!}

                        <span class="input-group-btn">
                   		    {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchProject']) !!}
                   	    </span>
                    </div>
                    {!! Form::hidden('name', null, ['id' => 'name']) !!}
                    <p>
                    <div class="list-group" id="listsalesorders">

                    </div>
                    </p>
                    <form id="formAccept">
                        {!! csrf_field() !!}
                        {!! Form::hidden('soheadid', 0, ['class' => 'form-control', 'id' => 'soheadid']) !!}
                        {!! Form::hidden('informationid', 0, ['class' => 'form-control', 'id' => 'informationid']) !!}
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="modal fade" id="selectBiddingprojectModal" tabindex="-1" role="dialog">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h4 class="modal-title">所属项目</h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<div class="input-group">--}}
                        {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '项目名称', 'id' => 'keyBiddingProject']) !!}--}}

                        {{--<span class="input-group-btn">--}}
                   		    {{--{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchBiddingProject']) !!}--}}
                   	    {{--</span>--}}
                    {{--</div>--}}
                    {{--{!! Form::hidden('name', null, ['id' => 'name']) !!}--}}
                    {{--<p>--}}
                    {{--<div class="list-group" id="listbiddingprojects">--}}

                    {{--</div>--}}
                    {{--</p>--}}
                    {{--<form id="formAccept">--}}
                        {{--{!! csrf_field() !!}--}}
                        {{--{!! Form::hidden('biddingprojectid', 0, ['class' => 'form-control', 'id' => 'biddingprojectid']) !!}--}}
                        {{--{!! Form::hidden('informationid', 0, ['class' => 'form-control', 'id' => 'informationid']) !!}--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    <script type="text/javascript" src="/js/jquery-editable-select.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {

       $('.dynamicSelect').each(function(index, select) {
            var name = $(select).attr('data-name');
            $(select).editableSelect({id: name});
        });  
        
        $('.dynamicSelect').each(function(index, select) {
            var val = $(select).attr('data-value');
            $(select).val(val);
        }); 
            
        $('#dynamicSelectWrapper').on('click', function (evt) {
            var target = evt.target || evt.srcElement;
            if ($(target).attr('data-name')) {
                var name = $(target).attr('data-name');
                var url = '/basic/biddinginformationitems/getvaluesbykey/' + name;
                if (window['URL_' + url]) {
                    return;
                }
                else {
                    $.get(url, {}, function (result) {
                        result && (window['URL_' + url] = true);
                        $.each(result, function (i, t) {
                            $(target).editableSelect('add', t);
                        });
                        $('#listWrapper_' + name).css('display', 'block');//fix plugin filter issue
                    });

                }
            }     
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
            $('#selectOrderModal').on('show.bs.modal', function (e) {
                $("#listsalesorders").empty();

                var text = $(e.relatedTarget);
                var modal = $(this);
                modal.find('#name').val(text.data('name'));
                modal.find('#informationid').val(text.data('informationid'));
                // alert(modal.find('#informationid').val());
            });

            $("#btnSearchProject").click(function() {
                if ($("#keyProject").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/sales/salesorders/getitemsbykey/') !!}" + "/" + $("#keyProject").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectProject_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.number + "</h4><p>" + field.descrip + "</p></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listsalesorders").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectProject_' + String(i);
                            $informationid=  $("#selectOrderModal").find('#informationid').val();
                            // alert($informationid);
                            addBtnClickEventProject(btnId, field.id, $informationid);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventProject(btnId, soheadid, informationid)
            {
                $("#" + btnId).bind("click", function() {
                    // $('#selectOrderModal').modal('toggle');
                    // $("#" + $("#selectOrderModal").find('#name').val()).val(field.descrip);
                    // $("#" + $("#selectOrderModal").find('#id').val()).val(soheadid);
                    $("#soheadid").val(soheadid);
                    $("#informationid").val(informationid);
                    // data=[];

// //					$("#supplier_bank").val(field.bank);
// //					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
// //					$("#vendbank_id").val(field.vendbank_id);
// //					$("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
//                     alert(soheadid +"," + informationid);

                    $.ajax({
                        type: "POST",
                        url: "{!! url('/basic/biddinginformations/updatesaleorderid/') !!}" ,
                        data: $("form#formAccept").serialize(),
                        // data: {id:soheadid,informationid:informationid},
                        dataType:"json",
                        success: function(result) {
                            if (result.errorcode >= 0)
                            {
                                $('#selectOrderModal').modal('toggle');
                                alert("关联成功。");
                                window.location.reload('true');
                                // redirect('development/fabricdischarges');
                            }
                            else
                                alert(result.errormsg );
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert('error');
                        }
                    });
                });
            }

            {{--$('#selectBiddingprojectModal').on('show.bs.modal', function (e) {--}}
                {{--$("#listbiddingprojects").empty();--}}

                {{--var text = $(e.relatedTarget);--}}
                {{--var modal = $(this);--}}
                {{--modal.find('#name').val(text.data('name'));--}}
                {{--modal.find('#informationid').val(text.data('informationid'));--}}
                {{--// alert(modal.find('#informationid').val());--}}
            {{--});--}}

            {{--$("#btnSearchBiddingProject").click(function() {--}}
                {{--if ($("#keyBiddingProject").val() == "") {--}}
                    {{--alert('请输入关键字');--}}
                    {{--return;--}}
                {{--}--}}
                {{--$.ajax({--}}
                    {{--type: "GET",--}}
                    {{--url: "{!! url('/basic/biddingprojects/getitemsbykey/') !!}" + "/" + $("#keyBiddingProject").val(),--}}
                    {{--success: function(result) {--}}
                        {{--var strhtml = '';--}}
                        {{--$.each(result.data, function(i, field) {--}}
                            {{--btnId = 'btnSelectBiddingProject_' + String(i);--}}
                            {{--strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"--}}
                        {{--});--}}
                        {{--if (strhtml == '')--}}
                            {{--strhtml = '无记录。';--}}
                        {{--$("#listbiddingprojects").empty().append(strhtml);--}}

                        {{--$.each(result.data, function(i, field) {--}}
                            {{--btnId = 'btnSelectBiddingProject_' + String(i);--}}
                            {{--$informationid=  $("#selectBiddingprojectModal").find('#informationid').val();--}}
                            {{--// alert($informationid);--}}
                            {{--addBtnClickEventBiddingProject(btnId, field.name,field.id, $informationid);--}}
                        {{--});--}}
                        {{--// addBtnClickEvent('btnSelectOrder_0');--}}
                    {{--},--}}
                    {{--error: function(xhr, ajaxOptions, thrownError) {--}}
                        {{--alert('error');--}}
                    {{--}--}}
                {{--});--}}
            {{--});--}}

            {{--function addBtnClickEventBiddingProject(btnId, projectname,biddingprojectid, informationid)--}}
            {{--{--}}
                {{--$("#" + btnId).bind("click", function() {--}}
                    {{--$('#selectBiddingprojectModal').modal('toggle');--}}
                    {{--$("#projectid").val(projectname);--}}
                    {{--$("#biddingprojectid").val(biddingprojectid);--}}
                    {{--$("#informationid").val(informationid);--}}

                    {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: "{!! url('/basic/biddinginformations/updatesaleorderid/') !!}" ,--}}
                    {{--data: $("form#formAccept").serialize(),--}}
                    {{--// data: {id:soheadid,informationid:informationid},--}}
                    {{--dataType:"json",--}}
                    {{--success: function(result) {--}}
                    {{--if (result.errorcode >= 0)--}}
                    {{--{--}}
                    {{--$('#selectOrderModal').modal('toggle');--}}
                    {{--alert("关联成功。");--}}
                    {{--window.location.reload('true');--}}
                    {{--// redirect('development/fabricdischarges');--}}
                    {{--}--}}
                    {{--else--}}
                    {{--alert(result.errormsg );--}}
                    {{--},--}}
                    {{--error: function(xhr, ajaxOptions, thrownError) {--}}
                    {{--alert('error');--}}
                    {{--}--}}
                    {{--});--}}
                {{--});--}}
            {{--}--}}
        });
    </script>
@endsection