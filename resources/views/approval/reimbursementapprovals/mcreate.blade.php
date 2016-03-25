@extends('app')

@section('main')
	{!! Form::model($reimbursement, ['class' => 'form-horizontal']) !!}
        @include('approval.reimbursements._form', 
            [
                'submitButtonText' => '提交', 
                'date' => null,
                'customer_id' => null, 
                'amount' => null, 
                'order_id' => null,
                'datego' => null,
                'dateback' => null,
                'mealamount' => null,
                'ticketamount' => null,
                'stayamount' => null,
                'otheramount' => null,
                'attr' => 'readonly',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

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
{{--
    {!! Form::open(array('url' => 'approval/reimbursementapprovals/mstore', 'class' => 'form-horizontal')) !!}
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
    {!! Form::close() !!}
--}}

@endsection


@section('script')

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