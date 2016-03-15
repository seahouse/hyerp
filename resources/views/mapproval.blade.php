@extends('app')

@section('main')
	<p>
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
	</p>
@endsection

@section('script')
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
	
	<script type="text/javascript">
		// var _config = {
  //           agentId: document.getElementById("agentid").value,
  //           corpId: document.getElementById("corpid").value,
  //           timeStamp: document.getElementById("timestamp").value,
  //           nonceStr: document.getElementById("noncestr").value,
  //           signature: document.getElementById("signature").value,
  //       };
		
		// dd.config({
  //           agentId: _config.agentId,
  //           corpId: _config.corpId,
  //           timeStamp: _config.timeStamp,
  //           nonceStr: _config.nonceStr,
  //           signature: _config.signature,
  //           jsApiList: ['runtime.info',
  //               'biz.contact.choose',
  //               'device.notification.confirm',
  //               'device.notification.alert',
  //               'device.notification.prompt',
  //               'biz.ding.post']
  //       });
		
		jQuery(document).ready(function(e) {
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
			             	    alert('userid: ' + msg.userid);
			             	    alert('userid_erp: ' + msg.userid_erp);
			             	    if (msg.userid_erp == -1)
			             	    	alert('您的账号未与后台绑定，无法使用此应用.' + msg.userid_erp);
			                },
			         	});
				    },
				    onFail : function(err) {
						alert('requestAuthCode fail: ' + JSON.stringify(err));
					}
				});
			});
		});
	</script>
@endsection