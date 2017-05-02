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
@include('approval.paymentrequests._form_print',
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
@include('approval.paymentrequests._approvals_print',
    [
        'attr' => 'readonly',
        'attrdisable' => 'disabled',
        'btnclass' => 'hidden',
    ])
{!! Form::close() !!}


{{--
    {!! Form::model($paymentrequest, ['class' => 'form-horizontal']) !!}
        @include('approval.paymentrequests._form_print',
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
        @include('approval.paymentrequests._approvals_print', 
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

    <!-- when next approver level is 1 or next approver is nothing, can destory -->
    @if (Auth::user()->id == $paymentrequest->applicant_id and isset($paymentrequest->approversetting->level) and $paymentrequest->approversetting->level == 1)
         {!! Form::open(array('url' => 'approval/paymentrequests/mdestroy/' . $paymentrequest->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}
            {!! Form::submit('撤销', ['class' => 'btn btn-danger btn-sm']) !!}
        {!! Form::close() !!}
    @endif
--}}
@endsection


@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {            
            $("#btnPreview").click(function() {
                window.print();
            });      

        });
    </script>

@if (Agent::isDesktop())
    <script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            DingTalkPC.config({
                agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
                corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
                timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
                nonceStr: "{!! array_get($config, 'nonceStr') !!}", // 必填，生成签名的随机串
                signature: "{!! array_get($config, 'signature') !!}", // 必填，签名
                jsApiList: [] // 必填，需要使用的jsapi列表
            });

            DingTalkPC.ready(function(res) {
                if (DingTalkPC.ua.isInDingTalk)
                    $("a").attr("target", "_self");
            });
        });
    </script>
@endif

    @yield('for_paymentrequestapprovals_create_script')
@endsection
