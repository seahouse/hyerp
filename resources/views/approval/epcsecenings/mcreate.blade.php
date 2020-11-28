@extends('app')

@section('title', 'EPC-安装队现场增补')

@section('main')


@can('approval_epcsecening_create')
    {!! Form::open(array('url' => 'approval/epcsecening/mstore', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
        @include('approval.epcsecenings._form',
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

<div class="modal fade" id="selectPoheadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择安装合同ERP编号</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    {!! Form::text('keyPohead', null, ['class' => 'form-control', 'placeholder' => '项目编号、项目名称', 'id' => 'keyPohead']) !!}
                    <span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchPohead']) !!}
                   	</span>
                </div>
                {!! Form::hidden('name', null, ['id' => 'name']) !!}
                {!! Form::hidden('id', null, ['id' => 'id']) !!}
                {!! Form::hidden('num', null, ['id' => 'num']) !!}
                <p>
                <div class="list-group" id="listpohead">

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

<div class="modal fade" id="selectApprovalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择关联相关审批单</h4>
            </div>
            <div class="modal-body">
                <div class="input-group" style="width:100%;">
                    <div class='col-xs-4 col-sm-4' style="padding:5px;">
                        {!! Form::select('type', array('customerdeduction' => '供应商扣款'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', 'id' => 'approvaltype']) !!}
                    </div>
                    <div class='col-xs-6 col-sm-6' style="padding:5px;">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '审批单号', 'id' => 'keyProjectpurchase']) !!}
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
	{{--<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>--}}
    <script src="https://g.alicdn.com/dingding/dingtalk-jsapi/2.7.13/dingtalk.open.js"></script>

	<script type="text/javascript">
		jQuery(document).ready(function(e) {
            var item_num = 1;
            var materialContent = $('#material_detail div[name="container_item"]').html();
            var humandayContent = $('#humanday_detail div[name="container_item"]').html();
            var craneContent = $('#crane_detail div[name="container_item"]').html();
            
            function genID () {
                return (new Date()).getTime() + '' + Math.round(Math.random() * 1000);
            }

            function resetOrder(root) {
                var num = 2;
                root.find('.moreOrder').each(function(){
                    $(this).html(num + '');
                    num ++;
                });
            }

            function recheckAddBtn(root) {
                var rootId = root.attr('id');
                var limit = 2;
                if (rootId == 'material_detail') {
                    limit = 15;
                }
                
                var size = root.find('div[name="container_item"]').size();
                if (size >= limit) {
                    root.find('.addMore').attr('lock', '1').css('color', 'gray');
                }
                else {
                    root.find('.addMore').attr('lock', '0').css('color', 'rgb(71,178,252)');
                }
                
            }

            $('.addMore').click(function() {
                if ($(this).attr('lock') == '1') {
                    return;
                }

                var parent = $(this).parent();
                var parentId = parent.attr('id');
                var moreDiv = parent.find('.moreDiv');
                
                switch(parentId) {
                    case 'material_detail':
                        var id = genID();
                        moreDiv.append('<p class="bannerTitle">增补所用材料部分（明细<15项）(<span class="moreOrder"></span>)&nbsp;<button class="btn btn-sm deleteMore" type="button">删除</button></p>');
                        moreDiv.append('<div id="container_item_' + id + '" name="container_item">' + materialContent + '</div>');
                        var container = moreDiv.find('#container_item_' + id);
                        container.find('#item_name_1').attr('data-num', id).attr('id', 'item_name_' + id);
                        container.find('#item_id_1').attr('id', 'item_id_' + id);
                        container.find('#item_spec_1').attr('id', 'item_spec_' + id);
                        container.find('#item_type_1').attr('id', 'item_type_' + id);
                        container.find('#unit_1').attr('id', 'unit_' + id);

                        moreDiv.find('.deleteMore').bind("click", function() {
                            var current = $(this).parent();
                            current.next().remove();
                            current.remove();
                            resetOrder(moreDiv);
                            recheckAddBtn(parent);
                        });
                        resetOrder(moreDiv);
                        recheckAddBtn(parent);
                        break;
                    case 'humanday_detail':
                        moreDiv.append('<p class="bannerTitle">增补所用人工部分（明细不大于2项）(<span class="moreOrder"></span>)&nbsp;<button class="btn btn-sm deleteMore" type="button">删除</button></p>');
                        moreDiv.append('<div name="container_item">' + humandayContent + '</div>');
                        moreDiv.find('.deleteMore').bind("click", function() {
                            var current = $(this).parent();
                            current.next().remove();
                            current.remove();
                            resetOrder(moreDiv);
                            recheckAddBtn(parent);
                        });
                        resetOrder(moreDiv);
                        recheckAddBtn(parent);
                        break;
                    case 'crane_detail':
                        moreDiv.append('<p class="bannerTitle">增补所用吊机台班（明细不大于2项）(<span class="moreOrder"></span>)&nbsp;<button class="btn btn-sm deleteMore" type="button">删除</button></p>');
                        moreDiv.append('<div name="container_item">' + craneContent + '</div>');
                        moreDiv.find('.deleteMore').bind("click", function() {
                            var current = $(this).parent();
                            current.next().remove();
                            current.remove();
                            resetOrder(moreDiv);
                            recheckAddBtn(parent);
                        });
                        resetOrder(moreDiv);
                        recheckAddBtn(parent);
                        break;
                }
            });

			true && $("#btnSubmit").click(function() {
                //  var itemArray = new Array();

                var arr = [];
                $('#material_detail div[name="container_item"]').each(function(){
                    arr.push({
                        material_type : $(this).find('select[name="material_type"]').val(),
                        item_name : $(this).find('input[name="item_name"]').val(),
                        item_id : $(this).find('input[name="item_id"]').val(),
                        item_spec : $(this).find('input[name="item_spec"]').val(),
                        unit : $(this).find('input[name="unit"]').val(),
                        quantity : $(this).find('input[name="quantity"]').val(),
                        unitprice : $(this).find('input[name="unitprice"]').val(),
                        remark : $(this).find('textarea[name="remark"]').val()
                    });
                });
                $("#items_string").val(JSON.stringify(arr));

                arr = [];
                $('#humanday_detail div[name="container_item"]').each(function(){
                    arr.push({
                        humandays_type : $(this).find('input[name="humandays_type"]').val(),
                        humandays : $(this).find('input[name="humandays"]').val(),
                        humandays_unitprice : $(this).find('input[name="humandays_unitprice"]').val(),
                        remark : $(this).find('textarea[name="remark"]').val()
                    });
                });
                $("#items_string_humanday").val(JSON.stringify(arr));

                arr = [];
                $('#crane_detail div[name="container_item"]').each(function(){
                    arr.push({
                        crane_type : $(this).find('input[name="crane_type"]').val(),
                        number : $(this).find('input[name="number"]').val(),
                        unitprice : $(this).find('input[name="unitprice"]').val()
                    });
                });
                $("#items_string_crane").val(JSON.stringify(arr));


            



//                  $("div[name='container_item']").each(function(i){
//                      var itemObject = new Object();
//                      var container = $(this);

//                      itemObject.name = container.find("input[name='cabinet_name']").val();
//                      itemObject.quantity = container.find("input[name='cabinet_quantity']").val();

// //
// //                     itemObject.unitprice_array = unitpriceArray;

//                      itemArray.push(itemObject);

// //                    alert(JSON.stringify(itemArray));
// //                    return false;
// //                    alert($("form#formMain").serialize());
//                  });
//                  $("#items_string").val(JSON.stringify(itemArray));

//                 //  var tonnagedetailArray = new Array();
//                 //  var tonnagedetailcontainer = $("#tonnagedetailcontainer");
//                 //  tonnagedetailcontainer.find("div[name='div_unitpriceitem']").each(function (i) {
//                 //      var tonnagedetailObject = new Object();
//                 //      var unitpriceitemcontainer = $(this);
//                 //      tonnagedetailObject.name = unitpriceitemcontainer.find("input[name='tonnage']").data("name");
//                 //      tonnagedetailObject.tonnage = unitpriceitemcontainer.find("input[name='tonnage']").val();
//                 //      if (tonnagedetailObject.tonnage == "")
//                 //          tonnagedetailObject.tonnage = 0.0;
//                 //      tonnagedetailObject.unitprice = 0.0;
//                 //      tonnagedetailArray.push(tonnagedetailObject);
//                 //  });
//                 //  $("#tonnagedetails_string").val(JSON.stringify(tonnagedetailArray));

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
                    $("#sohead_number").val(field.number);
                    $("#sohead_salesmanager").val(field.salesmanager);
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
                    $("#supplier_name").val(field.name);
                    $("#supplier_id").val(field.id);
                });
            }

            $('#selectPoheadModal').on('show.bs.modal', function (e) {
                $("#listpohead").empty();

                var target = $(e.relatedTarget);
                // alert(text.data('id'));

                var modal = $(this);
                modal.find('#name').val(target.data('name'));
                modal.find('#id').val(target.data('id'));
                modal.find('#num').val(target.data('num'));
                // alert(modal.find('#id').val());

            });

            $("#btnSearchPohead").click(function() {
                if ($("#keyPohead").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/purchase/purchaseorders/getitemsbyorderkey_simple/') !!}" + "/" + $("#keyPohead").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectPohead_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.number + "</h4><p>" + field.descrip + "</p></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listpohead").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectPohead_' + String(i);
                            addBtnClickEventPohead(btnId, field.id, field.number, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventPohead(btnId, soheadid, name, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectPoheadModal').modal('toggle');
                    $("#pohead_number").val(field.number);
                    $("#pohead_id").val(field.id);
                    $("#pohead_supplier_name").val(field.supplier_name);
                    var ticketedpercent = 0;
                    var paidpercent = 0;
                    if (field.amount > 0)
                    {
                        ticketedpercent = field.amount_ticketed / field.amount * 100.0;
                        paidpercent = field.amount_paid / field.amount * 100.0;
                    }
                    $("#ticketedpercent").val(ticketedpercent);
                    $("#paidpercent").val(paidpercent);
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
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.goods_name + "(" + field.goods_spec + ")</h4><h5>" + field.goods_old_name + "</h5></button>"
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
                    $("#item_type_" + $("#selectItemModal").find('#num').val()).val(field.type_name);
                    $("#unit_" +  + $("#selectItemModal").find('#num').val()).val(field.goods_unit_name);
                });
            }

            $('#selectApprovalModal').on('show.bs.modal', function (e) {
                $("#listApproval").empty();
                $("#approvaltype").val('');
                $("#keyProjectpurchase").val('');

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

                if ($("#keyProjectpurchase").val() == "") {
                    alert('请输入关键字');
                    return;
                }

                var requestUrl = 'approval/getdtitemsbykey';
                $.ajax({
                    type: "GET",
                    url: "{!! url('" + requestUrl + "') !!}" + "?type=" + $("#approvaltype").val() + "&key=" + $("#keyProjectpurchase").val(),
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

                        addBtnClickEventProjectpurchase2('btnSelectProjectpurchase_0', result);
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventProjectpurchase2(btnId, field)
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

            false && $("#btnAddItem").click(function() {
                item_num++;
                var btnId = 'btnDeleteItem_' + String(item_num);
                var divName = 'divClassItem_' + String(item_num);
                var itemHtml = '<div class="' + divName + '"><p class="bannerTitle">增补所用材料部分（明细<15项）(' + String(item_num) + ')&nbsp;<button class="btn btn-sm" id="' + btnId + '" type="button">删除</button></p>\
                	<div name="container_item">\
						<div class="form-group">\
							<label for="material_type" class="col-xs-4 col-sm-2 control-label">材料类别:</label>\
							<div class="col-sm-10 col-xs-8">\
							{!! Form::select('material_type', array('不锈钢管材' => '不锈钢管材', '不锈钢板材' => '不锈钢板材', '钢材型材' => '钢材型材', '钢材板材' => '钢材板材', '钢材管材' => '钢材管材',
                            '保温材料' => '保温材料', '电气材料' => '电气材料', '安装消耗材料' => '安装消耗材料', '管材配件' => '管材配件', '防腐材料' => '防腐材料',
                            '劳保用品' => '劳保用品', '施工期间甲方收取的费用' => '施工期间甲方收取的费用', '其他类别' => '其他类别'), null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}\
							</div>\
						</div>\
						<div class="form-group">\
							<label for="item_name" class="col-xs-4 col-sm-2 control-label">物品名称:</label>\
							<div class="col-sm-10 col-xs-8">\
							{!! Form::text('item_name', null, ['class' => 'form-control', 'data-toggle' => 'modal', 'data-target' => '#selectItemModal', 'data-num' => '1']) !!}\
							</div>\
						</div>\
                </div>\
                </div>';
                $("#itemMore").append(itemHtml);
                addBtnDeleteItemClickEvent(btnId, divName);
            });

            function genMaterialDetailItem () {

            }
            function genHumanDayItem () {}
            function genCraneDetailItem () {}



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

            selectAdditionalReasonChange = function () {
                var selectvalue = $("#additional_reason").val();
//                alert(selectvalue);

                $("#div_short_additional_reason").attr("style", "display:none;");
                $("#div_design_change_sheet").attr("style", "display:none;");
                $("#div_installworksheet").attr("style", "display:none;");
                $("#div_drawing_additional_reason").attr("style", "display:none;");
                $("#div_extra_additional_reason").attr("style", "display:none;");
                $("#div_huaxingworksheet").attr("style", "display:none;");
                $("#div_owner_additional_reason").attr("style", "display:none;");
                $("#div_owner_additional_reasonalreason").attr("style", "display:none;");
                $("#div_coordinate_additional_reason").attr("style", "display:none;");

                if (selectvalue == "短缺增补") {
                    $("#div_short_additional_reason").attr("style", "display:'';");
                    $("#div_design_change_sheet").attr("style", "display:'';");
                    $("#div_installworksheet").attr("style", "display:'';");
                }
                else if (selectvalue == "图纸差异增补")
                {
                    $("#div_drawing_additional_reason").attr("style", "display:'';");
                    $("#div_design_change_sheet").attr("style", "display:'';");
                    $("#div_installworksheet").attr("style", "display:'';");
                }
                else if (selectvalue == "范围外增补")
                {
                    $("#div_extra_additional_reason").attr("style", "display:'';");
                    $("#div_huaxingworksheet").attr("style", "display:'';");
                }
                else if (selectvalue == "业主额外增补")
                {
                    $("#div_owner_additional_reason").attr("style", "display:'';");
                    $("#div_huaxingworksheet").attr("style", "display:'';");
                }
                else if (selectvalue == "业主合理增补")
                {
                    $("#div_owner_additional_reasonalreason").attr("style", "display:'';");
                    $("#div_design_change_sheet").attr("style", "display:'';");
                    $("#div_huaxingworksheet").attr("style", "display:'';");
                }
                else if (selectvalue == "配合增补")
                {
                    $("#div_coordinate_additional_reason").attr("style", "display:'';");
                    $("#div_huaxingworksheet").attr("style", "display:'';");
                }
            }

            selectDesignChangeSheetChange = function () {
                var selectvalue = $("#design_change_sheet").val();

                if (selectvalue == "技术部下发了设计变更单")
                {
                    $("#divOutsourcingcompany").attr("style", "display:'';");
//                    $("label[for='outsourcingcompany']").attr("style", "display:'';");
//                    $("#outsourcingcompany").attr("style", "display:'';");
                }
                else
                {
                    $("#divOutsourcingcompany").attr("style", "display:none;");
//                    $("label[for='outsourcingcompany']").attr("style", "display:none;");
//                    $("#outsourcingcompany").attr("style", "display:none;");
                    $("#outsourcingcompany").val("");
                    $("#outsourcingcompany_id").val(0);
                }
            }

            selectExtraAdditionalReasonChange = function () {
                var selectvalue = $("#extra_additional_reason").val();

                $("#div_associatedapprovals").attr("style", "display:none;");

                if (selectvalue == "外协加工厂加工结构件漏件，需现场加工制作")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
                else if (selectvalue == "外协加工厂加工结构件散件发货，需现场额外加工造成增补")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
                else if (selectvalue == "外协加工厂加工结构件发货运输造成损坏，需现场修复")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
                else if (selectvalue == "供应商提供的设备不能满足现场要求或无法安装，需现场修正")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
                else if (selectvalue == "供应商提供的设备中漏发配件，需现场加工制作")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
                else if (selectvalue == "供应商提供的设备运输损坏，需要现场修正")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
            }

            selectCoordinateAdditionalReasonChange = function () {
                var selectvalue = $("#coordinate_additional_reason").val();

                $("#div_associatedapprovals").attr("style", "display:none;");

                if (selectvalue == "供应商提供的设备不能满足现场运行，调试或168中拆卸安装修复所产生的工作量")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
                else if (selectvalue == "因供应商提供设备或材料质量问题造成售后，从而产生安装队的工作量")
                {
                    $("#div_associatedapprovals").attr("style", "display:'';");
                }
            }

            selectAdditionalContentChange = function () {
                var selectvalue = $("#additional_content").val();

                $("#material_detail").attr("style", "display:none;");
                $("#humanday_detail").attr("style", "display:none;");
                $("#crane_detail").attr("style", "display:none;");

                if (selectvalue == "仅材料费用")
                {
                    $("#material_detail").attr("style", "display:'';");
                }
                else if (selectvalue == "材料费用+人工费用")
                {
                    $("#material_detail").attr("style", "display:'';");
                    $("#humanday_detail").attr("style", "display:'';");
                }
                else if (selectvalue == "材料费用+吊机费用")
                {
                    $("#material_detail").attr("style", "display:'';");
                    $("#crane_detail").attr("style", "display:'';");
                }
                else if (selectvalue == "材料费用+人工费用+吊机费用")
                {
                    $("#material_detail").attr("style", "display:'';");
                    $("#humanday_detail").attr("style", "display:'';");
                    $("#crane_detail").attr("style", "display:'';");
                }
            }

            selectTypeChange = function (num) {
                var productioncompany = $("#productioncompany").val();
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
				$("#btnSelectImage_beforeimage").click(function() {
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
							$("#previewimage_beforeimage").empty().append(imageHtml);
						},
						onFail: function(err) {
							alert('select image failed: ' + JSON.stringify(err));
						}
					});
				});

                // 上传附件
                $("#uploadAttach_bothsigned").click(function () {
                    dd.biz.util.uploadAttachment({
                        {{--image:{multiple:true,compress:false,max:9,spaceId: "{!! array_get($config, 'spaceid') !!}"},--}}
                        space:{corpId:"{!! array_get($config, 'corpId') !!}",spaceId:"{!! array_get($config, 'spaceid') !!}",isCopy:1 , max:9},
                        file:{spaceId:"{!! array_get($config, 'spaceid') !!}",max:5},
                        types:["file","space"],//PC端支持["photo","file","space"]
                        onSuccess : function(result) {
                            //onSuccess将在文件上传成功之后调用
//                            alert(JSON.stringify(result));
                            $("#files_string_bothsigned").val(JSON.stringify(result.data));
                            var strhtml = '已上传文件：';
                            $.each(result.data, function(i, field) {
                                btnId = 'btnSelectOrder_' + String(i);
                                strhtml += field.fileName + ",";
                            });
                            $("#lblFiles_bothsigned").empty().append(strhtml);
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

                // 上传附件
                $("#uploadAttach_huaxingworksheet").click(function () {
                    dd.biz.util.uploadAttachment({
                        {{--image:{multiple:true,compress:false,max:9,spaceId: "{!! array_get($config, 'spaceid') !!}"},--}}
                        space:{corpId:"{!! array_get($config, 'corpId') !!}",spaceId:"{!! array_get($config, 'spaceid') !!}",isCopy:1 , max:9},
                        file:{spaceId:"{!! array_get($config, 'spaceid') !!}",max:5},
                        types:["file","space"],//PC端支持["photo","file","space"]
                        onSuccess : function(result) {
                            //onSuccess将在文件上传成功之后调用
//                            alert(JSON.stringify(result));
                            $("#files_string_huaxingworksheet").val(JSON.stringify(result.data));
                            var strhtml = '已上传文件：';
                            $.each(result.data, function(i, field) {
                                btnId = 'btnSelectOrder_' + String(i);
                                strhtml += field.fileName + ",";
                            });
                            $("#lblFiles_huaxingworksheet").empty().append(strhtml);
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

                // 上传附件
                $("#uploadAttach_installworksheet").click(function () {
                    dd.biz.util.uploadAttachment({
                        {{--image:{multiple:true,compress:false,max:9,spaceId: "{!! array_get($config, 'spaceid') !!}"},--}}
                        space:{corpId:"{!! array_get($config, 'corpId') !!}",spaceId:"{!! array_get($config, 'spaceid') !!}",isCopy:1 , max:9},
                        file:{spaceId:"{!! array_get($config, 'spaceid') !!}",max:5},
                        types:["file","space"],//PC端支持["photo","file","space"]
                        onSuccess : function(result) {
                            //onSuccess将在文件上传成功之后调用
//                            alert(JSON.stringify(result));
                            $("#files_string_installworksheet").val(JSON.stringify(result.data));
                            var strhtml = '已上传文件：';
                            $.each(result.data, function(i, field) {
                                btnId = 'btnSelectOrder_' + String(i);
                                strhtml += field.fileName + ",";
                            });
                            $("#lblFiles_installworksheet").empty().append(strhtml);
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

                // 上传附件
                $("#uploadAttach_beforeimage").click(function () {
                    dd.biz.util.uploadAttachment({
                        {{--image:{multiple:true,compress:false,max:9,spaceId: "{!! array_get($config, 'spaceid') !!}"},--}}
                        space:{corpId:"{!! array_get($config, 'corpId') !!}",spaceId:"{!! array_get($config, 'spaceid') !!}",isCopy:1 , max:9},
                        file:{spaceId:"{!! array_get($config, 'spaceid') !!}",max:5},
                        types:["file","space"],//PC端支持["photo","file","space"]
                        onSuccess : function(result) {
                            //onSuccess将在文件上传成功之后调用
//                            alert(JSON.stringify(result));
                            $("#files_string_beforeimage").val(JSON.stringify(result.data));
                            var strhtml = '已上传文件：';
                            $.each(result.data, function(i, field) {
                                btnId = 'btnSelectOrder_' + String(i);
                                strhtml += field.fileName + ",";
                            });
                            $("#lblFiles_beforeimage").empty().append(strhtml);
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

                // 上传附件
                $("#uploadAttach_afterimage").click(function () {
                    dd.biz.util.uploadAttachment({
                        {{--image:{multiple:true,compress:false,max:9,spaceId: "{!! array_get($config, 'spaceid') !!}"},--}}
                        space:{corpId:"{!! array_get($config, 'corpId') !!}",spaceId:"{!! array_get($config, 'spaceid') !!}",isCopy:1 , max:9},
                        file:{spaceId:"{!! array_get($config, 'spaceid') !!}",max:5},
                        types:["file","space"],//PC端支持["photo","file","space"]
                        onSuccess : function(result) {
                            //onSuccess将在文件上传成功之后调用
//                            alert(JSON.stringify(result));
                            $("#files_string_afterimage").val(JSON.stringify(result.data));
                            var strhtml = '已上传文件：';
                            $.each(result.data, function(i, field) {
                                btnId = 'btnSelectOrder_' + String(i);
                                strhtml += field.fileName + ",";
                            });
                            $("#lblFiles_afterimage").empty().append(strhtml);
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
