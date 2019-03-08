@extends('app')

@section('title', '创建工程现场采购')

@section('main')

@can('approval_projectsitepurchase_create')
    {!! Form::open(array('url' => 'approval/projectsitepurchases/mstore', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
        @include('approval.projectsitepurchases._form',
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
                 var itemArray = new Array();

                $("div[name='container_item']").each(function(i){
                    var itemObject = new Object();
                    var container = $(this);

                    itemObject.item_id = container.find("input[name='item_id']").val();
                    itemObject.item_name = container.find("input[name='item_name']").val();
                    itemObject.item_spec = container.find("input[name='item_spec']").val();
//                    itemObject.productionoverview = container.find("textarea[name='productionoverview']").val();
//                    itemObject.tonnage = container.find("input[name='tonnage']").val();
//                    if (itemObject.tonnage == "")
//                        itemObject.tonnage = 0.0;
                    itemObject.unit = container.find("input[name='unit']").val();
                    itemObject.brand = container.find("input[name='brand']").val();
                    itemObject.quantity = container.find("input[name='quantity']").val();
                    if (itemObject.quantity == "")
                        itemObject.quantity = 0.0;
                    itemObject.unit_id = container.find("select[name='unit_id']").val();
                    itemObject.unit_name = container.find("select[name='unit_id']").find("option:selected").text();
                    itemObject.unitprice = container.find("input[name='unitprice']").val();
                    if (itemObject.unitprice == "")
                        itemObject.unitprice = 0.0;
                    itemObject.price = container.find("input[name='price']").val();


//                    var unitpriceArray = new Array();
//                    var pppaymentitemtypecontainer = container.find("div[name='pppaymentitemtypecontainer']");
//                    pppaymentitemtypecontainer.find("div[name='div_unitpriceitem']").each(function (i) {
//                        var unitpriceObject = new Object();
//                        var unitpriceitemcontainer = $(this);
//                        unitpriceObject.name = unitpriceitemcontainer.find("input[name='tonnage']").data("name");
//                        unitpriceObject.tonnage = unitpriceitemcontainer.find("input[name='tonnage']").val();
//                        if (unitpriceObject.tonnage == "")
//                            unitpriceObject.tonnage = 0.0;
//                        unitpriceObject.unitprice = unitpriceitemcontainer.find("input[name='unitprice']").val();
//                        unitpriceArray.push(unitpriceObject);
//                    });




                    itemArray.push(itemObject);

//                    alert(JSON.stringify(itemArray));
//                    return false;
//                    alert($("form#formMain").serialize());
                });
                $("#items_string").val(JSON.stringify(itemArray));

//                if ($("#sohead_id").val() != 7550)
//				{
//				    if ($("#issuedrawing_values").val() == "")
//					{
//                        $('#submitModal').modal('toggle');
//                        $("#dataDefine").empty().append('未选择下图单');
//                        return false;
//					}
//				}
				$("form#formMain").submit();

                 {{--$.post("{{ url('approval/mcitempurchase/weightvalid') }}", $("form#formMain").serialize(), function (data) {--}}
                     {{--if (data.code < 0)--}}
					 {{--{--}}
                         {{--$('#submitModal').modal('toggle');--}}
                         {{--$("#dataDefine").empty().append(data.msg);--}}
					 {{--}--}}
                     {{--else--}}
                         {{--$("form#formMain").submit();--}}
                 {{--});--}}
				 {{--return false;--}}
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
					$("#sohead_salesmanager").val(field.salesmanager);
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
                var itemHtml = '<div class="' + divName + '"><p class="bannerTitle">采购明细(' + String(item_num) + ')&nbsp;<button class="btn btn-sm" id="' + btnId + '" type="button">删除</button></p>\
                	<div name="container_item">\
						<div class="form-group">\
							{!! Form::label('item_name', '物品名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}\
							<div class="col-sm-10 col-xs-8">\
							\<input class="form-control" ="" data-toggle="modal" data-target="#selectItemModal" data-name="item_name_' + String(item_num) + '" data-num="' + String(item_num) + '" id="item_name_' + String(item_num) + '" name="item_name" type="text">\
							<input class="btn btn-sm" id="item_id_' + String(item_num) + '" name="item_id" type="hidden" value="0">\
                    </div>\
						</div>\
						<div class="form-group">\
							<label for="item_spec" class="col-xs-4 col-sm-2 control-label">规格型号:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" readonly="readonly" ="" name="item_spec" type="text" id="item_spec_' + String(item_num) + '">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="unit" class="col-xs-4 col-sm-2 control-label">单位:</label>\
							<div class="col-sm-10 col-xs-8">\
							    <input class="form-control" readonly="readonly" ="" id="unit_' + String(item_num) + '" name="unit" type="text">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="brand" class="col-xs-4 col-sm-2 control-label">品牌:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" placeholder="" ="" id="brand_' + String(item_num) + '" name="brand" type="text">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="quantity" class="col-xs-4 col-sm-2 control-label">数量:</label>\
							<div class="col-sm-8 col-xs-5">\
							<input class="form-control" name="quantity" type="text" id="quantity_' + String(item_num) + '">\
							</div>\
							<div class="col-sm-2 col-xs-3">{{ Form::select("unit_id", $unitList_hxold, null, ["class" => "form-control", "placeholder" => "--默认--"]) }}</div>\
						</div>\
						<div class="form-group">\
							<label for="unitprice" class="col-xs-4 col-sm-2 control-label">单价:</label>\
							<div class="col-sm-10 col-xs-8">\
							<input class="form-control" name="unitprice" type="text"  id="unitprice_' + String(item_num) + '">\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="price" class="col-xs-4 col-sm-2 control-label">金额（元）:</label>\
							<div class="col-sm-10 col-xs-8">\
							    <input class="form-control" ="" id="price_' + String(item_num) + '" name="price" type="text">\
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
                var productioncompany = $("#productioncompany").val();
//                $("#pppaymentitemtypecontainer_1" + String(num)).empty();
                var strhtml = '';
                var strhtml2 = '';
                var selecttype = $("#type_" + String(num));
                var selectarea = $("#area_" + String(num));

                if (selecttype.val() == "抛丸")
                {
                    @foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.抛丸') as $key => $value)
                        {{--alert('{!! $key !!}');--}}
                        strhtml2 += '<div class="form-group" name="div_unitpriceitem">';
                        strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\
                            <div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\
                            \</div>\
                            \<div class="col-sm-5 col-xs-4">';
                        if (productioncompany == "泰州分公司")
                            strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['泰州分公司'] !!}" readonly="readonly">';
                        else if (productioncompany == "胶州分公司")
                            strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['胶州分公司'] !!}" readonly="readonly">';
                        strhtml2 += '\</div>';
                        strhtml2 += '</div>';
                    @endforeach
                }
                else if (selecttype.val() == "油漆")
                {
                    @foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.油漆') as $key => $value)
                            {{--alert('{!! $key !!}');--}}
                        strhtml2 += '<div class="form-group" name="div_unitpriceitem">';
                        strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\
                            <div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\
                            \</div>\
                            \<div class="col-sm-5 col-xs-4">';
                        if (productioncompany == "泰州分公司")
                            strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['泰州分公司'] !!}" readonly="readonly">';
                        else if (productioncompany == "胶州分公司")
                            strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['胶州分公司'] !!}" readonly="readonly">';
                        strhtml2 += '\</div>';
                        strhtml2 += '</div>';
                    @endforeach
                }
                else if (selecttype.val() == "人工")
                {
                    @foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.人工') as $key => $value)
                            {{--alert('{!! $key !!}');--}}
                        strhtml2 += '<div class="form-group" name="div_unitpriceitem">';
                        strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\
                            <div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\
                            \</div>\
                            \<div class="col-sm-5 col-xs-4">';
                        if (productioncompany == "泰州分公司")
                            strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['泰州分公司'] !!}" readonly="readonly">';
                        else if (productioncompany == "胶州分公司")
                            strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['胶州分公司'] !!}" readonly="readonly">';
                        strhtml2 += '\</div>';
                        strhtml2 += '</div>';
                    @endforeach
                }
                else if (selecttype.val() == "铆焊")
                {
                    @foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.铆焊') as $key => $value)
                        var b = true;
                        if (selectarea.val() == "国内" && "{!! $key !!}" == "包装支架含漆")
                            b = false;
                        if (b && selectarea.val() == "国外" && "{!! $key !!}" == "包装支架")
                            b = false;
                        if (b)
                        {
                            strhtml2 += '<div class="form-group" name="div_unitpriceitem">';
                            strhtml2 += '<label for="paowan" class="col-xs-4 col-sm-2 control-label">{!! $key !!}:</label>\
                            <div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="{!! $key !!}">\
                            \</div>\
                            \<div class="col-sm-5 col-xs-4">';
                            if (productioncompany == "泰州分公司")
                                strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['泰州分公司'] !!}" readonly="readonly">';
                            else if (productioncompany == "胶州分公司")
                                strhtml2 +='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! $value['胶州分公司'] !!}" readonly="readonly">';
                            strhtml2 += '\</div>';
                            strhtml2 += '</div>';
                        }
                    @endforeach
                }


                if (selecttype.val() == "抛丸")
                {
                    strhtml += '<div class="form-group" name="div_unitpriceitem">\
                            <label for="paowan" class="col-xs-4 col-sm-2 control-label">抛丸:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="抛丸">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="{!! config('custom.dingtalk.approversettings.pppayment.pricedetail.抛丸.抛丸.泰州分公司') !!}" readonly="readonly">\
                            </div>\
                        </div>\
                        ';
                }
                else if (selecttype.val() == "油漆")
                {
                    strhtml += '<div class="form-group" name="div_unitpriceitem">\
                            <label for="hanjiegj" class="col-xs-4 col-sm-2 control-label">焊接钢架:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="hanjiegj_weight_' + String(num) + '" name="tonnage" type="text" data-name="焊接钢架">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="hanjiegj_unitprice_' + String(num) + '" name="unitprice" type="text" value="460" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="guanleict" class="col-xs-4 col-sm-2 control-label">罐类仓体:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="guanleict_weight_' + String(num) + '" name="tonnage" type="text" data-name="罐类仓体">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="guanleict_unitprice_' + String(num) + '" name="unitprice" type="text" value="360" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="buxiugg" class="col-xs-4 col-sm-2 control-label">不锈钢罐:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="buxiugg_weight_' + String(num) + '" name="tonnage" type="text" data-name="不锈钢罐">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="buxiugg_unitprice_' + String(num) + '" name="unitprice" type="text" value="130" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="luoshuangj" class="col-xs-4 col-sm-2 control-label">螺栓钢架:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="luoshuangj_weight_' + String(num) + '" name="tonnage" type="text"  data-name="螺栓钢架">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="luoshuangj_unitprice_' + String(num) + '" name="unitprice" type="text" value="500" readonly="readonly">\
                            </div>\
                        </div>\
                        ';
                }
                else if (selecttype.val() == "人工")
                {
                    strhtml += '<div class="form-group" name="div_unitpriceitem">\
                            <label for="rengong" class="col-xs-4 col-sm-2 control-label">人工:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="rengong_weight_' + String(num) + '" name="tonnage" type="text"   data-name="人工">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="rengong_unitprice_' + String(num) + '" name="unitprice" type="text" value="250" readonly="readonly">\
                            </div>\
                        </div>\
                        ';
                }
                else if (selecttype.val() == "铆焊")
                {
                    strhtml += '<div class="form-group" name="div_unitpriceitem">\
                            <label for="jinfengsaztt" class="col-xs-4 col-sm-2 control-label">风栅套筒:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="jinfengsaztt_weight_' + String(num) + '" name="tonnage" type="text" data-name="风栅套筒">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="jinfengsaztt_unitprice_' + String(num) + '" name="unitprice" type="text" value="1207" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="hanjiegj" class="col-xs-4 col-sm-2 control-label">焊接钢架:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="hanjiegj_weight_' + String(num) + '" name="tonnage" type="text" data-name="焊接钢架">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="hanjiegj_unitprice_' + String(num) + '" name="unitprice" type="text" value="812" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="huidouzxt" class="col-xs-4 col-sm-2 control-label">灰斗箱体:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="huidouzxt_weight_' + String(num) + '" name="tonnage" type="text" data-name="灰斗箱体">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="huidouzxt_unitprice_' + String(num) + '" name="unitprice" type="text" value="976" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="pingtaiptlg" class="col-xs-4 col-sm-2 control-label">平台爬梯:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="pingtaiptlg_weight_' + String(num) + '" name="tonnage" type="text" data-name="平台爬梯">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="pingtaiptlg_unitprice_' + String(num) + '" name="unitprice" type="text" value="985" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="shoutabt" class="col-xs-4 col-sm-2 control-label">收塔本体:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="shoutabt_weight_' + String(num) + '" name="tonnage" type="text" data-name="收塔本体">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="shoutabt_unitprice_' + String(num) + '" name="unitprice" type="text" value="976" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="woke" class="col-xs-4 col-sm-2 control-label">进风烟道:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="woke_weight_' + String(num) + '" name="tonnage" type="text" data-name="进风烟道">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="woke_unitprice_' + String(num) + '" name="unitprice" type="text" value="976" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="huadongzj" class="col-xs-4 col-sm-2 control-label">滑动支架:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="huadongzj_weight_' + String(num) + '" name="tonnage" type="text" data-name="滑动支架">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="huadongzj_unitprice_' + String(num) + '" name="unitprice" type="text" value="1136" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="buxiugg" class="col-xs-4 col-sm-2 control-label">不锈钢罐:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="buxiugg_weight_' + String(num) + '" name="tonnage" type="text" data-name="不锈钢罐">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="buxiugg_unitprice_' + String(num) + '" name="unitprice" type="text" value="1713" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="shangxiangt" class="col-xs-4 col-sm-2 control-label">上箱体:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="shangxiangt_weight_' + String(num) + '" name="tonnage" type="text" data-name="上箱体">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="shangxiangt_unitprice_' + String(num) + '" name="unitprice" type="text" value="1236" readonly="readonly">\
                            </div>\
                        </div>\
                        \<div class="form-group" name="div_unitpriceitem">\
                            <label for="luoshuangj" class="col-xs-4 col-sm-2 control-label">螺栓钢架:</label>\
                            <div class="col-sm-5 col-xs-4">\
                                <input class="form-control" placeholder="吨数" ="" id="luoshuangj_weight_' + String(num) + '" name="tonnage" type="text" data-name="螺栓钢架">\
                            </div>\
                            \<div class="col-sm-5 col-xs-4">\
                            <input class="form-control" placeholder="单价" ="" id="luoshuangj_unitprice_' + String(num) + '" name="unitprice" type="text" value="1050" readonly="readonly">\
                            </div>\
                        </div>\
                        ';
                }
                $("#pppaymentitemtypecontainer_" + String(num)).empty().append(strhtml2);

            }

			 $("#btnParseExcel").click(function() {
//                 $('#formUploadParseExcel').append($(this).parent().children());
//                 return false;
//                 $('#formUploadParseExcel').submit();
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
