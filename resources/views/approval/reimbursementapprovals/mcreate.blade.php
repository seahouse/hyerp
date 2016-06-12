@extends('approval.reimbursements.mshow')

@section('for_reimbursementapprovals_create')
    @include('approval.reimbursementapprovals._form', 
    	[
    		'acceptButtonText' => '同意', 
    		'rejectButtonText' => '拒绝', 
    		'date' => date('Y-m-d'),
    		'customer_id' => '0', 
    		'amount' => '0.0', 
    		'order_id' => '0',
    		'datego' => date('Y-m-d'),
    		'dateback' => date('Y-m-d'),
    		'mealamount' => '0.0',
    		'ticketamount' => '0.0',
    		'stayamount' => '0.0',
    		'otheramount' => '0.0',
			'attr' => '',
			'btnclass' => 'btn btn-primary',
    	])

@endsection


@section('for_reimbursementapprovals_create_script')	
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>

	<script type="text/javascript">		
		jQuery(document).ready(function(e) {

			dd.config({
			    agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
			    corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
			    timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
			    nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
			    signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
			    jsApiList: ['biz.util.uploadImage'] // 必填，需要使用的jsapi列表
			});

			dd.ready(function() {
				$("#btnAccept").bind("click", function() {
					alert('abcd');
					$.ajax({
						type: "POST",
						url: "{{ url('approval/reimbursementapprovals/mstore') }}",
						data: $("form#formAccept").serialize(),
						contentType:"application/x-www-form-urlencoded",
						error: function(xhr, ajaxOptions, thrownError) {
		             	    alert($("form#formAccept").serialize());
		             	    alert('error');
							alert(xhr.status);
							alert(xhr.responseText);
							alert(ajaxOptions);
							alert(thrownError);
						},
						success: function(result) {
							if (result == "success")
							{
								alert('操作成功.');
								dd.biz.ding.post({
								    users : ['manager1200'],//用户列表，工号
								    corpId: '{!! array_get($config, 'corpId') !!}', //企业id
								    type: 2, //钉类型 1：image  2：link
								    alertType: 2,
								    alertDate: {"format":"yyyy-MM-dd HH:mm","value":"2015-05-09 08:00"},
								    attachment: {
								        title: '华星审批',
								        url: '',
								        image: '',
								        text: '2222'
								    }
								    text: '有一个报销需要您审批', //消息
								    onSuccess : function() {},
								    onFail : function() {}
								});
							}								
							else
								alert('操作失败.');
							$('#acceptModal').modal('toggle');
							location.href = "{{ url('approval/reimbursements/mindexmyapproval') }}";
						},
					});
				});
			});


			$("#btnReject").bind("click", function() {
				$.ajax({
					type: "POST",
					url: "{{ url('approval/reimbursementapprovals/mstore') }}",
					data: $("form#formReject").serialize(),
					contentType:"application/x-www-form-urlencoded",
					error: function(xhr, ajaxOptions, thrownError) {
	             	    alert($("form#formReject").serialize());
	             	    alert('error');
						alert(xhr.status);
						alert(xhr.responseText);
						alert(ajaxOptions);
						alert(thrownError);
					},
					success: function(result) {
						alert('操作完成.');
						$('#rejectModal').modal('toggle');
						location.href = "{{ url('approval/reimbursements/mindexmyapproval') }}";
					},
				});
			});


			
		});
	</script>

@endsection
