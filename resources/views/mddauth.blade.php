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
<!--     $agent->is('Windows'): {{ $agent->is('Windows') }}		<br>
    $agent->is('Firefox'): {{ $agent->is('Firefox') }}		<br>
    $agent->is('iPhone'): {{ $agent->is('iPhone') }}		<br>
    $agent->is('OS X'): {{ $agent->is('OS X') }}			<br>
    $agent->isAndroidOS(): {{ $agent->isAndroidOS() }}		<br>
    $agent->isNexus(): {{ $agent->isNexus() }}				<br>
    $agent->isSafari(): {{ $agent->isSafari() }}			<br>
    $agent->isMobile(): {{ $agent->isMobile() }}			<br>
    $agent->isTablet(): {{ $agent->isTablet() }}			<br>
	$agent->device(): {{ $agent->device() }}				<br>
    $agent->platform(): {{ $agent->platform() }}			<br>
    $agent->browser(): {{ $agent->browser() }}				<br>
    $agent->isDesktop(): {{ $agent->isDesktop() }}			<br>
    $agent->isPhone(): {{ $agent->isPhone() }}				<br>
    $agent->isRobot(): {{ $agent->isRobot() }}				<br>
    $agent->robot(): {{ $agent->robot() }}					<br>
    $agent->isPhone(): {{ $agent->isPhone() }}				<br> -->

    <!-- can not display array value -->
    <!--     $agent->languages():			<br> -->
    
    <!-- {{ $url }} -->
@endsection

@if ($agent->isMobile())
@section('script')
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
	
	<script type="text/javascript">
		// alert(document.referrer);

		// alert(" {!! array_get($config, 'url') !!}");
		jQuery(document).ready(function(e) {


			dd.config({
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
				dd.runtime.info({
					onSuccess: function(info) {
						// alert('runtime info: ' + JSON.stringify(info));
					},
					onFail: function(err) {
						alert('fail: ' + JSON.stringify(err));
					}
				});

				// if the page is not first history.back, exit it.
				if (history.length > 1)
				{
					dd.biz.navigation.close({
					    onSuccess : function(result) {
					        /*result结构
					        {}
					        */
					    },
					    onFail : function(err) {}
					});
				}
	
				dd.runtime.permission.requestAuthCode({
				    corpId: "{!! array_get($config, 'corpId') !!}",
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
			             	    else if ("{!! array_get($config, 'appname') !!}" == "approval")
			             	    {
			             	    	var url = '{!! $url !!}';
			             	    	if ('{!! $url !!}' != '')
			             	    		location.href = "{!! url('/') !!}" + "/" + url;
			             	    	else
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

			dd.error(function(error) {
				alert('dd.error: ' + JSON.stringify(error));
			});
		});
	</script>
@endsection
@elseif ($agent->isDesktop()) 
@section('script')
	<script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
	
	<script type="text/javascript">
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
			    	'runtime.permission.requestAuthCode',
			    	'device.notification.alert', 
			    	'device.notification.confirm', 
			    	'biz.util.uploadImage',
			    	'biz.navigation.close',
			    	'biz.util.open',
			    	'biz.util.openLink'] // 必填，需要使用的jsapi列表
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

				console.log(DingTalkPC.ua);
	
				// console.log('requestAuthCode');
				DingTalkPC.runtime.permission.requestAuthCode({
				    corpId: "{!! array_get($config, 'corpId') !!}",
				    onSuccess: function(result) {
			     	    $.ajax({
			         	    type:"GET",
			         	    url:"{{ url('dingtalk/getuserinfo') }}" + "/" + result.code,
			         	    error:function(xhr, ajaxOptions, thrownError){
								DingTalkPC.device.notification.alert({
								    message: "登录错误",
								    title: "登录错误",//可传空
								    buttonName: "收到",
								    onSuccess : function() {
								        /*回调*/
								    },
								    onFail : function(err) {}
								});				    			
			     //         	    alert('error');
								// alert(xhr.status);
								// alert(xhr.responseText);
								// alert(ajaxOptions);
								// alert(thrownError);
			             	},
			             	success:function(msg){
			             		// console.log('requestAuthCode success');
			             	    // alert('userid: ' + msg.userid);
			             	    // alert('userid_erp: ' + msg.userid_erp);
			             	    // console.log('{!! array_get($config, "appname") !!}');
			             	    if (msg.userid_erp == -1)
			             	    {
									DingTalkPC.device.notification.alert({
									    message: "登录错误",
									    title: "登录错误",//可传空
									    buttonName: "收到",
									    onSuccess : function() {
									        /*回调*/
									    },
									    onFail : function(err) {}
									});
			             	    	// alert('您的账号未与后台绑定，无法使用此应用.');
			             	    }
			             	    else if ("{!! array_get($config, 'appname') !!}" == "approval")
			             	    {
			             	    	// location.href = "{{ url('/mapproval') }}";
			             	    	var url = '{!! $url !!}';
			             	    	if ('{!! $url !!}' != '')
			             	    	{
										// DingTalkPC.biz.util.openLink({
										//     url: "{!! url('/') !!}" + "/" + url,//要打开链接的地址
										//     onSuccess : function(result) {
										//     	console.log('openLink success.');
										//         /**/
										//     },
										//     onFail : function() {
										//     	console.log('openLink failed.');
										//     }
										// })
			             	    		location.href = "{!! url('/') !!}" + "/" + url;
			             	    	}
			             	    	else
			             	    		location.href = "{{ url('/mapproval') }}";
			             	    }
			             	    // else
			             	    // 	console.log('else ~~');
			                },
			         	});
				    },
				    onFail : function(err) {
						DingTalkPC.device.notification.alert({
						    message: JSON.stringify(err),
						    title: "requestAuthCode",//可传空
						    buttonName: "收到",
						    onSuccess : function() {
						        /*回调*/
						    },
						    onFail : function(err) {}
						});
						// alert('requestAuthCode fail: ' + JSON.stringify(err));
					}
				});
			});

			DingTalkPC.error(function(error) {
				alert('DingTalkPC.error: ' + JSON.stringify(error));
			});
		});
	</script>
@endsection

@endif