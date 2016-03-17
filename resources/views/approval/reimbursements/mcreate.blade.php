@extends('app')

@section('main')
    {!! Form::open(array('url' => 'approval/reimbursements/mstore', 'class' => 'form-horizontal')) !!}
        @include('approval.reimbursements._form', 
        	[
        		'submitButtonText' => '添加', 
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
        	])
    {!! Form::close() !!}
@endsection

@section('script')
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
	
	<script type="text/javascript">
		
		jQuery(document).ready(function(e) {
			dd.ready(function() {				

				dd.device.base.getUUID({
				    onSuccess : function(data) {
				    	alert(data.uuid);
				        /*
				        {
				            uuid: '3udbhg98ddlljokkkl' //
				        }
				        */
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
@endsection
