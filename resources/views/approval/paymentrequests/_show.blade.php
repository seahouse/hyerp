@section('main')

@if ($agent->isDesktop())    
    <div class="panel-body">
        <a href="{{url('/approval/paymentrequests/' . $paymentrequest->id . '/printpage')}}" target="_blank" class="btn btn-default btn-sm pull-right">打印版页面</a>
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

{{-- 撤回审批记录 --}}
<?php $paymentrequestretract = $paymentrequest->paymentrequestretract; ?>

@if(isset($paymentrequestretract))
    {!! Form::model($paymentrequestretract, ['class' => 'form-horizontal']) !!}
    @include('approval.paymentrequestretractapprovals._approvals',
        [
            'attr' => 'readonly',
            'attrdisable' => 'disabled',
            'btnclass' => 'hidden',
        ])
    {!! Form::close() !!}
@endif

{{-- 下一个审批人 --}}
@if(isset($paymentrequestretract))
    {!! Form::model($paymentrequestretract, ['class' => 'form-horizontal']) !!}
    @include('approval.paymentrequestretractapprovals._approvers',
        [
            'attr' => 'readonly',
        ])
    {!! Form::close() !!}
@endif

    @yield('for_paymentrequestretractapprovals_create')

    <!-- when next approver level is 1 or next approver is nothing, can destory -->
    @if (Auth::user()->id == $paymentrequest->applicant_id and isset($paymentrequest->approversetting->level) and $paymentrequest->approversetting->level == 1)
         {!! Form::open(array('url' => 'approval/paymentrequests/mdestroy/' . $paymentrequest->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}
            {!! Form::submit('撤销', ['class' => 'btn btn-danger btn-sm']) !!}
        {!! Form::close() !!}
    @endif

<div class="modal fade" id="retractModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">请输入撤回的理由</h4>
            </div>
            <div class="modal-body">
                {{--
                {!! Form::open(array('url' => 'approval/paymentrequestretract')) !!}
                    {!! Form::text('retractreason', null, ['class' => 'form-control']) !!}
                    {!! Form::hidden('paymentrequest_id', $paymentrequest->id, ['class' => 'form-control']) !!}
                    {!! Form::submit('确定', ['class' => 'btn btn-sm']) !!}
                {!! Form::close() !!}
                --}}
                <form id="formRetract">
                    {!! csrf_field() !!}
                    {!! Form::text('description', null, ['class' => 'form-control']) !!}
                    {!! Form::hidden('paymentrequest_id', $paymentrequest->id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('status', -1, ['class' => 'form-control']) !!}
                </form>
            </div>
            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnRetract']) !!}
            </div>
        </div>
    </div>
</div>

    {{-- 审批通过后，发起人可以申请撤回 --}}
    {{-- todo: 已撤回的审批单，无法再次撤回 --}}
    @if (Auth::user()->id == $paymentrequest->applicant_id and $paymentrequest->approversetting_id == 0)
        {!! Form::button('申请撤回', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#retractModal']) !!}
        {!! Form::open(array('url' => 'approval/paymentrequests/retract/' . $paymentrequest->id)) !!}
            {{--
            {!! Form::submit('申请撤回', ['class' => 'btn btn-warning btn-sm']) !!}
            {!! Form::button('申请撤回', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#retractModal']) !!}
            --}}
        {!! Form::close() !!}
    @endif
@endsection


@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {            
            $("#btnPreview").click(function() {
                window.print();
            });

            $("#btnRetract").bind("click", function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('approval/paymentrequestretract') }}",
                    data: $("form#formRetract").serialize(),
                    contentType:"application/x-www-form-urlencoded",
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert($("form#formRetract").serialize());
                        alert('error');
                        alert(xhr.status);
                        alert(xhr.responseText);
                        alert(ajaxOptions);
                        alert(thrownError);
                    },
                    success: function(result) {
//                        alert('操作完成.');
                        $('#rejectModal').modal('toggle');
{{--                        location.href = "{{ url('approval/mindexmyapproval') }}";--}}
                        location.reload(true);
                    },
                });
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
