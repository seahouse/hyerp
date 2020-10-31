@extends('app')

@section('title', '创建客户扣款审批单')

@section('main')

@can('approval_customerdeduction_create')
    {!! Form::open(array('url' => 'approval/customerdeduction/mstore', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
        @include('approval.customerdeductions._form',
        	[
        		'submitButtonText' => '提交',
        		'project_name' => null,
        		'drawingchecker' => null,
        		'pohead_name' => null,
        		'item_name' => null,
        		'paymentdate' => date('Y-m-d'),
        		'customer_name' => null,
        		'customer_id' => '0',
        		'amount' => '0.0',
        		'order_number' => null,
        		'order_id' => '0',
        		'paydate'   => null,
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

<!-- customer selector -->
<div class="modal fade" id="selectCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择客户</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '客户名称', 'id' => 'keyCustomer']) !!}
                    <span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchCustomer']) !!}
                   	</span>
                </div>
                {!! Form::hidden('name', null, ['id' => 'name']) !!}
                {!! Form::hidden('id', null, ['id' => 'id']) !!}
                <p>
                <div class="list-group" id="listcustomer">

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
				{!! Form::hidden('num', null, ['id' => 'num']) !!}
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

<div class="modal fade" id="selectApproval" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择工程采购审批单</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '审批单号', 'id' => 'keyApproval']) !!}
                    <span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchApproval']) !!}
                   	</span>
                </div>
                {!! Form::hidden('name', null, ['id' => 'name']) !!}
                {!! Form::hidden('id', null, ['id' => 'id']) !!}
                {!! Form::hidden('num', null, ['id' => 'num']) !!}
                <p>
                <div class="list-group" id="listapproval">

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

<!-- item selector -->
<div class="modal fade" id="selectItemModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">选择物品</h4>
			</div>
			<div class="modal-body">
				<div class="input-group">
					{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '物品名称', 'id' => 'keyItem']) !!}
					<span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchItem']) !!}
                   	</span>
				</div>
				{!! Form::hidden('name', null, ['id' => 'name']) !!}
				{!! Form::hidden('id', null, ['id' => 'id']) !!}
				{!! Form::hidden('num', null, ['id' => 'num']) !!}
				<p>
				<div class="list-group" id="listitem">

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

