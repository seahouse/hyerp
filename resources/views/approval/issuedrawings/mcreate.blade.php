@extends('app')

@section('title', '创建图纸下发申请单')

@section('main')


@can('approval_issuedrawing_create')
    {!! Form::open(array('url' => 'approval/issuedrawing/mstore', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
        @include('approval.issuedrawings._form',
        	[
        		'submitButtonText' => '提交',
        		'project_name' => null,
        		'drawingchecker' => null,
        		'pohead_name' => null,
        		'requestdeliverydate' => date('Y-m-d'),
        		'customer_name' => null,
        		'customer_id' => '0',
        		'amount' => '0.0',
        		'order_number' => null,
        		'order_id' => '0',
				'attr' => '',
				'attrdisable' => '',
				'btnclass' => 'btn btn-primary',
        	])
    {!! Form::close() !!}

	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

@else
	<div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无权限'}}
    </div>
@endcan

<!-- order selector -->
<div class="modal fade" id="selectDrawingcheckerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择图纸合校人</h4>
            </div>
            <div class="modal-body">
            	<div class="input-group">
            		{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '员工姓名', 'id' => 'keyDrawingchecker']) !!}
            		<span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchDrawingchecker']) !!}
                   	</span>
            	</div>
            	{!! Form::hidden('name', null, ['id' => 'name']) !!}
            	{!! Form::hidden('id', null, ['id' => 'id']) !!}
            	{!! Form::hidden('supplierid', 0, ['id' => 'supplierid']) !!}
            	{!! Form::hidden('poheadamount', 0, ['id' => 'poheadamount']) !!}
            	<p>
            		<div class="list-group" id="listsalesorders">

            		</div>
            	</p>
                <form id="formAccept">
                    {!! csrf_field() !!}                   	
                   	
{{--                    {!! Form::hidden('reimbursement_id', $reimbursement->id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('status', 0, ['class' => 'form-control']) !!} --}}   
                </form>                
            </div>
{{--            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
            </div>--}}   
        </div>
    </div>
</div>

<!-- supplier selector -->
<div class="modal fade" id="selectProjectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择项目</h4>
            </div>
            <div class="modal-body">
            	<div class="input-group">
            		{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '项目编号、项目名称', 'id' => 'keyProject']) !!}
            		<span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchProject']) !!}
                   	</span>
            	</div>
            	{!! Form::hidden('name', null, ['id' => 'name']) !!}
            	{!! Form::hidden('id', null, ['id' => 'id']) !!}
            	<p>
            		<div class="list-group" id="listproject">

            		</div>
            	</p>
                <form id="formAccept">
                    {!! csrf_field() !!}                   	
                   	
