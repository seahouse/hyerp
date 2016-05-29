@extends('app')

@section('main')
	{!! Form::model($reimbursement, ['class' => 'form-horizontal']) !!}
        @include('approval.reimbursements._form', 
            [
                'submitButtonText' => '提交', 
                'date' => null,
                'customer_name' => null,
                'customer_id' => null, 
                'amount' => null, 
                'order_number' => null,
                'order_id' => null,
                'datego' => null,
                'dateback' => null,
                'mealamount' => null,
                'ticketamount' => null,
                'amountAirfares' => null,
                'amountTrain' => null,
                'amountTaxi' => null,
                'amountOtherTicket' => null,
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
	<script type="text/javascript">		
		jQuery(document).ready(function(e) {
			$("#btnAccept").bind("click", function() {
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
							alert('操作成功.');
						else
							alert('操作失败.');
						$('#acceptModal').modal('toggle');
						location.href = "{{ url('approval/reimbursements/mindexmyapproval') }}";
					},
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