<!-- supplier bank selector -->
<div class="modal fade" id="selectSupplierBankModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">选择开户行与账号</h4>
			</div>
			<div class="modal-body">
				{{--
                                <div class="input-group">
                                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '供应商名称', 'id' => 'keyCustomer']) !!}
                                    <span class="input-group-btn">
                                           {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchCustomer']) !!}
                                       </span>
                                </div>
                --}}
				{!! Form::hidden('name', null, ['id' => 'name']) !!}
				{!! Form::hidden('id', null, ['id' => 'id']) !!}
				<p>
				<div class="list-group" id="listsupplierbanks">

				</div>
				</p>
				<p>
				{!! Form::button('新增', ['class' => 'btn btn-sm', 'id' => 'btnShowAddVendbank']) !!}
				<form id="formAddVendbank">
					{!! csrf_field() !!}
					<div class="form-group">
						{!! Form::label('bankname', '开户行:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
						<div class='col-xs-8 col-sm-10'>
							{!! Form::text('bankname', null, ['class' => 'form-control', 'placeholder' => '请输入开户行']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('accountnum', '银行账号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
						<div class='col-xs-8 col-sm-10'>
							{!! Form::text('accountnum', null, ['class' => 'form-control', 'placeholder' => '请输入银行账号']) !!}
						</div>
					</div>
					{!! Form::hidden('vendinfo_id', 0, ['id' => 'vendinfo_id']) !!}
					{!! Form::hidden('isdefault', 1, ['id' => 'isdefault']) !!}
					{!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAddVendbank']) !!}
					{{--
                                        {!! Form::hidden('reimbursement_id', $reimbursement->id, ['class' => 'form-control']) !!}
                                        {!! Form::hidden('status', 0, ['class' => 'form-control']) !!}
                    --}}
				</form>
				</p>

			</div>
			{{--
                        <div class="modal-footer">
                            {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                            {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
                        </div>
            --}}
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
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
{{--                {!! Form::button('继续提交', ['class' => 'btn btn-sm', 'id' => 'btnSubmitContinue']) !!} --}}
            </div>
        </div>
    </div>
</div>

	{{-- upload and parse excel file form --}}
{{--
	<form id="formUploadParseExcel" method="post" action="{{ url('approval/mcitempurchase/uploadparseexcel') }}"  class="form-horizontal" enctype="multipart/form-data">
		<div class="form-group">
			{!! Form::label('items_excelfile', '解析明细模板文件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
			<div class='col-xs-8 col-sm-10'>
				{!! Form::file('items_excelfile', []) !!}
				{!! Form::button('解析Excel', ['class' => 'btn btn-sm', 'id' => 'btnParseExcel']) !!}
			</div>
		</div>
	</form>
--}}

@endsection


@section('script')
	{{--<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>--}}
    <script src="https://g.alicdn.com/dingding/dingtalk-jsapi/2.7.13/dingtalk.open.js"></script>

	<script type="text/javascript">
		jQuery(document).ready(function(e) {
            var item_num = 1;

			 $("#btnSubmit").click(function() {
                 var itemArray = new Array();

                 var flag = true;
                $("div[name='container_item']").each(function(i){
                    var itemObject = new Object();
                    var container = $(this);

                    itemObject.type = container.find("select[name='type']").val();
                    itemObject.otherremark = container.find("input[name='otherremark']").val();
                    itemObject.unit = container.find("input[name='unit']").val();
//                    itemObject.tonnage = container.find("input[name='tonnage']").val();
//                    if (itemObject.tonnage == "")
//                        itemObject.tonnage = 0.0;
                    itemObject.quantity = container.find("input[name='quantity']").val();
                    if (itemObject.quantity == "")
                        itemObject.quantity = 0.0;
                    console.log(!isNaN(itemObject.quantity));
                    if (isNaN(itemObject.quantity))
                        flag = false;
//                    itemObject.unit_name = container.find("select[name='unit_id']").find("option:selected").text();
                    itemObject.amount = container.find("input[name='amount']").val();
                    if (itemObject.amount == "")
                        itemObject.amount = 0.0;



                    itemArray.push(itemObject);

//                    alert(JSON.stringify(itemArray));
//                    return false;
//                    alert($("form#formMain").serialize());
                });
                $("#items_string").val(JSON.stringify(itemArray));

                 if (!flag)
                 {
                     $('#submitModal').modal('toggle');
                     $("#dataDefine").empty().append('数量字段必须是数字类型，不能包含字符串数值。');
                     return false;
                 }

				$("form#formMain").submit();

			 });


			$("#btnSubmitContinue").click(function() {
				$("form#formMain").submit();
			});

            $('#selectCustomerModal').on('show.bs.modal', function (e) {
                $("#listsuppliers").empty();

                var text = $(e.relatedTarget);
                // alert(text.data('id'));

                var modal = $(this);
                modal.find('#name').val(text.data('name'));
                modal.find('#id').val(text.data('id'));
                // alert(modal.find('#id').val());
            });

            $("#btnSearchCustomer").click(function() {
                if ($("#keyCustomer").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/sales/custinfos/getitemsbykey/') !!}" + "/" + $("#keyCustomer").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectCustomer_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listcustomer").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectCustomer_' + String(i);
                            addBtnClickEventCustomer(btnId, field.id, field.name, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventCustomer(btnId, supplierid, name, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectCustomerModal').modal('toggle');
                    $("#customer_name").val(field.name);
                    $("#customer_id").val(field.id);
                });
            }

            $('#selectApproval').on('show.bs.modal', function (e) {
                $("#listapproval").empty();

                var target = $(e.relatedTarget);
                // alert(text.data('id'));

                var modal = $(this);
                modal.find('#name').val(target.data('name'));
                modal.find('#id').val(target.data('id'));
                modal.find('#num').val(target.data('num'));
                // alert(modal.find('#id').val());
            });

            $("#btnSearchApproval").click(function() {
                if ($("#keyApproval").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/approval/projectsitepurchases/getitemsbykey/') !!}" + "/" + $("#keyApproval").val(),
                    success: function(result) {
                        var strhtml = '';
                        console.log(result);
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectApproval_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.business_id + "</h4><p>提交人：" + field.applicant + "，项目简称：" + field.projectjc + "，项目订单编号：" + field.sohead_number + "</p></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listapproval").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectApproval_' + String(i);
                            addBtnClickEventProjectpurchase(btnId, field.id, field.number, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventProjectpurchase(btnId, soheadid, name, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectApproval').modal('toggle');
                    var strhtml = '关联审批单：' + field.business_id;
                    $("#lblAssociatedapprovals").empty().append(strhtml);
                    $("#associated_approval_projectpurchase").val(field.process_instance_id);
//					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
//					$("#vendbank_id").val(field.vendbank_id);
                });
            }

			$('#selectProjectModal').on('show.bs.modal', function (e) {
				$("#listproject").empty();

				var target = $(e.relatedTarget);
				// alert(text.data('id'));

				var modal = $(this);
				modal.find('#name').val(target.data('name'));
				modal.find('#id').val(target.data('id'));
                modal.find('#num').val(target.data('num'));
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
							strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.number + "</h4><p>" + field.descrip + "</p></button>"
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
                    $("#sohead_number_" + $("#selectProjectModal").find('#num').val()).val(field.number);
                    $("#sohead_number").val(field.number);
					$("#sohead_salesmanager").val(field.salesmanager);
					if (field.C == 0)
					    $("#projecttype").val('EP项目');
					else
                        $("#projecttype").val('EPC项目');
				});
			}

            $('#selectItemModal').on('show.bs.modal', function (e) {
                $("#listitem").empty();

                var target = $(e.relatedTarget);

                var modal = $(this);
                modal.find('#num').val(target.data('num'));
//                modal.find('#id').val(target.data('id'));
            });

            $("#btnSearchItem").click(function() {
                if ($("#keyItem").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/product/items/getitemsbykey/') !!}" + "/" + $("#keyItem").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectItem_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.goods_name + "(" + field.goods_spec + ")</h4></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listitem").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectItem_' + String(i);
                            addBtnClickEventItem(btnId, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventItem(btnId, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectItemModal').modal('toggle');
//                    $("#item_name").val(field.goods_name);
//                    $("#item_id").val(field.goods_id);
//                    $("#item_spec").val(field.goods_spec);
//                    $("#unit").val(field.goods_unit_name);
                    $("#item_name_" + $("#selectItemModal").find('#num').val()).val(field.goods_name);
                    $("#item_id_" + $("#selectItemModal").find('#num').val()).val(field.goods_id);
                    $("#item_spec_" + $("#selectItemModal").find('#num').val()).val(field.goods_spec);
                    $("#unit_" +  + $("#selectItemModal").find('#num').val()).val(field.goods_unit_name);
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

            $('#selectIssueDrawingsModal').on('shown.bs.modal', function (e) {
                 $("#listissuedrawings").empty();

                var text = $(e.relatedTarget);
                var modal = $(this);


                $.ajax({
                    type: "GET",
                    url: "{!! url('/approval/issuedrawing/getitemsbysoheadid/') !!}" + "/" + $("#sohead_id_" + $("#selectIssueDrawingsModal").find('#num').val()).val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectSupplierbank_' + String(i);
                            // strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + field.bankname + ": " + field.accountnum + "</button>"
                            strhtml += '<label class="list-group-item"><input type="checkbox" name="check_issuedrawing" value="' + field.id + '" data-number="' + field.business_id + '">' + field.business_id + '</label>';
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        modal.find('#listissuedrawings').empty().append(strhtml);

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            $("#btnok_selectissuedrawing").click(function() {

                var checkvalues = [];
                var checknumbers = [];
                $("#selectIssueDrawingsModal").find("input[type='checkbox']:checked").each(function (i) {
                    checkvalues[i] =$(this).val();
                    checknumbers[i] = $(this).attr('data-number');
                });

//                $("input[name='check_issuedrawing']:checked").each(function(i){
//                    checkvalues[i] =$(this).val();
//                    checknumbers[i] = $(this).attr('data-number');
//                });

                $("#" + $("#selectIssueDrawingsModal").find('#name').val()).val(checknumbers.join(","));
                $("#" + $("#selectIssueDrawingsModal").find('#id').val()).val(checkvalues.join(","));
//                $("#issuedrawing_numbers").val(checknumbers.join(","));
//                $("#issuedrawing_values").val(checkvalues.join(","));
                $('#selectIssueDrawingsModal').modal("toggle");
            });

            $('#selectSupplierBankModal').on('show.bs.modal', function (e) {
                $("#listsupplierbanks").empty();
                $("form#formAddVendbank").hide();
                $("#selectSupplierBankModal").find("#bankname").val("");
                $("#selectSupplierBankModal").find("#accountnum").val("");

                var text = $(e.relatedTarget);
                var modal = $(this);

                modal.find('#name').val(text.data('name'));
                modal.find('#id').val(text.data('id'));
            });

            $('#selectSupplierBankModal').on('shown.bs.modal', function (e) {
                // $("#listsupplierbanks").empty();

                var text = $(e.relatedTarget);
                var modal = $(this);

                // modal.find('#listsupplierbanks').append("aaaa");

                $.ajax({
                    type: "GET",
                    url: "{!! url('/purchase/vendbank/getitemsbyvendid/') !!}" + "/" + $("#supplier_id").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectSupplierbank_' + String(i);
                            // strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.bankname + "</h4><p>" + field.accountnum + "</p></button>"
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + field.bankname + ": " + field.accountnum + "</button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        modal.find('#listsupplierbanks').empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectSupplierbank_' + String(i);
                            addBtnClickEventSupplierbank(btnId, field);
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventSupplierbank(btnId, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectSupplierBankModal').modal('toggle');
                    // $("#" + $("#selectCustomerModal").find('#name').val()).val(name);
                    // $("#" + $("#selectCustomerModal").find('#id').val()).val(supplierid);
                    $("#vendbank_id").val(field.id);
                    $("#supplier_bank").val(field.bankname);
                    $("#supplier_bankaccountnumber").val(field.accountnum);
                });
            }

            $("#btnShowAddVendbank").click(function() {
                $("form#formAddVendbank").show();
            });

            $("#btnAddVendbank").click(function() {
                if ($("#selectSupplierBankModal").find("#vendinfo_id").val() == 0)
                {
                    alert("还未选中供应商。");
                    return;
                }
                if ($("#selectSupplierBankModal").find("#bankname").val().trim() == "" || $("#selectSupplierBankModal").find("#accountnum").val().trim() == "")
                {
                    alert("开户行和银行账号不能为空。");
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ url('purchase/vendbank') }}",
                    data: $("form#formAddVendbank").serialize(),
                    dataType: "json",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert('error');
                    },
                    success:function(result){
                        alert("新增成功。");
                        $('#selectSupplierBankModal').modal('toggle');
                        $("#vendbank_id").val(result.id);
                        $("#supplier_bank").val(result.bankname);
                        $("#supplier_bankaccountnumber").val(result.accountnum);
                    },
                });
            });

            $("#btnAddItem").click(function() {
                item_num++;
                var btnId = 'btnDeleteItem_' + String(item_num);
                var divName = 'divClassItem_' + String(item_num);
                var itemHtml = '<div class="' + divName + '"><p class="bannerTitle">增补内容明细(' + String(item_num) + ')&nbsp;<button class="btn btn-sm" id="' + btnId + '" type="button">删除</button></p>\
                	<div name="container_item">\
						<div class="form-group">\
							{!! Form::label('type', '增补内容:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}\
							<div class="col-sm-10 col-xs-8">\
							<select class="form-control" onchange="selectTypeChange(this.dataset.num)" data-num="' + String(item_num) + '" ="" id="type_' + String(item_num) + '" name="type"><option value="机务材料">机务材料</option><option value="机务设备">机务设备</option><option value="电气材料">电气材料</option><option value="电气设备">电气设备</option><option value="人工用量">人工用量</option><option value="运费">运费</option><option value="其他类别">其他类别</option></select>\
                    </div>\
						</div>\
                    <div id="divOtherremark_' + String(item_num) + '">\
                            <div class="form-group">\
                                <label for="otherremark" class="col-xs-4 col-sm-2 control-label">其他类别补充说明:</label>\
                                <div class="col-sm-10 col-xs-8">\
                                <input class="form-control" ="" name="otherremark" type="text" id="otherremark_' + String(item_num) + '">\
                                </div>\
                            </div>\
						</div>\
						<div class="form-group">\
							<label for="unit" class="col-xs-4 col-sm-2 control-label">单位:</label>\
							<div class="col-sm-10 col-xs-8">\
							    <input class="form-control" ="" id="unit_' + String(item_num) + '" name="unit" type="text">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="quantity" class="col-xs-4 col-sm-2 control-label">数量:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" placeholder="" ="" id="quantity_' + String(item_num) + '" name="quantity" type="text">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="amount" class="col-xs-4 col-sm-2 control-label">此项增补金额（元）:</label>\
							<div class="col-sm-10 col-xs-8">\
							    <input class="form-control" ="" id="amount_' + String(item_num) + '" name="amount" type="text">\
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

            selectSigncontract_conditionChange = function () {
                var signcontract_condition = $("#signcontract_condition").val();
                if (signcontract_condition == "甲方与我们已签定增补合同")
                {
                    $("#divFiles").attr("style", "display:none;");
                    $("#divImages").attr("style", "display:block;");
                }
                else
                {
                    $("#divFiles").attr("style", "display:block;");
                    $("#divImages").attr("style", "display:none;");
                }
            }

            selectTypeChange = function (num) {
                var selecttype = $("#type_" + String(num));
                if (selecttype.val() == "其他类别")
                {
                    $("#divOtherremark_" + String(num)).attr("style", "display:block;");
                }
                else
                    $("#divOtherremark_" + String(num)).attr("style", "display:none;");
            }

			dd.config({
			    agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
			    corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
			    timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
			    nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
			    signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
			    jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile', 'biz.util.uploadAttachment', 'biz.cspace.preview'] // 必填，需要使用的jsapi列表
			});

//            window.selectImage_Mobile = function(evt) {
////                alert('aaa');
//                alert(i);
//                var target = evt.srcElement|evt.target;
////                var num = $(this).val();
//                    alert($(this).val());
//            }

//            function selectImage_Mobile() {
//                alert('aaa');
//            }

//            $("button[name=aaaaaa]").click(function() {
//                var num = $(this).val();
//                    alert($(this).val());
//            });


			dd.ready(function() {
				$("#btnSelectImage").click(function() {
				    var num = $(this).val();
//                    alert($(this).val());
					dd.biz.util.uploadImage({
						multiple: true,
						max: 9,
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
//                            alert($(this).val());
                            $("#imagesname_mobile").val(result);
							$("#previewimage").empty().append(imageHtml);
						},
						onFail: function(err) {
							alert('select image failed: ' + JSON.stringify(err));
						}
					});
				});

                selectImage_Mobile = function (num) {
//                    var num = $(this).val();
//                    alert($(this).val());
                    dd.biz.util.uploadImage({
                        multiple: true,
                        max: 9,
                        onSuccess: function(result) {
                            var images = result;	// result.split(',');
                            var imageHtml = '';
                            for (var i in images) {
                                imageHtml += '<div class="col-xs-6 col-md-3">';
                                imageHtml += '<div class="thumbnail">';
                                imageHtml += '<img src=' + images[i] + ' />';
                                //imageHtml += '<input name="image_' + String(i) + '" value=' + images[i] + ' type="hidden">';
                                imageHtml += '</div>';
                                imageHtml += '</div>';
                            }
//                            alert($(this).val());
                            $("#imagesname_mobile_" + String(num)).val(result);
                            $("#previewimage_" + String(num)).empty().append(imageHtml);
                        },
                        onFail: function(err) {
                            alert('select image failed: ' + JSON.stringify(err));
                        }
                    });
                };


                // 上传附件
                $("#uploadAttach").click(function () {
                    dd.biz.util.uploadAttachment({
                        {{--image:{multiple:true,compress:false,max:9,spaceId: "{!! array_get($config, 'spaceid') !!}"},--}}
                        space:{corpId:"{!! array_get($config, 'corpId') !!}",spaceId:"{!! array_get($config, 'spaceid') !!}",isCopy:1 , max:9},
                        file:{spaceId:"{!! array_get($config, 'spaceid') !!}",max:5},
                        types:["file","space"],//PC端支持["photo","file","space"]
                        onSuccess : function(result) {
                            //onSuccess将在文件上传成功之后调用
//                            alert(JSON.stringify(result));
                            $("#files_string").val(JSON.stringify(result.data));
                            var strhtml = '已上传文件：';
                            $.each(result.data, function(i, field) {
                                btnId = 'btnSelectOrder_' + String(i);
                                strhtml += field.fileName + ",";
                            });
                            $("#lblFiles").empty().append(strhtml);
                            /*
                             {
                             type:'', // 用户选择了哪种文件类型 ，image（图片）、file（手机文件）、space（钉盘文件）
                             data: [
                             {
                             spaceId: "232323",
                             fileId: "DzzzzzzNqZY",
                             fileName: "审批流程.docx",
                             fileSize: 1024,
                             fileType: "docx"
                             },
                             {
                             spaceId: "232323",
                             fileId: "DzzzzzzNqZY",
                             fileName: "审批流程1.pdf",
                             fileSize: 1024,
                             fileType: "pdf"
                             },
                             {
                             spaceId: "232323",
                             fileId: "DzzzzzzNqZY",
                             fileName: "审批流程3.pptx",
                             fileSize: 1024,
                             fileType: "pptx"
                             }
                             ]

                             }
                             */
                        },
                        onFail : function(err) {}
                    });
                });
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
