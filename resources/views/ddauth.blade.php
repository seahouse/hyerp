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


<!-- 	{{ $config['url'] }}
	{{ array_get($config, 'url') }} -->

	@foreach ($config as $key => $value)
		{!! Form::hidden($key, $value, ['id' => $key]) !!}
    @endforeach
@endsection

@section('script')
	<script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
	
	<script type="text/javascript">
		// alert(" {!! array_get($config, 'url') !!}");
		jQuery(document).ready(function(e) {
			DingTalkPC.config({
			    // agentId: '13231599', // 必填，微应用ID
			    // corpId: 'ding6ed55e00b5328f39',//必填，企业ID
			    // timeStamp: $('#timeStamp').val(), // 必填，生成签名的时间戳
			    // nonceStr: $('#nonceStr').val(), // 必填，生成签名的随机串
			    // signature: $('#signature').val(), // 必填，签名
			    agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
			    corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
			    timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
			    nonceStr: "{!! array_get($config, 'nonceStr') !!}", // 必填，生成签名的随机串
			    signature: "{!! array_get($config, 'signature') !!}", // 必填，签名
			    jsApiList: ['runtime.info',
			    	'device.notification.alert', 
			    	'device.notification.confirm', 
			    	'biz.util.uploadImage',
			    	'biz.navigation.close'] // 必填，需要使用的jsapi列表
			});

			

			DingTalkPC.ready(function(res) {
				// DingTalkPC.runtime.info({
				// 	onSuccess: function(info) {
				// 		// alert('runtime info: ' + JSON.stringify(info));
				// 	},
				// 	onFail: function(err) {
				// 		alert('fail: ' + JSON.stringify(err));
				// 	}
				// });

				// // if the page is not first history.back, exit it.
				// if (history.length > 1)
				// {
				// 	dd.biz.navigation.close({
				// 	    onSuccess : function(result) {
				// 	        /*result结构
				// 	        {}
				// 	        */
				// 	    },
				// 	    onFail : function(err) {}
				// 	});
				// }
	
				DingTalkPC.runtime.permission.requestAuthCode({
				    corpId: "{!! array_get($config, 'corpId') !!}",
				    onSuccess: function(result) {
				    	alert(result.code);
			     	    $.ajax({
			         	    type:"GET",
			         	    url:"{{ url('dingtalk/getuserinfo') }}" + "/" + result.code,
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
			             	    else if ("{!! array_get($config, 'appname') !!}" == "approval")
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

			DingTalkPC.error(function(error) {
				alert('DingTalkPC.error: ' + JSON.stringify(error));
			});
		});
	</script>
@endsection