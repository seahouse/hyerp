@extends('app')

@section('title', '创建付款申请单')

@section('main')
    {!! Form::open(array('url' => 'approval/paymentrequests/mstore', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
        @include('approval.paymentrequests._form', 
        	[
        		'submitButtonText' => '提交', 
        		'supplier_name' => null,
        		'pohead_number' => null,
        		'pohead_name' => null,
        		'datepay' => date('Y-m-d'),
        		'customer_name' => null,
        		'customer_id' => '0', 
        		'amount' => '0.0', 
        		'order_number' => null,
        		'order_id' => '0',
        		'travel_1_datego' => date('Y-m-d'),
        		'travel_1_dateback' => date('Y-m-d'),
        		'travel_1_customer_name' => null,
        		'travel_1_order_number' => null,
        		'datego' => date('Y-m-d'),
        		'dateback' => date('Y-m-d'),
        		'mealamount' => '0.0',
        		'ticketamount' => '0.0',
        		'amountAirfares' => '0.0',
        		'amountTrain' => '0.0',
        		'amountTaxi' => '0.0',
        		'amountOtherTicket' => '0.0',
        		'stayamount' => '0.0',
        		'otheramount' => '0.0',
				'attr' => '',
				'attrdisable' => 'disabled',
				'btnclass' => 'btn btn-primary',
        	])
    {!! Form::close() !!}

<!-- order selector -->
<div class="modal fade" id="selectOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择采购订单</h4>                
            </div>
            <div class="modal-body">
            	<div class="input-group">
            		{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '对应订单编号或者工程名称', 'id' => 'keyOrder']) !!}
            		<span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchOrder']) !!}
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
			var travelNum = 1;



			// $("#btnSubmit").click(function() {
			// 	$('#submitModal').modal('toggle');
			// 	return false;
			// });

			$('#submitModal').on('shown.bs.modal', function (e) {
				$("#btnSubmitContinue").attr('disabled',true);
				$.ajax({
					type: "POST",
					url: "{{ url('approval/paymentrequests/check') }}",
					data: $("form#formMain").serialize(),
					dataType: "json",
					error:function(xhr, ajaxOptions, thrownError){
						alert('error');
					},
					success:function(msg){
						var strhtml = '';
						strhtml += "生活补贴合计: " + String(msg.mealamount) + "<br />";
						strhtml += "交通费合计: " + String(msg.ticketamount) + "<br />";
						strhtml += "总费用: " + String(msg.amountTotal) + "<br />";
						strhtml += "平均每日住宿费: " + String(msg.stayamountPer) + "<br />";
						strhtml += "平均每日合计: " + String(msg.amountPer) + "<br />";
						$("#dataDefine").empty().append(strhtml);

						if (msg.status == "OK")
							$("#btnSubmitContinue").attr('disabled', false);
					},
				});				
			});

			$("#btnSubmitContinue").click(function() {
				$("form#formMain").submit();
			});

			$("#btnAddTravel").click(function() {
				travelNum++;
				var btnId = 'btnDeleteTravel_' + String(travelNum);
				var divName = 'divClassTravel_' + String(travelNum);
				var itemTravel = '<div class="' + divName + '"><p class="bannerTitle">出差时间段明细(' + String(travelNum) + ')&nbsp;<button class="btn btn-sm" id="' + btnId + '" type="button">删除</button></p>\
					<div class="form-group">\
						<label for="travel_' + String(travelNum) + '_datego" class="col-xs-4 col-sm-2 control-label">出差去日:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_datego" type="date" value="2016-01-01" >\
						</div>\
					</div>\
					<div class="form-group">\
						<label for="traveldateback_' + String(travelNum) + '" class="col-xs-4 col-sm-2 control-label">出差回日:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_dateback" type="date" value="2016-01-01" >\
						</div>\
					</div>\
					<div class="form-group">\
						<label for="traveldescrip_' + String(travelNum) + '" class="col-xs-4 col-sm-2 control-label">地点及事由:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_descrip" type="text">\
						</div>\
					</div>\
					<div class="form-group">\
						<label for="travel_customer_name' + String(travelNum) + '" class="col-xs-4 col-sm-2 control-label">客户:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_customer_name" type="text" data-toggle="modal" data-target="#selectSupplierModal" data-name="travel_' + String(travelNum) + '_customer_name" data-id="travel_' + String(travelNum) + '_customer_id" type="text" id="travel_' + String(travelNum) + '_customer_name">\
						<input name="travel_' + String(travelNum) + '_customer_id" id="travel_' + String(travelNum) + '_customer_id" type="hidden" value="0">\
						</div>\
					</div>\
					<div class="form-group">\
						<label for="travelcontacts_' + String(travelNum) + '" class="col-xs-4 col-sm-2 control-label">客户联系人:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_contacts" type="text">\
						</div>\
					</div>\
					<div class="form-group">\
						<label for="travelcontactspost_' + String(travelNum) + '" class="col-xs-4 col-sm-2 control-label">客户联系人职务:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_contactspost" type="text">\
						</div>\
					</div>\
					<div class="form-group">\
						<label for="travel_order_number' + String(travelNum) + '" class="col-xs-4 col-sm-2 control-label">对应订单:</label>\
						<div class="col-sm-10 col-xs-8">\
						<input class="form-control" name="travel_' + String(travelNum) + '_order_number" type="text" data-toggle="modal" data-target="#selectOrderModal" data-name="travel_' + String(travelNum) + '_order_number" data-id="travel_' + String(travelNum) + '_order_id" data-customerid="travel_' + String(travelNum) + '_customer_id" type="text" id="travel_' + String(travelNum) + '_order_number">\
						<input name="travel_' + String(travelNum) + '_order_id" id="travel_' + String(travelNum) + '_order_id" type="hidden" value="0">\
						</div>\
					</div>\
					</div>';
				$("#travelMore").append(itemTravel);
				addBtnDeleteTravelClickEvent(btnId, divName);
			});

			function addBtnDeleteTravelClickEvent(btnId, divName)
			{
				$("#" + btnId).bind("click", function() {
					// travelNum--; 	// 不需要减法，否则在删除中间段的时候会导致有重复div
					$("." + divName).remove();
				});
			}

			$('#selectOrderModal').on('show.bs.modal', function (e) {
				$("#listsalesorders").empty();

				var text = $(e.relatedTarget);
				var modal = $(this);

				modal.find('#name').val(text.data('name'));
				modal.find('#id').val(text.data('id'));
				modal.find('#supplierid').val(text.data('supplierid'));
				modal.find('#poheadamount').val(text.data('poheadamount'));
			});

			$("#btnSearchOrder").click(function() {
				if ($("#keyOrder").val() == "") {
					alert('请输入关键字');
					return;
				}
				$.ajax({
					type: "GET",
					url: "{!! url('/purchase/purchaseorders/getitemsbyorderkey/') !!}" + "/" + $("#keyOrder").val() + "/" + $("#" + $("#selectOrderModal").find('#supplierid').val()).val(),
					success: function(result) {
						var strhtml = '';
						$.each(result.data, function(i, field) {
							btnId = 'btnSelectOrder_' + String(i);
							strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.number + "</h4><p>" + field.descrip + "</p></button>"							
						});
						if (strhtml == '')
							strhtml = '无记录。';
						$("#listsalesorders").empty().append(strhtml);

						$.each(result.data, function(i, field) {
							btnId = 'btnSelectOrder_' + String(i);
							addBtnClickEvent(btnId, field.id, field.number, field.amount, field.amount_paid, field);
						});
						// addBtnClickEvent('btnSelectOrder_0');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert('error');
					}
				});
			});

			function addBtnClickEvent(btnId, salesorderid, number, amount, amount_paid, field)
			{
				$("#" + btnId).bind("click", function() {
					$('#selectOrderModal').modal('toggle');
					// $("#order_number").val(number);
					// $("#order_id").val(salesorderid);
					$("#" + $("#selectOrderModal").find('#name').val()).val(number);
					$("#" + $("#selectOrderModal").find('#id').val()).val(salesorderid);
					$("#" + $("#selectOrderModal").find('#poheadamount').val()).val(amount);
					$("#pohead_amount_paid").val(amount_paid);
					$("#pohead_amount_ticketed").val(field.amount_ticketed);
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
							btnId = 'btnSelectCustomer_' + String(i);
							strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"							
						});
						if (strhtml == '')
							strhtml = '无记录。';
						$("#listsuppliers").empty().append(strhtml);

						$.each(result.data, function(i, field) {
							btnId = 'btnSelectCustomer_' + String(i);
							addBtnClickEventSupplier(btnId, field.id, field.name);
						});
						// addBtnClickEvent('btnSelectOrder_0');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert('error');
					}
				});
			});

			function addBtnClickEventSupplier(btnId, supplierid, name)
			{
				$("#" + btnId).bind("click", function() {
					$('#selectSupplierModal').modal('toggle');
					$("#" + $("#selectSupplierModal").find('#name').val()).val(name);
					$("#" + $("#selectSupplierModal").find('#id').val()).val(supplierid);
				});
			}



			// $("#btnSelectImage").click(function() {
			// 	var images = ['http://static.dingtalk.com/media/lADODGPhgM0CHM0DwA_960_540.jpg', 'http://static.dingtalk.com/media/lALODL7StM0DwM0CHA_540_960.png'];
			// 	var imageHtml = '';
			// 	for (var i in images) {
			// 		imageHtml += '<div class="col-xs-6 col-md-3">';
			// 		imageHtml += '<div class="thumbnail">';
			// 		imageHtml += '<img src=' + images[i] + ' />';
			// 		imageHtml += '<input name="image_' + String(i) + '" value=' + images[i] + ' type="hidden">';
			// 		imageHtml += '</div>';
			// 		imageHtml += '</div>';
			// 	}
			// 	$("#previewimage").empty().append(imageHtml);
			// });

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

				// 上传附件
				$("#btnSelectPaymentnodeattachment").click(function() {
					dd.biz.cspace.saveFile({
						corpId:"{!! array_get($config, 'corpId') !!}",
						url:"https://ringnerippca.files.wordpress.com/20.pdf",
						name:"文件名",
						onSuccess: function(data) {
		                 /* data结构
		                 {"data":
		                    [
		                    {
		                    "corpId": "", //公司id
		                    "spaceId": "" //空间id
		                    "fileId": "", //文件id
		                    "fileName": "", //文件名
		                    "fileSize": 111111, //文件大小
		                    "fileType": "", //文件类型
		                    }
		                    ]
		                 }
		                 */
		                },
		                onFail: function(err) {
		                    alert(JSON.stringify(err));
		                }
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
