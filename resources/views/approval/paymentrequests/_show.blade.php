@section('main')

@if ($agent->isDesktop())    
    <div class="panel-body">
        <form class="pull-right" action="/approval/paymentrequests/exportitem/{{ $paymentrequest->id }}" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">导出PDF</button>
            </div>
        </form>
        <button class="btn btn-default btn-sm pull-right" id="btnPreview">预览并打印</button>
    </div>
@endif

<!--startprint-->
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
<!--endprint-->

    @yield('for_paymentrequestapprovals_create')

    @if (Auth::user()->id == $paymentrequest->applicant_id and isset($paymentrequest->approversetting->level) and $paymentrequest->approversetting->level == 1)
         {!! Form::open(array('url' => 'approval/paymentrequests/mdestroy/' . $paymentrequest->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}
            {!! Form::submit('撤销', ['class' => 'btn btn-danger btn-sm']) !!}
        {!! Form::close() !!}
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        // window.onbeforeprint = function() {
        //     alert("aaaa");
        // }

        jQuery(document).ready(function(e) {
            // 是个问题：如果是数字字符串，会把签名的0省了
            // var order_number = String(@if (isset($reimbursement->order->number)) {{ $reimbursement->order->number }} @endif);
            // $("#order_number").val($("#order_number2").val());
            // $("#customer_name").val($("#customer_name2").val());
            
            $("#btnPreview").click(function() {
                // bdhtml=window.document.body.innerHTML; 
                // sprnstr="<!--startprint-->"; 
                // eprnstr="<!--endprint-->"; 
                // prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); 
                // prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); 
                // window.document.body.innerHTML=prnhtml; 
                window.print();

                // window.document.body.innerHTML=bdhtml;
            });

            

            // window.onafterprint = function() {
            //     alert("bbbb");
            // }
        });
    </script>

    @yield('for_paymentrequestapprovals_create_script')
@endsection
