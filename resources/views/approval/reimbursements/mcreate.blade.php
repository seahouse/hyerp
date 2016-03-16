@extends('app')

@section('main')
    {!! Form::open(array('url' => 'approval/reimbursements', 'class' => 'form-horizontal')) !!}
        @include('approval.reimbursements._form', ['submitButtonText' => '添加', 'marketprice' => '0.0'])
    {!! Form::close() !!}
@endsection

@section('script')
	<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
	
	<script type="text/javascript">
		
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

				$("#date").click(function() {
					dd.biz.util.datepicker({
					    format: 'yyyy-MM-dd',
					    value: '2015-04-17', //默认显示日期
					    onSuccess : function(result) {
					    	alert(result.value);
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

			});
		});
	</script>
@endsection
