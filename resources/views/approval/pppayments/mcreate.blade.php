@extends('app')

@section('title', '创建生产加工单结算付款单')

@section('main')

@can('approval_pppayment_create')
    {!! Form::open(array('url' => 'approval/pppayment/mstore', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
        @include('approval.pppayments._form',
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

<!-- issuedrawing selector -->
<div class="modal fade" id="selectIssueDrawingsModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">选择下发图纸审批单</h4>
			</div>
			<div class="modal-body">
				<div class="input-group">
					{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'keyDrawingchecker']) !!}
					<span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchDrawingchecker']) !!}
                   	</span>
				</div>
				{!! Form::hidden('name', null, ['id' => 'name']) !!}
				{!! Form::hidden('id', null, ['id' => 'id']) !!}
				{!! Form::hidden('supplierid', 0, ['id' => 'supplierid']) !!}
				{!! Form::hidden('poheadamount', 0, ['id' => 'poheadamount']) !!}
				{!! Form::hidden('num', null, ['id' => 'num']) !!}
				<p>
				<div class="list-group" id="listissuedrawings">

				</div>
				</p>
				<form id="formAccept">
					{{--{!! csrf_field() !!}--}}

				</form>
			</div>
			<div class="modal-footer">
				{!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
				{!! Form::button('确定', ['class' => 'btn btn-sm btn-primary', 'id' => 'btnok_selectissuedrawing']) !!}
			</div>
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

<!-- supplier selector -->
<div class="modal fade" id="selectSupplierModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">选择供应商</h4>
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
                                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '供应商名称', 'id' => 'keySupplier']) !!}
                                    <span class="input-group-btn">
                                           {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchSupplier']) !!}
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
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>

	<script type="text/javascript">
		jQuery(document).ready(function(e) {
            var item_num = 1;

			 $("#btnSubmit").click(function() {
                 $("#productioncompany").val($("#productioncompany_id").find("option:selected").text());
                 var itemArray = new Array();

                $("div[name='container_item']").each(function(i){
                    var itemObject = new Object();
                    var container = $(this);

                    itemObject.sohead_id = container.find("input[name='sohead_id']").val();
                    itemObject.project_name = container.find("input[name='project_name']").val();
                    itemObject.sohead_number = container.find("input[name='sohead_number']").val();
                    itemObject.productionoverview = container.find("textarea[name='productionoverview']").val();
                    //itemObject.tonnage = container.find("input[name='tonnage']").val();
                    //if (itemObject.tonnage == "")
                    itemObject.tonnage = 0.0;
                    itemObject.issuedrawing_numbers = container.find("input[name='issuedrawing_numbers']").val();
                    itemObject.issuedrawing_values = container.find("input[name='issuedrawing_values']").val();
                    itemObject.imagesname = container.find("input[name='imagesname']").val();
                    itemObject.imagesname_mobile = container.find("input[name='imagesname_mobile']").val();
//                    itemObject.unit_id = container.find("select[name='unit_id']").val();
//                    itemObject.unit_name = container.find("select[name='unit_id']").find("option:selected").text();
//                    itemObject.weight = container.find("input[name='weight']").val();
//                    if (itemObject.weight == "")
//                        itemObject.weight = 0.0;

                    itemObject.area = container.find("select[name='area']").val();
                    itemObject.type = container.find("select[name='type']").val();
                    itemObject.unitprice_inputname = container.find("input[name='unitprice_inputname']").val();
                    itemObject.totalprice_inputname = container.find("input[name='totalprice_inputname']").val();


                    var unitpriceArray = new Array();
                    var pppaymentitemtypecontainer = container.find("div[name='pppaymentitemtypecontainer']");
                    pppaymentitemtypecontainer.find("div[name='div_unitpriceitem']").each(function (i) {
                        var unitpriceObject = new Object();
                        var unitpriceitemcontainer = $(this);
                        unitpriceObject.name = unitpriceitemcontainer.find("input[name='tonnage']").data("name");
                        unitpriceObject.tonnage = unitpriceitemcontainer.find("input[name='tonnage']").val();
                        if (unitpriceObject.tonnage == "")
                            unitpriceObject.tonnage = 0.0;
                        itemObject.tonnage= Number(unitpriceObject.tonnage)  + Number(itemObject.tonnage) ;
                        unitpriceObject.unitprice = unitpriceitemcontainer.find("input[name='unitprice']").val();
                        unitpriceArray.push(unitpriceObject);
                    });

                    itemObject.unitprice_array = unitpriceArray;

                    itemArray.push(itemObject);

                   //alert(JSON.stringify(itemArray));
//                    return false;
//                    alert($("form#formMain").serialize());
                });

                $("#items_string").val(JSON.stringify(itemArray));

//                alert($("#sohead_id").val());
                if ($("#sohead_id").val() != 7550)
				{
				    if ($("#issuedrawing_values").val() == "")
					{
                        $('#submitModal').modal('toggle');
                        $("#dataDefine").empty().append('未选择下图单');
                        return false;
					}
				}

				$("form#formMain").submit();

			 });


			$("#btnSubmitContinue").click(function() {
				$("form#formMain").submit();
			});


			$('#selectIssueDrawingsModal').on('show.bs.modal', function (e) {
				$("#listissuedrawings").empty();

				var text = $(e.relatedTarget);
				var modal = $(this);

				modal.find('#name').val(text.data('name'));
				modal.find('#id').val(text.data('id'));
				modal.find('#supplierid').val(text.data('supplierid'));
				modal.find('#poheadamount').val(text.data('poheadamount'));
                modal.find('#num').val(text.data('num'));
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
						$("#listissuedrawings").empty().append(strhtml);

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
					$('#selectIssueDrawingsModal').modal('toggle');
//					$("#" + $("#selectIssueDrawingsModal").find('#name').val()).val(number);
//					$("#" + $("#selectIssueDrawingsModal").find('#id').val()).val(salesorderid);
//					$("#" + $("#selectIssueDrawingsModal").find('#poheadamount').val()).val(amount);
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
//					$("#supplier_bank").val(field.bank);
//					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
//					$("#vendbank_id").val(field.vendbank_id);
//					$("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
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
                            btnId = 'btnSelectCustomer_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listsuppliers").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectCustomer_' + String(i);
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
                    $("#" + $("#selectSupplierModal").find('#name').val()).val(name);
                    $("#" + $("#selectSupplierModal").find('#id').val()).val(supplierid);
                    $("#supplier_bank").val(field.bank);
                    $("#supplier_bankaccountnumber").val(field.bankaccountnumber);
                    $("#vendbank_id").val(field.vendbank_id);
                    $("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
                });
            }

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
                    // $("#" + $("#selectSupplierModal").find('#name').val()).val(name);
                    // $("#" + $("#selectSupplierModal").find('#id').val()).val(supplierid);
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
                var itemHtml = '<div class="' + divName + '"><p class="bannerTitle">明细(' + String(item_num) + ')&nbsp;<button class="btn btn-sm" id="' + btnId + '" type="button">删除</button></p>\
                	<div name="container_item">\
						<div class="form-group">\
							{!! Form::label('project_name', '所属项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}\
							<div class="col-sm-10 col-xs-8">\
							\<input class="form-control" ="" data-toggle="modal" data-target="#selectProjectModal" data-name="project_name_' + String(item_num) + '" data-id="sohead_id_' + String(item_num) + '" data-num="' + String(item_num) + '" id="project_name_' + String(item_num) + '" name="project_name" type="text">\
							<input class="btn btn-sm" id="sohead_id_' + String(item_num) + '" name="sohead_id" type="hidden" value="0">\
                    </div>\
						</div>\
						<div class="form-group">\
							<label for="sohead_number" class="col-xs-4 col-sm-2 control-label">项目编号:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" readonly="readonly" ="" name="sohead_number" type="text" id="sohead_number_' + String(item_num) + '">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="productionoverview" class="col-xs-4 col-sm-2 control-label">制作概述:</label>\
							<div class="col-sm-10 col-xs-8">\
							<textarea class="form-control" ="" rows="3" id="productionoverview_' + String(item_num) + '" name="productionoverview" cols="50"></textarea>\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="issuedrawing_numbers" class="col-xs-4 col-sm-2 control-label">下发图纸审批单号:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" placeholder="--点击选择--" readonly="readonly" ="" data-toggle="modal" data-target="#selectIssueDrawingsModal" data-name="issuedrawing_numbers_' + String(item_num) + '" data-id="issuedrawing_values_' + String(item_num) + '" data-num="' + String(item_num) + '" id="issuedrawing_numbers_' + String(item_num) + '" name="issuedrawing_numbers" type="text">\
							\<input class="btn btn-sm" id="issuedrawing_values_' + String(item_num) + '" name="issuedrawing_values" type="hidden">\
							</div>\
						</div>\
						\<div class="form-group">\
							<label for="area" class="col-xs-4 col-sm-2 control-label">地区:</label>\
							<div class="col-sm-10 col-xs-8">\
							    <select class="form-control" ="" id="area_' + String(item_num) + '" onchange="selectTypeChange(this.dataset.num)" data-num="' + String(item_num) + '" name="area"><option selected="selected" value="">--请选择--</option><option value="国内">国内</option><option value="国外">国外</option></select>\
							</div>\
						</div>\
						\<div class="form-group">\
							<label for="type" class="col-xs-4 col-sm-2 control-label">类型:</label>\
							<div class="col-sm-10 col-xs-8">\
							    <select class="form-control" ="" id="type_' + String(item_num) + '" onchange="selectTypeChange(this.dataset.num)" data-num="' + String(item_num) + '" name="type"><option selected="selected" value="">--请选择--</option><option value="抛丸">抛丸</option><option value="油漆">油漆</option><option value="人工">人工</option><option value="铆焊">铆焊</option><option value="外协油漆">外协油漆</option><option value="板拼型钢">板拼型钢</option></select>\
							    <input class="btn btn-sm" id="unitprice_inputname_' + String(item_num) + '" name="unitprice_inputname" type="hidden" value="unitprice_inputname_' + String(item_num) + '">\
							    <input class="btn btn-sm" name="totalprice_inputname" type="hidden" value="totalprice_inputname_' + String(item_num) + '">\
							</div>\
						</div>\
						\<div id="pppaymentitemtypecontainer_' + String(item_num) + '" name="pppaymentitemtypecontainer"></div>\
                    <div class="form-group">\
							<label for="images" class="col-xs-4 col-sm-2 control-label">上传质检签收单:</label>\
							<div class="col-sm-10 col-xs-8">\
							\<div class="row" id="previewimage_' + String(item_num) + '"></div>\
							\<input class="btn btn-sm" id="imagesname_' + String(item_num) + '" name="imagesname" type="hidden" value="images_' + String(item_num) + '">\
							\@if (Agent::isDesktop())
							<input multiple="multiple" id="images_' + String(item_num) + '" name="images_' + String(item_num) + '[]" type="file">\
							\@else
                                <button class="btn btn-sm" id="btnSelectImage" value="1" onclick="selectImage_Mobile(' + String(item_num) + ')" type="button">+</button>\
                        \<input class="btn btn-sm" id="imagesname_mobile_' + String(item_num) + '" name="imagesname_mobile" type="hidden">\
                        @endif
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

            selectTypeChange = function (num) {
                var productioncompany = $("#productioncompany_id").find("option:selected").text();
//                var productioncompany = $("#productioncompany").val();
                var strhtml = '';
                var strhtml2 = '';
                var selecttype = $("#type_" + String(num));
                var selectarea = $("#area_" + String(num));

                $.post("{{ url('approval/pppayment/getpricedetailhtml') }}", { productioncompany: productioncompany, selectarea: selectarea.val(), selecttype: selecttype.val() }, function (data) {
                    //
                    $("#pppaymentitemtypecontainer_" + String(num)).empty().append(data);
                });
                return;

                {{--if (selecttype.val() == "抛丸")--}}
                {{--{--}}
                    {{--@foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.抛丸') as $key => $value)--}}
                        {{--strhtml2 += '<div class="form-group" name="div_unitpriceitem">';--}}
                        {{--strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\--}}
                            {{--<div class="col-sm-5 col-xs-4">\--}}
                            {{--<input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\--}}
                            {{--\</div>\--}}
                            {{--\<div class="col-sm-5 col-xs-4">';--}}
                        {{--if (productioncompany == "泰州分公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['泰州分公司']['国内'] !!}" readonly="readonly">';--}}
                        {{--else if (productioncompany == "胶州分公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['胶州分公司']['国内'] !!}" readonly="readonly">';--}}
                        {{--else if (productioncompany == "宣城分公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! isset($value['宣城分公司']) ? $value['宣城分公司'] : '0' !!}" readonly="readonly">';--}}
                        {{--else if (productioncompany == "许昌子公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! isset($value['许昌子公司']) ? $value['许昌子公司'] : '0' !!}" readonly="readonly">';--}}
                        {{--strhtml2 += '\</div>';--}}
                        {{--strhtml2 += '</div>';--}}
                    {{--@endforeach--}}
                {{--}--}}
                {{--else if (selecttype.val() == "油漆")--}}
                {{--{--}}
                    {{--@foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.油漆') as $key => $value)--}}
                        {{--strhtml2 += '<div class="form-group" name="div_unitpriceitem">';--}}
                        {{--strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\--}}
                            {{--<div class="col-sm-5 col-xs-4">\--}}
                            {{--<input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\--}}
                            {{--\</div>\--}}
                            {{--\<div class="col-sm-5 col-xs-4">';--}}
                        {{--var value = '';--}}
                        {{--if (selectarea.val() == "国外")--}}
                        {{--{--}}
                            {{--@if (isset($value['国外']))--}}
                                {{--value = "{!! $value['国外'] !!}";--}}
                            {{--@endif--}}
                        {{--}--}}
                        {{--if (value == "")--}}
                        {{--{--}}
                            {{--if (productioncompany == "泰州分公司")--}}
                                {{--value = "{!! $value['泰州分公司'] !!}";--}}
                            {{--else if (productioncompany == "胶州分公司")--}}
                                {{--value = "{!! $value['胶州分公司'] !!}"--}}
                            {{--else if (productioncompany == "宣城分公司")--}}
                                {{--value = "{!! isset($value['宣城分公司']) ? $value['宣城分公司'] : '0' !!}"--}}
                            {{--else if (productioncompany == "许昌子公司")--}}
                                {{--value = "{!! isset($value['许昌子公司']) ? $value['许昌子公司'] : '0' !!}"--}}
                        {{--}--}}
                        {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="' + value + '" readonly="readonly">';--}}
                        {{--strhtml2 += '\</div>';--}}
                        {{--strhtml2 += '</div>';--}}
                    {{--@endforeach--}}
                {{--}--}}
                {{--else if (selecttype.val() == "人工")--}}
                {{--{--}}
                    {{--@foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.人工') as $key => $value)--}}
                            {{--alert('{!! $key !!}');--}}
                        {{--strhtml2 += '<div class="form-group" name="div_unitpriceitem">';--}}
                        {{--strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\--}}
                            {{--<div class="col-sm-5 col-xs-4">\--}}
                            {{--<input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\--}}
                            {{--\</div>\--}}
                            {{--\<div class="col-sm-5 col-xs-4">';--}}
                        {{--if (productioncompany == "泰州分公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['泰州分公司'] !!}" readonly="readonly">';--}}
                        {{--else if (productioncompany == "胶州分公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['胶州分公司'] !!}" readonly="readonly">';--}}
                        {{--else if (productioncompany == "宣城分公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! isset($value['宣城分公司']) ? $value['宣城分公司'] : '0' !!}" readonly="readonly">';--}}
                        {{--else if (productioncompany == "许昌子公司")--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! isset($value['许昌子公司']) ? $value['许昌子公司'] : '0' !!}" readonly="readonly">';--}}
                        {{--strhtml2 += '\</div>';--}}
                        {{--strhtml2 += '</div>';--}}
                    {{--@endforeach--}}
                {{--}--}}
                {{--else if (selecttype.val() == "铆焊")--}}
                {{--{--}}
                    {{--@foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.铆焊') as $key => $value)--}}
                        {{--var b = true;--}}
                        {{--if (selectarea.val() == "国内" && "{!! $key !!}" == "包装支架含漆")--}}
                            {{--b = false;--}}
                        {{--if (b && selectarea.val() == "国外" && "{!! $key !!}" == "包装支架")--}}
                            {{--b = false;--}}
                        {{--if (b)--}}
                        {{--{--}}
                            {{--strhtml2 += '<div class="form-group" name="div_unitpriceitem">';--}}
                            {{--strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\--}}
                            {{--<div class="col-sm-5 col-xs-4">\--}}
                            {{--<input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\--}}
                            {{--\</div>\--}}
                            {{--\<div class="col-sm-5 col-xs-4">';--}}

                            {{--var value = '';--}}
                            {{--if (selectarea.val() == "国外")--}}
                            {{--{--}}
                                {{--@if (isset($value['国外']))--}}
                                    {{--value = "{!! $value['国外'] !!}";--}}
                                {{--@endif--}}
                            {{--}--}}
                            {{--if (value == "")--}}
                            {{--{--}}
                                {{--if (productioncompany == "泰州分公司")--}}
                                    {{--value = "{!! $value['泰州分公司'] !!}";--}}
                                {{--else if (productioncompany == "胶州分公司")--}}
                                    {{--value = "{!! $value['胶州分公司'] !!}"--}}
                                {{--else if (productioncompany == "宣城分公司")--}}
                                    {{--value = "{!! isset($value['宣城分公司']) ? $value['宣城分公司'] : '0' !!}"--}}
                                {{--else if (productioncompany == "许昌子公司")--}}
                                    {{--value = "{!! isset($value['许昌子公司']) ? $value['许昌子公司'] : '0' !!}"--}}
                            {{--}--}}
                            {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="' + value + '" readonly="readonly">';--}}
                            {{--strhtml2 += '\</div>';--}}
                            {{--strhtml2 += '</div>';--}}
                        {{--}--}}
                    {{--@endforeach--}}
                {{--}--}}
                {{--else if (selecttype.val() == "外协油漆")--}}
                {{--{--}}
                    {{--@foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.外协油漆') as $key => $value)--}}
                            {{--alert('{!! $key !!}');--}}
                        {{--strhtml2 += '<div class="form-group" name="div_unitpriceitem">';--}}
                    {{--strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\--}}
                            {{--<div class="col-sm-5 col-xs-4">\--}}
                            {{--<input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\--}}
                            {{--\</div>\--}}
                            {{--\<div class="col-sm-5 col-xs-4">';--}}
                    {{--var value = '';--}}
                    {{--if (selectarea.val() == "国外")--}}
                    {{--{--}}
                        {{--@if (isset($value['国外']))--}}
                            {{--value = "{!! $value['国外'] !!}";--}}
                        {{--@endif--}}
                    {{--}--}}
                    {{--if (value == "")--}}
                    {{--{--}}
                        {{--if (productioncompany == "泰州分公司")--}}
                            {{--value = "{!! $value['泰州分公司'] !!}";--}}
                        {{--else if (productioncompany == "胶州分公司")--}}
                            {{--value = "{!! $value['胶州分公司'] !!}"--}}
                        {{--else if (productioncompany == "宣城分公司")--}}
                            {{--value = "{!! isset($value['宣城分公司']) ? $value['宣城分公司'] : '0' !!}"--}}
                        {{--else if (productioncompany == "许昌子公司")--}}
                            {{--value = "{!! isset($value['许昌子公司']) ? $value['许昌子公司'] : '0' !!}"--}}
                    {{--}--}}
                    {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="' + value + '" readonly="readonly">';--}}
                        {{--strhtml2 += '\</div>';--}}
                    {{--strhtml2 += '</div>';--}}
                    {{--@endforeach--}}
                {{--}--}}
                {{--else if (selecttype.val() == "板拼型钢")--}}
                {{--{--}}
                    {{--@foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.板拼型钢') as $key => $value)--}}
                        {{--strhtml2 += '<div class="form-group" name="div_unitpriceitem">';--}}
                    {{--strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\--}}
                            {{--<div class="col-sm-5 col-xs-4">\--}}
                            {{--<input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\--}}
                            {{--\</div>\--}}
                            {{--\<div class="col-sm-5 col-xs-4">';--}}
                    {{--var value = '';--}}
                    {{--if (selectarea.val() == "国外")--}}
                    {{--{--}}
                        {{--@if (isset($value['国外']))--}}
                            {{--value = "{!! $value['国外'] !!}";--}}
                        {{--@endif--}}
                    {{--}--}}
                    {{--if (value == "")--}}
                    {{--{--}}
                        {{--if (productioncompany == "泰州分公司")--}}
                            {{--value = "{!! $value['泰州分公司'] !!}";--}}
                        {{--else if (productioncompany == "胶州分公司")--}}
                            {{--value = "{!! $value['胶州分公司'] !!}"--}}
                        {{--else if (productioncompany == "宣城分公司")--}}
                            {{--value = "{!! isset($value['宣城分公司']) ? $value['宣城分公司'] : '0' !!}"--}}
                        {{--else if (productioncompany == "许昌子公司")--}}
                            {{--value = "{!! isset($value['许昌子公司']) ? $value['许昌子公司'] : '0' !!}"--}}
                    {{--}--}}
                    {{--strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="' + value + '" readonly="readonly">';--}}
                        {{--strhtml2 += '\</div>';--}}
                    {{--strhtml2 += '</div>';--}}
                    {{--@endforeach--}}
                {{--}--}}

                {{--$("#pppaymentitemtypecontainer_" + String(num)).empty().append(strhtml2);--}}
            }

			 $("#btnParseExcel").click(function() {
                 var formData = new FormData();
                 formData.append('items_excelfile', $('#items_excelfile')[0].files[0]);
                 $.ajax({
                     type: "POST",
                     url: "{!! url('approval/mcitempurchase/uploadparseexcel') !!}",
					 data: formData,
                     processData: false,
                     contentType: false,
                     success: function(result) {
                         $("#items_string2").val(JSON.stringify(result));
//                         alert(JSON.stringify(result));
                         var strhtml = '';
                         strhtml += '<table class="table table-striped table-hover table-condensed">';
                         if (result.length > 0)
						 {
                             strhtml += '<thead><tr><th>物品名称</th><th>型号</th><th>单位</th><th>尺寸</th><th>数量</th><th>重量</th><th>备注</th></tr></thead>';
						     strhtml += '<tbody>';
						 }

                         $.each(result, function(i, field) {
//                             alert(field.item_name);
                             strhtml += '<tr><td>' + field.item_name + '</td><td>' + field.item_spec + '</td><td>' + field.unit_name + '</td><td>' + field.size + '</td><td>' + field.quantity + '</td><td>' + field.weight + '</td><td>' + field.remark + '</td></tr>'
//                             $.each(field, function(j, item) {
//                                 if (j == 'item_name')
//                                     alert(item);
////                                 btnId = 'btnSelectDrawingchecker_' + String(i);
////                                 strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
//                             });
//                             btnId = 'btnSelectDrawingchecker_' + String(i);
//                             strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
                         });
                         if (result.length > 0)
                             strhtml += '</tbody>';
                         strhtml += '</table>';
                         if (strhtml == '')
                             strhtml = '无记录。';
                         $("#items_excel").empty().append(strhtml);

//                         $.each(result.data, function(i, field) {
//                             btnId = 'btnSelectDrawingchecker_' + String(i);
//                             addBtnClickEvent(btnId, field);
//                         });
                     },
                     error: function(xhr, ajaxOptions, thrownError) {
                         alert('error');
                     }
                 });
			 });

			dd.config({
			    agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
			    corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
			    timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
			    nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
			    signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
			    jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile'] // 必填，需要使用的jsapi列表
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
//				$("#btnSelectImage").click(function() {
//				    var num = $(this).val();
////                    alert($(this).val());
//					dd.biz.util.uploadImage({
//						multiple: true,
//						max: 9,
//						onSuccess: function(result) {
//							var images = result;	// result.split(',');
//							var imageHtml = '';
//							for (var i in images) {
//								imageHtml += '<div class="col-xs-6 col-md-3">';
//								imageHtml += '<div class="thumbnail">';
//								imageHtml += '<img src=' + images[i] + ' />';
//								//imageHtml += '<input name="image_' + String(i) + '" value=' + images[i] + ' type="hidden">';
//								imageHtml += '</div>';
//								imageHtml += '</div>';
//							}
////                            alert($(this).val());
//                            $("#imagesname_mobile_" + String(num)).val(result);
//							$("#previewimage_" + String(num)).empty().append(imageHtml);
//						},
//						onFail: function(err) {
//							alert('select image failed: ' + JSON.stringify(err));
//						}
//					});
//				});

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
                }

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
