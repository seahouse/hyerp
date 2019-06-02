@section('main')


@if (Agent::isDesktop())
    <div class="panel-body">
        <a href="{{url('/approval/pppayments/' . $pppayment->id . '/printpage')}}" target="_blank" class="btn btn-default btn-sm pull-right">打印版页面</a>
        <form class="pull-right" action="/approval/pppayments/exportitem/{{ $pppayment->id }}" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">导出PDF</button>
            </div>
        </form>
        <button class="btn btn-default btn-sm pull-right" id="btnPreview">预览并打印</button>
    </div>
@endif

<!--startprint-->
    {!! Form::model($pppayment, ['class' => 'form-horizontal']) !!}
        @include('approval.pppayments._form',
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

{{--<div id="container" style="display: none;">--}}
    {{--<div class="lightbox"></div>--}}
    {{--<div id="pop" class="pop">--}}
        {{--<canvas id="the-canvas"></canvas>--}}
    {{--</div>--}}
{{--</div>--}}

    {!! Form::model($pppayment, ['class' => 'form-horizontal']) !!}
        @include('approval.pppayments._approvals',
            [
                'attr' => 'readonly',
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    {!! Form::model($pppayment, ['class' => 'form-horizontal']) !!}
        @include('approval.pppayments._approvers',
            [
                'attr' => 'readonly',
            ])
    {!! Form::close() !!}
<!--endprint-->

    @yield('for_paymentrequestapprovals_create')

{{-- 撤回审批记录 --}}
<?php $paymentrequestretract = $pppayment->paymentrequestretract; ?>

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
    @if (Auth::user()->id == $pppayment->applicant_id and isset($pppayment->approversetting->level) and $pppayment->approversetting->level == 1)
         {!! Form::open(array('url' => 'approval/paymentrequests/mdestroy/' . $pppayment->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}
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
                    {!! Form::hidden('paymentrequest_id', $pppayment->id, ['class' => 'form-control']) !!}
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
    @if (Auth::user()->id == $pppayment->applicant_id and $pppayment->approversetting_id == 0)
        {!! Form::button('申请撤回', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#retractModal']) !!}
        {!! Form::open(array('url' => 'approval/paymentrequests/retract/' . $pppayment->id)) !!}
            {{--
            {!! Form::submit('申请撤回', ['class' => 'btn btn-warning btn-sm']) !!}
            {!! Form::button('申请撤回', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#retractModal']) !!}
            --}}
        {!! Form::close() !!}
    @endif

    {{-- pdf 预览 --}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        PDF 预览标题
                    </h4>
                </div>
                <div class="modal-body" >
                        <a class="media" id="pdfContainer"
                           @if (isset($pppayment->purchaseorder_hxold->businesscontract)) href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $pppayment->purchaseorder_hxold->id . '/' . $pppayment->purchaseorder_hxold->businesscontract !!}" @else href="" @endif>

                        </a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>

    {{--
    {!! Form::button('预览PDF', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#myModal']) !!}
    <div class="media" style="width: 300px; height: 300px;" id="pdfContainer2"  href="http://www.huaxing-east.cn:2015/HxCgFiles/swht/7592/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf"></div>

    <embed width="100%" height="800px" name="plugin" id="plugin" src="http://www.huaxing-east.cn:2015/HxCgFiles/swht/7592/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf" type="application/pdf" internalinstanceid="68" title="">
    --}}

    {{--
    <a class="pdf" style="" href="/pdfjs/build/generic/web/viewer.html?file=compressed.tracemonkey-pldi-09.pdf" >aaa.pdf</a>
    <a class="pdf" style="" href="/approval/paymentrequests/pdfjs/viewer" >bbb.pdf</a>
    --}}
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
    <script src="/js/jquery.media.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#pdfContainer').media({width:'100%', height:800});
//            $('#pdfContainer2').media({width:'100%', height:800});
        });
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function(e) {




        });
    </script>
@endif

    @yield('for_paymentrequestapprovals_create_script')
@endsection
