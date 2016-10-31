@section('main')
    {!! Form::model($paymentrequest, ['class' => 'form-horizontal']) !!}
        @include('approval.paymentrequests._form', 
            [
                'submitButtonText' => '提交', 
                'supplier_name' => null,
                'pohead_number' => null,
                'datepay' => null,
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
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    {!! Form::model($paymentrequest, ['class' => 'form-horizontal']) !!}
        @include('approval.paymentrequests._approvals', 
            [
                'attr' => 'readonly',
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    {!! Form::model($paymentrequest, ['class' => 'form-horizontal']) !!}
        @include('approval.paymentrequests._approvers', 
            [
                'attr' => 'readonly',
            ])
    {!! Form::close() !!}

    @yield('for_paymentrequestapprovals_create')
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            // 是个问题：如果是数字字符串，会把签名的0省了
            // var order_number = String(@if (isset($reimbursement->order->number)) {{ $reimbursement->order->number }} @endif);
            // $("#order_number").val($("#order_number2").val());
            // $("#customer_name").val($("#customer_name2").val());
            
            // console.log("{{ $paymentrequest->purchaseorder_hxold->arrival_percent }}");
        });
    </script>

    @yield('for_paymentrequestapprovals_create_script')
@endsection