{{--                    {!! Form::hidden('reimbursement_id', $reimbursement->id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('status', 0, ['class' => 'form-control']) !!} --}}   
                </form>                
            </div>
{{--            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
            </div>--}}   
        </div>
    </div>
</div>

<div class="modal fade" id="selectSupplierModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">选择外协单位</h4>
			</div>
			<div class="modal-body">
				<div class="input-group">
					{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '供应商名称', 'id' => 'keySupplier']) !!}
					<span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchSupplier']) !!}
                   	</span>
				</div>
				{!! Form::hidden('name', null, ['id' => 'name']) !!}
				{!! Form::hidden('id', null, ['id' => 'id']) !!}
				<p>
				<div class="list-group" id="listsuppliers">

				</div>
				</p>
				<form id="formAccept">
					{!! csrf_field() !!}

					{{--                    {!! Form::hidden('reimbursement_id', $reimbursement->id, ['class' => 'form-control']) !!}
                                        {!! Form::hidden('status', 0, ['class' => 'form-control']) !!} --}}
				</form>
			</div>
			{{--            <div class="modal-footer">
                            {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                            {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
                        </div>--}}
		</div>
	</div>
</div>

<div class="modal fade" id="selectApprovalModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">选择关联相关审批单</h4>
			</div>
			<div class="modal-body">
				<div class="input-group" style="width:100%;">
					<div class='col-xs-4 col-sm-4' style="padding:5px;">
						{!! Form::select('type', array('designchangenotice' => '通用-设计变更通知单', 'vendordeduction' => '扣款-供应商扣款流程（ERP）'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', 'id' => 'approvaltype']) !!}
					</div>
					<div class='col-xs-6 col-sm-6' style="padding:5px;">
						{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '审批单号', 'id' => 'keyApproval']) !!}
					</div>
					<div class='col-xs-2 col-sm-2' style="padding:5px;">
                        <span class="input-group-btn">
                   		    {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchApproval']) !!}
                       </span>
					</div>
				</div>
				{!! Form::hidden('name', null, ['id' => 'name']) !!}
				{!! Form::hidden('id', null, ['id' => 'id']) !!}
				{!! Form::hidden('num', null, ['id' => 'num']) !!}
				<p>
				<div class="list-group" id="listApproval">

				</div>
				</p>
				<form id="formAccept">
					{!! csrf_field() !!}

					{{--                    {!! Form::hidden('reimbursement_id', $reimbursement->id, ['class' => 'form-control']) !!}
                                        {!! Form::hidden('status', 0, ['class' => 'form-control']) !!} --}}
				</form>
			</div>
			{{--            <div class="modal-footer">
                            {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                            {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
                        </div>--}}
		</div>
	</div>
</div>

<!-- before submit -->
<div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">提交确定</h4>                
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
                {!! Form::button('继续提交', ['class' => 'btn btn-sm', 'id' => 'btnSubmitContinue']) !!}
            </div>
        </div>
    </div>
</div>


@endsection


@section('script')
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>

	<script type="text/javascript">
		jQuery(document).ready(function(e) {
            var item_num = 1;

			 $("#btnSubmit").click(function() {
                 $("#materialsupplier").val($("#materialsupplier_id").find("option:selected").text());

                 var itemArray = new Array();

                 $("div[name='container_item']").each(function(i){
                     var itemObject = new Object();
                     var container = $(this);

                     itemObject.name = container.find("input[name='cabinet_name']").val();
                     itemObject.quantity = container.find("input[name='cabinet_quantity']").val();

//
//                     itemObject.unitprice_array = unitpriceArray;

                     itemArray.push(itemObject);

//                    alert(JSON.stringify(itemArray));
//                    return false;
//                    alert($("form#formMain").serialize());
                 });
                 $("#items_string").val(JSON.stringify(itemArray));

                 var tonnagedetailArray = new Array();
                 var tonnagedetailcontainer = $("#tonnagedetailcontainer");
                 tonnagedetailcontainer.find("div[name='div_unitpriceitem']").each(function (i) {
                     var tonnagedetailObject = new Object();
                     var unitpriceitemcontainer = $(this);
                     tonnagedetailObject.name = unitpriceitemcontainer.find("input[name='tonnage']").data("name");
                     tonnagedetailObject.tonnage = unitpriceitemcontainer.find("input[name='tonnage']").val();
                     if (tonnagedetailObject.tonnage == "")
                         tonnagedetailObject.tonnage = 0.0;
                     tonnagedetailObject.unitprice = 0.0;
                     tonnagedetailArray.push(tonnagedetailObject);
                 });
                 $("#tonnagedetails_string").val(JSON.stringify(tonnagedetailArray));
//                 return false;

                 $("form#formMain").submit();
			 });

			{{--$('#submitModal').on('shown.bs.modal', function (e) {--}}
				{{--$("#btnSubmitContinue").attr('disabled',true);--}}
				{{--$.ajax({--}}
					{{--type: "POST",--}}
					{{--url: "{{ url('approval/paymentrequests/check') }}",--}}
					{{--data: $("form#formMain").serialize(),--}}
					{{--dataType: "json",--}}
					{{--error:function(xhr, ajaxOptions, thrownError){--}}
						{{--alert('error');--}}
					{{--},--}}
					{{--success:function(msg){--}}
						{{--var strhtml = '';--}}
						{{--strhtml += "生活补贴合计: " + String(msg.mealamount) + "<br />";--}}
						{{--strhtml += "交通费合计: " + String(msg.ticketamount) + "<br />";--}}
						{{--strhtml += "总费用: " + String(msg.amountTotal) + "<br />";--}}
						{{--strhtml += "平均每日住宿费: " + String(msg.stayamountPer) + "<br />";--}}
						{{--strhtml += "平均每日合计: " + String(msg.amountPer) + "<br />";--}}
						{{--$("#dataDefine").empty().append(strhtml);--}}

						{{--if (msg.status == "OK")--}}
							{{--$("#btnSubmitContinue").attr('disabled', false);--}}
					{{--},--}}
				{{--});				--}}
			{{--});--}}

			$("#btnSubmitContinue").click(function() {
				$("form#formMain").submit();
			});


			$('#selectDrawingcheckerModal').on('show.bs.modal', function (e) {
				$("#listsalesorders").empty();

				var text = $(e.relatedTarget);
				var modal = $(this);

				modal.find('#name').val(text.data('name'));
				modal.find('#id').val(text.data('id'));
				modal.find('#supplierid').val(text.data('supplierid'));
				modal.find('#poheadamount').val(text.data('poheadamount'));
			});

			$("#btnSearchDrawingchecker").click(function() {
				if ($("#keyDrawingchecker").val() == "") {
					alert('请输入关键字');
					return;
				}
				$.ajax({
					type: "GET",
					url: "{!! url('/system/users/getitemsbykey/') !!}" + "/" + $("#keyDrawingchecker").val(),
					success: function(result) {
						var strhtml = '';
						$.each(result.data, function(i, field) {
							btnId = 'btnSelectDrawingchecker_' + String(i);
							strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
						});
						if (strhtml == '')
							strhtml = '无记录。';
						$("#listsalesorders").empty().append(strhtml);

						$.each(result.data, function(i, field) {
							btnId = 'btnSelectDrawingchecker_' + String(i);
							addBtnClickEvent(btnId, field);
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert('error');
					}
				});
			});

			function addBtnClickEvent(btnId, field)
			{
				$("#" + btnId).bind("click", function() {
					$('#selectDrawingcheckerModal').modal('toggle');
//					$("#" + $("#selectDrawingcheckerModal").find('#name').val()).val(number);
//					$("#" + $("#selectDrawingcheckerModal").find('#id').val()).val(salesorderid);
//					$("#" + $("#selectDrawingcheckerModal").find('#poheadamount').val()).val(amount);
					$("#drawingchecker").val(field.name);
					$("#drawingchecker_id").val(field.id);
					{{--
					$("#pohead_amount_paid").val(amount_paid);
					$("#pohead_amount_ticketed").val(field.amount_ticketed);
					var pohead_arrived = '未到货';
					if (field.arrival_percent > 0.0 && field.arrival_percent < 0.99)
						pohead_arrived = '部分到货';
					else if (field.arrival_percent >= 0.99)
						pohead_arrived = '全部到货';
					$("#pohead_arrived").val(pohead_arrived);
					$("#paymethod").val(field.paymethod);

					if (amount > 0.0)
					{
						var percent = amount_paid / amount * 100;
						var percent_str = percent.toFixed(2);
						$("#amount_paid_percent").html(percent_str + "%");

						percent = field.amount_ticketed / amount * 100;
						percent_str = percent.toFixed(2);
						$("#amount_ticketed_percent").html(percent_str + "%");
					}
					$("#pohead_productname").val(field.productname);

					$.ajax({
						type: "GET",
						url: "{!! url('/sales/salesorders/getitembyid/') !!}" + "/" + field.sohead_id,
						success: function(result) {
							$("#sohead_installeddate").val(result.installeddate.substring(0, 10));
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert('error');
						}
					});
					--}}
				});
			}

			$('#selectProjectModal').on('show.bs.modal', function (e) {
				$("#listproject").empty();

				var text = $(e.relatedTarget);
				// alert(text.data('id'));

				var modal = $(this);
				modal.find('#name').val(text.data('name'));
				modal.find('#id').val(text.data('id'));
				// alert(modal.find('#id').val());
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
							strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" +
								field.number + "</h4><p>" + field.descrip + "</p><p>" + field.company_name + "</p></button>"
						});
						if (strhtml == '')
							strhtml = '无记录。';
						$("#listproject").empty().append(strhtml);

						$.each(result.data, function(i, field) {
							btnId = 'btnSelectProject_' + String(i);
							addBtnClickEventProject(btnId, field.id, field.number, field);
						});
						// addBtnClickEvent('btnSelectOrder_0');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert('error');
					}
				});
			});

			function addBtnClickEventProject(btnId, soheadid, name, field)
			{
				$("#" + btnId).bind("click", function() {
					$('#selectProjectModal').modal('toggle');
					$("#" + $("#selectProjectModal").find('#name').val()).val(field.descrip);
					$("#" + $("#selectProjectModal").find('#id').val()).val(soheadid);
                    $("#sohead_number").val(field.number);
					$("#company_name").val(field.company_name);
//					$("#supplier_bank").val(field.bank);
//					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
//					$("#vendbank_id").val(field.vendbank_id);
//					$("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
				});
			}

            $('#selectSupplierModal').on('show.bs.modal', function (e) {
                $("#listsuppliers").empty();

                var text = $(e.relatedTarget);
                // alert(text.data('id'));

                var modal = $(this);
                modal.find('#name').val(text.data('name'));
                modal.find('#id').val(text.data('id'));
                // alert(modal.find('#id').val());
            });

            $("#btnSearchSupplier").click(function() {
                if ($("#keySupplier").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/purchase/vendinfos/getitemsbykey/') !!}" + "/" + $("#keySupplier").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectSupplier_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listsuppliers").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectSupplier_' + String(i);
                            addBtnClickEventSupplier(btnId, field.id, field.name, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventSupplier(btnId, supplierid, name, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectSupplierModal').modal('toggle');
//                    $("#" + $("#selectSupplierModal").find('#name').val()).val(name);
//                    $("#" + $("#selectSupplierModal").find('#id').val()).val(supplierid);
                    $("#outsourcingcompany").val(field.name);
                    $("#outsourcingcompany_id").val(field.id);
                });
            }

            $("#btnAddItem").click(function() {
                item_num++;
                var btnId = 'btnDeleteItem_' + String(item_num);
                var divName = 'divClassItem_' + String(item_num);
                var itemHtml = '<div class="' + divName + '"><p class="bannerTitle">柜体明细(' + String(item_num) + ')&nbsp;<button class="btn btn-sm" id="' + btnId + '" type="button">删除</button></p>\
                	<div name="container_item">\
						<div class="form-group">\
							<label for="cabinet_name" class="col-xs-4 col-sm-2 control-label">柜体名称:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" ="" name="cabinet_name" type="text" id="cabinet_name_' + String(item_num) + '">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="cabinet_quantity" class="col-xs-4 col-sm-2 control-label">数量:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" placeholder="" ="" id="cabinet_quantity_' + String(item_num) + '" name="cabinet_quantity" type="text">\
							</div>\
						</div>\
                </div>\
                </div>';
                $("#itemMore").append(itemHtml);
                addBtnDeleteItemClickEvent(btnId, divName);
            });

            function addBtnDeleteItemClickEvent(btnId, divName)
            {
                $("#" + btnId).bind("click", function() {
                    // travelNum--; 	// 不需要减法，否则在删除中间段的时候会导致有重复div
                    $("." + divName).remove();
                });
            }


			// show amount percent when blur
			$("#amount").blur(function() {
				if ($("#pohead_amount").val() > 0.0 && $("#amount").val() > 0.0)
				{
					var percent = $("#amount").val() / $("#pohead_amount").val() * 100;
					var percent_str = percent.toFixed(2);
					$("#amount_percent").html(percent_str + "%");
				}
			});

            selectDesigndepartmentChange = function () {
                var designdepartment = $("#designdepartment").val();
//                alert(designdepartment);
//                $("#pppaymentitemtypecontainer_1" + String(num)).empty();
                var strhtml = '';
                var strhtml2 = '';

                if (designdepartment == "电控室")
                {
                    $("#cabinet_detail").attr("style", "display:'';");
                }
                else
				{
                    $("#cabinet_detail").attr("style", "display:none;");
				}

            }

            selectProductioncompanyChange = function () {
                var productioncompany = $("#productioncompany_id").find("option:selected").text();
                $("#productioncompany").val(productioncompany);
//                var productioncompany = $("#productioncompany").val();

                if (productioncompany == "外协单位")
                {
                    $("#divOutsourcingcompany").attr("style", "display:'';");
                }
                else
                {
                    $("#divOutsourcingcompany").attr("style", "display:none;");
                    $("#outsourcingcompany").val("");
                    $("#outsourcingcompany_id").val(0);
                }
            }

            selectTypeChange = function (num) {
                var productioncompany = $("#productioncompany_id").find("option:selected").text();
//                var productioncompany = $("#productioncompany").val();
                console.log(productioncompany);
                var strhtml = '';
                var strhtml2 = '';
                var selecttype = $("#type_" + String(num));
                var selectarea = $("#area_" + String(num));

                $.post("{{ url('approval/issuedrawing/gettonnagedetailhtml') }}", { productioncompany: productioncompany, selectarea: selectarea.val(), selecttype: selecttype.val() }, function (data) {
                    //
                    $("#tonnagedetailcontainer").empty().append(data);
                });
            }

            $('#selectApprovalModal').on('show.bs.modal', function (e) {
                $("#listApproval").empty();
                $("#approvaltype").val('');
                $("#keyApproval").val('');

                var target = $(e.relatedTarget);
                // alert(text.data('id'));

                var modal = $(this);
                modal.find('#name').val(target.data('name'));
                modal.find('#id').val(target.data('id'));
                modal.find('#num').val(target.data('num'));
                // alert(modal.find('#id').val());
            });

            $("#btnSearchApproval").click(function() {
                if ($("#approvaltype").val() == "") {
                    alert('请选择审批单类型');
                    return;
                }

                if ($("#keyApproval").val() == "") {
                    alert('请输入关键字');
                    return;
                }

                var requestUrl = 'approval/getdtitemsbykey';
                $.ajax({
                    type: "GET",
                    url: "{!! url('" + requestUrl + "') !!}" + "?type=" + $("#approvaltype").val() + "&key=" + $("#keyApproval").val(),
                    success: function(result) {

                        var html = [];
                        if (result && result.business_id) {
                            html.push("<button type='button' class='list-group-item' id='btnSelectProjectpurchase_0'>");
                            html.push("<h4>" + result.business_id + "</h4><p>");
                            html.push(result.title+ '<br/>');
                            if (result.content) {
                                for(var key in result.content) {
                                    if (result.content.hasOwnProperty(key)) {
                                        html.push(key + ': ' + result.content[key] + '<br/>');
                                    }
                                }
                            }
                            html.push("</p></button>");
                        }
                        else {
                            html.push('无记录。');
                        }

                        $("#listApproval").empty().append(html.join(''));

                        addBtnClickEventProjectpurchase('btnSelectProjectpurchase_0', result);
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventProjectpurchase(btnId, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectApprovalModal').modal('toggle');

                    var html = [];
                    html.push('<div id="divRemovePurchaseClick_' + field.business_id + '">');
                    html.push("<span>关联审批单：" + field.business_id + "</span>");
                    html.push('&nbsp;&nbsp;<a data-business_id="' + field.business_id + '" data-process_instance_id="' + field.process_instance_id + '" onclick="window.removeProjectpurchaseClick(this);" href="javascript:void(0);">删除</a>');
                    html.push("</div>");

                    var hVal = [];
                    if ($("#associatedapprovals").val() != '') {
                        hVal = $("#associatedapprovals").val().split(',');
                    }
                    if ($.inArray(field.process_instance_id, hVal) < 0) {
                        hVal.push(field.process_instance_id);
                        $("#lblAssociatedapprovals").append(html.join(''));
                    }
                    $("#associatedapprovals").val(hVal.join(','));
//					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
//					$("#vendbank_id").val(field.vendbank_id);
                });
            }

            window.removeProjectpurchaseClick = function(it) {
                var process_instance_id = $(it).attr('data-process_instance_id');
                $("#divRemovePurchaseClick_" + $(it).attr('data-business_id')).remove();

                var hVal = [];
                if ($("#associatedapprovals").val() != '') {
                    hVal = $("#associatedapprovals").val().split(',');
                }
                var pos = $.inArray(process_instance_id, hVal);
                hVal.splice(pos, 1);
                $("#associatedapprovals").val(hVal.join(','));
                return false;
            }


			dd.config({
			    agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
			    corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
			    timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
			    nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
			    signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
			    jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile'] // 必填，需要使用的jsapi列表
			});

			// $.ajax({
			// 	type: "GET",
			// 	url: "{{ url('dingtalk/getconfig') }}",
			// 	error:function(xhr, ajaxOptions, thrownError){
   //           		alert('getConfig failed.');
   //           	    alert('error');
			// 		alert(xhr.status);
			// 		alert(xhr.responseText);
			// 		alert(ajaxOptions);
			// 		alert(thrownError);
   //           	},
   //           	success:function(result){
   //           		alert('getConfig success. signature:' + result.signature);
   //           		dd.config({
			// 		    agentId: '13231599', // 必填，微应用ID
			// 		    corpId: 'ding6ed55e00b5328f39',//必填，企业ID
			// 		    timeStamp: result.timeStamp, // 必填，生成签名的时间戳
			// 		    nonceStr: result.nonceStr, // 必填，生成签名的随机串
			// 		    signature: result.signature, // 必填，签名
			// 		    jsApiList: ['device.notification.alert', 'device.notification.confirm', 'biz.util.uploadImage'] // 必填，需要使用的jsapi列表
			// 		});
   //              },
			// });


			dd.ready(function() {
				$("#btnSelectImage").click(function() {
					dd.biz.util.uploadImage({
						multiple: true,
						max: 5,
						onSuccess: function(result) {
							var images = result;	// result.split(',');
							var imageHtml = '';
							for (var i in images) {
								imageHtml += '<div class="col-xs-6 col-md-3">';
								imageHtml += '<div class="thumbnail">';
								imageHtml += '<img src=' + images[i] + ' />';
								imageHtml += '<input name="image_' + String(i) + '" value=' + images[i] + ' type="hidden">';
								imageHtml += '</div>';
								imageHtml += '</div>';
							}
							$("#previewimage").empty().append(imageHtml);
						},
						onFail: function(err) {
							alert('select image failed: ' + JSON.stringify(err));
						}
					});
				});

				// // 上传附件
				// $("#btnSelectPaymentnodeattachment").click(function() {
				// 	dd.biz.cspace.saveFile({
				// 		corpId:"{!! array_get($config, 'corpId') !!}",
				// 		url:"https://ringnerippca.files.wordpress.com/20.pdf",
				// 		name:"文件名",
				// 		onSuccess: function(data) {
		  //                 data结构
		  //                {"data":
		  //                   [
		  //                   {
		  //                   "corpId": "", //公司id
		  //                   "spaceId": "" //空间id
		  //                   "fileId": "", //文件id
		  //                   "fileName": "", //文件名
		  //                   "fileSize": 111111, //文件大小
		  //                   "fileType": "", //文件类型
		  //                   }
		  //                   ]
		  //                }
		                 
		  //               },
		  //               onFail: function(err) {
		  //                   alert(JSON.stringify(err));
		  //               }
				// 	});
				// });
			});

			dd.error(function(error) {
				alert('dd.error: ' + JSON.stringify(error));
			});
		});
	</script>

<!--	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
	
	<script type="text/javascript">
		
		jQuery(document).ready(function(e) {
			dd.ready(function() {
				dd.config({
				    agentId: '13231599', // 必填，微应用ID
				    corpId: 'ding6ed55e00b5328f39',//必填，企业ID
				    timeStamp: e.timeStamp, // 必填，生成签名的时间戳
				    nonceStr: '12345', // 必填，生成签名的随机串
				    signature: '', // 必填，签名
				    jsApiList: ['device.notification.alert', 'device.notification.confirm'] // 必填，需要使用的jsapi列表
				});

				dd.device.base.getUUID({
				    onSuccess : function(data) {
				    	alert(data.uuid);
				    },
				    onFail : function(err) {
				    	alert("dd.device.base.getUUID");
				    	alert(JSON.stringify(err));
				    }
				});

				$("#date").click(function() {
					var mydate = new Date();
					dd.biz.util.datepicker({
					    format: 'yyyy-MM-dd',
					    value: mydate.toLocaleString(),  //'2015-04-17', //默认显示日期
					    onSuccess : function(result) {
					    	$("#date").val(result.value);
					        //onSuccess将在点击完成之后回调
					        /*{
					            value: "2015-02-10"
					        }
					        */
					    },
					    onFail : function() {}
					});
				});

				$("#datego").click(function() {
					var mydate = new Date();
					dd.biz.util.datepicker({
					    format: 'yyyy-MM-dd',
					    value: mydate.toLocaleString(),  //'2015-04-17', //默认显示日期
					    onSuccess : function(result) {
					    	$("#datego").val(result.value);
					        //onSuccess将在点击完成之后回调
					        /*{
					            value: "2015-02-10"
					        }
					        */
					    },
					    onFail : function() {}
					});
				});

				$("#dateback").click(function() {
					var mydate = new Date();
					dd.biz.util.datepicker({
					    format: 'yyyy-MM-dd',
					    value: mydate.toLocaleString(),  //'2015-04-17', //默认显示日期
					    onSuccess : function(result) {
					    	$("#dateback").val(result.value);
					        //onSuccess将在点击完成之后回调
					        /*{
					            value: "2015-02-10"
					        }
					        */
					    },
					    onFail : function() {}
					});
				});

			});
		});
	</script>
-->
@endsection
