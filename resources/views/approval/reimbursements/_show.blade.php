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
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    {!! Form::model($reimbursement, ['class' => 'form-horizontal']) !!}
        @include('approval.reimbursements._approvals', 
            [
                'attr' => 'readonly',
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    @yield('for_reimbursementapprovals_create')
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            // 是个问题：如果是数字字符串，会把签名的0省了
            // var order_number = String(@if (isset($reimbursement->order->number)) {{ $reimbursement->order->number }} @endif);
            // $("#order_number").val($("#order_number2").val());
            // $("#customer_name").val($("#customer_name2").val());
        });
    </script>

    @yield('for_reimbursementapprovals_create_script')
@endsection
