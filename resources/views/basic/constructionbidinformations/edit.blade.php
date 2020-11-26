@extends('navbarerp')

@section('head')
<link href="{{ asset('css/jquery-editable-select.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('main')
@can('basic_constructionbidinformation_edit')
<div class="panel-heading">
    @can('basic_constructionbidinformation_edittable')
    @if (Auth::user()->email == 'admin@admin.com')
    <a href="{{ url('basic/constructionbidinformations/' . $constructionbidinformation->id . '/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>
    @endif
    @endcan
</div>

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

{!! Form::model($constructionbidinformation, ['method' => 'PATCH', 'action' => ['Basic\ConstructionbidinformationController@update', $constructionbidinformation->id], 'class' => 'form-horizontal']) !!}
@include('basic.constructionbidinformations._form',
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

<table id="tableItems" class="table table-striped table-hover table-full-width">
    <thead>
        <tr>
            <th>项目类型</th>
            <th>名称</th>
            <th>采购方</th>
            <th>规格及技术要求</th>
            <th>单条线</th>
            <th>倍数</th>
            {{--<th>三条线</th>--}}
            {{--<th>四条线</th>--}}
            <th>单位</th>
            <!-- <th>备注</th> -->
            <th>材料费</th>
            <th>安装费</th>
        </tr>
    </thead>
    <tbody>
        @foreach($constructionbidinformation->constructionbidinformationitems as $constructionbidinformationitem)
        <tr data-constructionbidinformationitem_id="{{ $constructionbidinformationitem->id }}">
            <td>
                {{ $constructionbidinformationitem->projecttype }}
            </td>
            <td>
                {{ $constructionbidinformationitem->key }}
            </td>
            <div id="div{{ $constructionbidinformationitem->id }}" name="constructionbidinformationitem_container" data-constructionbidinformationitem_id="{{ $constructionbidinformationitem->id }}">
                <td>
                    {!! Form::select('purchaser', array(''=>'--请选择--', '华星东方' => '华星东方', '投标人' => '投标人', '业主方' => '业主方'), $constructionbidinformationitem->purchaser,
                    ['class' => 'form-control', 'data-price' => $constructionbidinformationitem->constructionbidinformationfield(), 'required']) !!}
                </td>
                <td>
                    {!! Form::text('specification_technicalrequirements', $constructionbidinformationitem->specification_technicalrequirements, ['class' => 'form-control']) !!}
                </td>
                <td>
                    <!-- {!! Form::text('value', $constructionbidinformationitem->value, ['class' => 'form-control']) !!} -->
                    <input type="number" name="value" value="{{ $constructionbidinformationitem->value }}" class="form-control" min="0" step="0.01">
                </td>
                <td>
                    <!-- {!! Form::text('multiple', $constructionbidinformationitem->multiple, ['class' => 'form-control']) !!} -->
                    <input type="number" name="multiple" value="{{ $constructionbidinformationitem->multiple }}" class="form-control" min="0" step="0.01">
                </td>
                {{--<td>--}}
                {{--{!! Form::text('value_line3', $constructionbidinformationitem->value_line3, ['class' => 'form-control']) !!}--}}
                {{--</td>--}}
                {{--<td>--}}
                {{--{!! Form::text('value_line4', $constructionbidinformationitem->value_line4, ['class' => 'form-control']) !!}--}}
                {{--</td>--}}
                <td>
                    <input type="text" name="unit" readonly value="{{ $constructionbidinformationitem->unit }}" class="form-control">
                    {{-- {!! Form::select('unit', $unitstrList, $constructionbidinformationitem->unit, ['class' => 'form-control']) !!}--}}
                </td>
                <!-- <td>
                    {!! Form::text('remark', $constructionbidinformationitem->remark, ['class' => 'form-control']) !!}
                </td> -->
                <td>
                    <input type="number" name="material_fee" @if($constructionbidinformationitem->purchaser != "投标人") readonly @endif value="{{ $constructionbidinformationitem->material_fee }}" class="form-control" min="0" step="0.01">
                </td>
                <td>
                    <input type="text" name="install_fee" readonly value="{{ $constructionbidinformationitem->install_fee }}" class="form-control">
                </td>
            </div>
        </tr>
        @endforeach
        {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}

    </tbody>
</table>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('保存', ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}
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

@endsection

@section('script')
<script type="text/javascript" src="/DataTables/datatables.js"></script>
<script type="text/javascript" src="/js/jquery-editable-select.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(e) {

        $('.dynamicSelect').each(function(index, select) {
            var name = $(select).attr('data-name');
            $(select).editableSelect({
                id: name
            });
        });

        $('.dynamicSelect').each(function(index, select) {
            var val = $(select).attr('data-value');
            $(select).val(val);
        });

        $('#dynamicSelectWrapper').on('click', function(evt) {
            var target = evt.target || evt.srcElement;
            if ($(target).attr('data-name')) {
                var name = $(target).attr('data-name');
                var url = '/basic/constructionbidinformationitems/getvaluesbykey/' + name;
                if (window['URL_' + url]) {
                    return;
                } else {
                    $.get(url, {}, function(result) {
                        result && (window['URL_' + url] = true);
                        $.each(result, function(i, t) {
                            $(target).editableSelect('add', t);
                        });
                        $('#listWrapper_' + name).css('display', 'block'); //fix plugin filter issue
                    });

                }
            }
        });

        $("#btnSubmit").click(function() {
            var itemArray = new Array();

            $("#tableItems tbody tr").each(function() {
                var constructionbidinformationitem_id = this.dataset.constructionbidinformationitem_id;
                console.info(constructionbidinformationitem_id);
                var trrow = $(this);

                var itemObject = new Object();
                itemObject.constructionbidinformationitem_id = constructionbidinformationitem_id;
                itemObject.purchaser = trrow.find("select[name='purchaser']").val();
                itemObject.specification_technicalrequirements = trrow.find("input[name='specification_technicalrequirements']").val();
                itemObject.value = trrow.find("input[name='value']").val();
                itemObject.multiple = trrow.find("input[name='multiple']").val();
                //                    itemObject.value_line3 = trrow.find("input[name='value_line3']").val();
                //                    itemObject.value_line4 = trrow.find("input[name='value_line4']").val();
                itemObject.unit = trrow.find("input[name='unit']").val();
                // itemObject.remark = trrow.find("input[name='remark']").val();
                itemObject.material_fee = trrow.find("input[name='material_fee']").val();
                itemObject.install_fee = trrow.find("input[name='install_fee']").val();

                //                    console.info(JSON.stringify(itemObject));
                itemArray.push(itemObject);
            });

            console.info(JSON.stringify(itemArray));
            $("#items_string").val(JSON.stringify(itemArray));
            //                return false;

            $("form#frmPurchaseorder").submit();
        });



        $('#selectOrderModal').on('show.bs.modal', function(e) {
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
                        $informationid = $("#selectOrderModal").find('#informationid').val();
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

        function addBtnClickEventProject(btnId, soheadid, informationid) {
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
                    url: "{!! url('/basic/constructionbidinformations/updatesaleorderid/') !!}",
                    data: $("form#formAccept").serialize(),
                    // data: {id:soheadid,informationid:informationid},
                    dataType: "json",
                    success: function(result) {
                        if (result.errorcode >= 0) {
                            $('#selectOrderModal').modal('toggle');
                            alert("关联成功。");
                            window.location.reload('true');
                            // redirect('development/fabricdischarges');
                        } else
                            alert(result.errormsg);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });


        }

        $("select[name='purchaser']").change(function() {
            var purchase = $(this).val();
            var data = $(this).data("price");
            var objValue = $(this).parent().parent().find("input[name='value']");
            var objMatrial = $(this).parent().parent().find("input[name='material_fee']");
            var objInstall = $(this).parent().parent().find("input[name='install_fee']");

            // 当选择采购方是投标人时， 根据输入的材料费自动计算安装费 = 单条*倍数 - 材料费
            // 如果选择的采购方是华星东方时， 材料费和安装费设置为不可编辑。（ 如果很难实现就先放放， 也可以多花点时间研究研究）
            objMatrial.attr('readonly', true);
            objInstall.val(0);
            if (purchase == "华星东方") {
//                objValue.val(data.unitprice);
                objMatrial.val(0);
            } else {
//                objValue.val(data.unitprice_bidder);
                objMatrial.removeAttr('readonly');

                var objMultiple = $(this).parent().parent().find("input[name='multiple']");
                objInstall.val(data.unitprice_bidder * objMultiple.val() - objMatrial.val());
            }

            var objUnit = $(this).parent().parent().find("input[name='unit']");
            objUnit.val(data.unit);
        });

        // 当材料费启用时，计算安装费
        $("input[name='value'],input[name='multiple'],input[name='material_fee']").change(function() {
            // console.info($(this).val());
            var objMatrial = $(this).parent().parent().find("input[name='material_fee']");
            if (objMatrial.attr('readonly') == 'readonly') return;

            var objValue = $(this).parent().parent().find("input[name='value']");
            var objMultiple = $(this).parent().parent().find("input[name='multiple']");
            var objInstall = $(this).parent().parent().find("input[name='install_fee']");
            objInstall.val(objValue.val() * objMultiple.val() - objMatrial.val());
        });
    });
</script>
@endsection