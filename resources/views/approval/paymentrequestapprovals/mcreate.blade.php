@extends('approval.paymentrequests.mshow')

@section('for_paymentrequestapprovals_create')
    @include('approval.paymentrequestapprovals._form', 
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

	@if (Auth::user()->email == "admin@admin.com")
	{!! Form::button('聊天2', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnPickConversation']) !!}
	{!! Form::button('聊天3', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnPickConversation3']) !!}
	{!! Form::button('聊天4', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnPickConversation4']) !!}
	<a href="/dingtalk/send_to_conversation">聊天5</a>
	@endif

@endsection


@section('for_paymentrequestapprovals_create_script')	
	<script src="http://g.alicdn.com/dingding/open-develop/1.0.0/dingtalk.js"></script>

	<script type="text/javascript">		
		jQuery(document).ready(function(e) {


			$("#btnAccept").bind("click", function() {
				$.ajax({
					type: "POST",
					url: "{{ url('approval/paymentrequestapprovals/mstore') }}",
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
{{--
							// dd.biz.ding.post({
							//     users : ['manager1200'],//用户列表，工号
							//     corpId: '{!! array_get($config, 'corpId') !!}', //企业id
							//     type: 2, //钉类型 1：image  2：link
							//     alertType: 2,
							//     alertDate: {"format":"yyyy-MM-dd HH:mm","value":"2015-05-09 08:00"},
							//     attachment: {
							//         title: '华星审批',
							//         url: '',
							//         image: '',
							//         text: '2222'
							//     },
							//     text: '有一个报销需要您审批', //消息
							//     onSuccess : function() {},
							//     onFail : function() {}
							// });
--}}
						}								
						else
							alert('操作失败：' + result);
						$('#acceptModal').modal('toggle');
						location.href = "{{ url('approval/mindexmyapproval') }}";
					},
				});
			});

			$("#btnReject").bind("click", function() {
				$.ajax({
					type: "POST",
					url: "{{ url('approval/paymentrequestapprovals/mstore') }}",
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
						location.href = "{{ url('approval/mindexmyapproval') }}";
					},
				});
			});

            $("#btnPickConversation4").click(function() {
                $.post("{{ url('/dingtalk/send_to_conversation') }}", {cid:"chatf12c075227956c1a596eec32885865f8", id: "{{$paymentrequest->id}}", _token:"{!! csrf_token() !!}"}, function (data) {

                });
            });

            dd.config({
                agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
                corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
                timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
                nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
                signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
                jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile', 'biz.chat.pickConversation',
					'biz.chat.chooseConversationByCorpId', 'biz.chat.toConversation'] // 必填，需要使用的jsapi列表
            });

            dd.ready(function() {
                $("#btnPickConversation").click(function() {
                    dd.biz.chat.pickConversation({
                        corpId: '{!! array_get($config, 'corpId') !!}',
                        isConfirm:'false', //是否弹出确认窗口，默认为true
                        onSuccess : function(result) {
                            //onSuccess将在选择结束之后调用
                            // 该cid和服务端开发文档-普通会话消息接口配合使用，而且只能使用一次，之后将失效
							// alert(result.cid);
                            // alert(result.title);
							/*{
							 cid: 'xxxx',
							 title:'xxx'
							 }*/

							{{--
                            dd.biz.chat.toConversation({
                                corpId: '{!! array_get($config, 'corpId') !!}', //企业id
                                chatId: result.cid,//会话Id
                                onSuccess : function() {
                                    alert('进入会话.');
								},
                                onFail : function() { alert('进入会话失败'); }
                            });
                            --}}

                            $.post("{{ url('/dingtalk/send_to_conversation') }}", {cid:result.cid, id: "{{$paymentrequest->id}}", _token:"{!! csrf_token() !!}"}, function (data) {

                            }, "json");

                        },
                        onFail : function() { alert('error'); }
                    });
                });

                $("#btnPickConversation3").click(function() {
                    dd.biz.chat.chooseConversationByCorpId({
                        corpId: '{!! array_get($config, 'corpId') !!}',
                        onSuccess : function(result) {
                            //onSuccess将在选择结束之后调用
                            alert(result.chatId);
                            alert(result.title);
							/*{
							 chatId: 'xxxx',
							 title:'xxx'
							 }*/

                            dd.biz.chat.toConversation({
                                corpId: '{!! array_get($config, 'corpId') !!}', //企业id
                                chatId: result.chatId,//会话Id
                                onSuccess : function() {
                                    alert('进入会话.');
                                },
                                onFail : function(error) { alert('进入会话失败'); alert(result.chatId); alert('dd.error: ' + JSON.stringify(error)); }
                            });


                        },
                        onFail : function() { alert('error'); }
                    });
                });

            });

            dd.error(function(error) {
                alert('dd.error: ' + JSON.stringify(error));
            });
			
		});
	</script>

@endsection
