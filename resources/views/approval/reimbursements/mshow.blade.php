@extends('app')

@section('main')
    {!! Form::model($reimbursement, ['class' => 'form-horizontal']) !!}
        @include('approval.reimbursements._form', 
            [
                'submitButtonText' => '提交', 
                'date' => null,
                'customer_id' => null, 
                'amount' => null, 
                'order_number' => null,
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
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            // 是个问题：如果是数字字符串，会把签名的0省了
            // var order_number = String(@if (isset($reimbursement->order->number)) {{ $reimbursement->order->number }} @endif);
            $("#order_number").val($("#order_number2").val());
        });
    </script>
@endsection