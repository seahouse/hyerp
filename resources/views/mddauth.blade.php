@extends('app')

@section('title')
身份验证
@endsection

@section('main')
	<p>
		<h4>身份验证中，请稍后....</h4>		
	</p>
<!-- 	<p>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">待我审批</button></a>
			</div>
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">我发起的</button></a>
			</div>
		</div>
	</p>

	<p>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="/approval/reimbursements/mcreate"><button type="button" class="btn btn-default">报销</button></a>
			</div>
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">请款</button></a>
			</div>
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">请假</button></a>
			</div>
		</div>
	</p> -->
@endsection

@section('script')
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
	
	<script type="text/javascript">		
		jQuery(document).ready(function(e) {
			$.ajax({
				type: "GET",
				url: "{{ url('dingtalk/getconfig') }}",
				error:function(xhr, ajaxOptions, thrownError){
             		alert('getConfig failed.');
             	    alert('error');
					alert(xhr.status);
					alert(xhr.responseText);
					alert(ajaxOptions);
					alert(thrownError);
             	},
             	success:function(result){
             		alert('getConfig success. signature:' + result.signature);
             		dd.config({
					    agentId: '13231599', // 必填，微应用ID
					    corpId: 'ding6ed55e00b5328f39',//必填，企业ID
					    timeStamp: result.timeStamp, // 必填，生成签名的时间戳
					    nonceStr: result.nonceStr, // 必填，生成签名的随机串
					    signature: result.signature, // 必填，签名
					    jsApiList: ['device.notification.alert', 'device.notification.confirm', 'biz.util.uploadImage'] // 必填，需要使用的jsapi列表
					});

					dd.ready(function() {				
						dd.runtime.info({
							onSuccess: function(info) {
								// alert('runtime info: ' + JSON.stringify(info));
							},
							onFail: function(err) {
								alert('fail: ' + JSON.stringify(err));
							}
						});
			
						dd.runtime.permission.requestAuthCode({
						    corpId: "ding6ed55e00b5328f39",
						    onSuccess: function(info) {
					     	    $.ajax({
					         	    type:"GET",
					         	    url:"{{ url('dingtalk/getuserinfo') }}" + "/" + info.code,
					         	    error:function(xhr, ajaxOptions, thrownError){
					             	    alert('error');
										alert(xhr.status);
										alert(xhr.responseText);
										alert(ajaxOptions);
										alert(thrownError);
					             	},
					             	success:function(msg){
					             	    // alert('userid: ' + msg.userid);
					             	    // alert('userid_erp: ' + msg.userid_erp);
					             	    if (msg.userid_erp == -1)
					             	    	alert('您的账号未与后台绑定，无法使用此应用.');
					             	    else if ("{{ request('app') }}" == "approval")
					             	    {
					             	    	location.href = "{{ url('/mapproval') }}";
					             	    }
					                },
					         	});
						    },
						    onFail : function(err) {
								alert('requestAuthCode fail: ' + JSON.stringify(err));
							}
						});
					});
                },
			});

			// dd.ready(function() {				
			// 	dd.runtime.info({
			// 		onSuccess: function(info) {
			// 			// alert('runtime info: ' + JSON.stringify(info));
			// 		},
			// 		onFail: function(err) {
			// 			alert('fail: ' + JSON.stringify(err));
			// 		}
			// 	});
	
			// 	dd.runtime.permission.requestAuthCode({
			// 	    corpId: "ding6ed55e00b5328f39",
			// 	    onSuccess: function(info) {
			//      	    $.ajax({
			//          	    type:"GET",
			//          	    url:"{{ url('dingtalk/getuserinfo') }}" + "/" + info.code,
			//          	    error:function(xhr, ajaxOptions, thrownError){
			//              	    alert('error');
			// 					alert(xhr.status);
			// 					alert(xhr.responseText);
			// 					alert(ajaxOptions);
			// 					alert(thrownError);
			//              	},
			//              	success:function(msg){
			//              	    // alert('userid: ' + msg.userid);
			//              	    // alert('userid_erp: ' + msg.userid_erp);
			//              	    if (msg.userid_erp == -1)
			//              	    	alert('您的账号未与后台绑定，无法使用此应用.');
			//              	    else if ("{{ request('app') }}" == "approval")
			//              	    {
			//              	    	location.href = "{{ url('/mapproval') }}";
			//              	    }
			//                 },
			//          	});
			// 	    },
			// 	    onFail : function(err) {
			// 			alert('requestAuthCode fail: ' + JSON.stringify(err));
			// 		}
			// 	});
			// });
		});
	</script>
@endsection