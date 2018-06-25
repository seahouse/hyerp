@section('main')


@if (Agent::isDesktop())
    <div class="panel-body">
        <a href="{{url('/approval/issuedrawing/' . $issuedrawing->id . '/printpage')}}" target="_blank" class="btn btn-default btn-sm pull-right">打印版页面</a>
        <form class="pull-right" action="/approval/issuedrawing/exportitem/{{ $issuedrawing->id }}" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">导出PDF</button>
            </div>
        </form>
        <button class="btn btn-default btn-sm pull-right" id="btnPreview">预览并打印</button>
    </div>
@endif

<!--startprint-->
    {!! Form::model($issuedrawing, ['class' => 'form-horizontal']) !!}
        @include('approval.issuedrawings._form',
            [
                'submitButtonText' => '提交', 
                'supplier_name' => null,
                'pohead_number' => null,
                'datepay' => null,
                'requestdeliverydate' => null,
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

    {!! Form::model($issuedrawing, ['class' => 'form-horizontal']) !!}
        @include('approval.issuedrawings._approvals',
            [
                'attr' => 'readonly',
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    {!! Form::model($issuedrawing, ['class' => 'form-horizontal']) !!}
        @include('approval.issuedrawings._approvers',
            [
                'attr' => 'readonly',
            ])
    {!! Form::close() !!}
<!--endprint-->

    @yield('for_issuedrawingapprovals_create')

{{-- 撤回审批记录 --}}
<?php $issuedrawingretract = $issuedrawing->issuedrawingretract; ?>

@if(isset($issuedrawingretract))
    {!! Form::model($issuedrawingretract, ['class' => 'form-horizontal']) !!}
    @include('approval.issuedrawingretractapprovals._approvals',
        [
            'attr' => 'readonly',
            'attrdisable' => 'disabled',
            'btnclass' => 'hidden',
        ])
    {!! Form::close() !!}
@endif

{{-- 下一个审批人 --}}
@if(isset($issuedrawingretract))
    {!! Form::model($issuedrawingretract, ['class' => 'form-horizontal']) !!}
    @include('approval.issuedrawingretractapprovals._approvers',
        [
            'attr' => 'readonly',
        ])
    {!! Form::close() !!}
@endif

    @yield('for_issuedrawingretractapprovals_create')

    <!-- when the approval status is passed, can modify tongue -->
{{--
    @if (Auth::user()->id == $issuedrawing->applicant_id and isset($issuedrawing->status) and $issuedrawing->status == 0)
         {!! Form::open(array('url' => 'approval/issuedrawing/mdestroy/' . $issuedrawing->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}
            {!! Form::submit('撤销', ['class' => 'btn btn-danger btn-sm']) !!}
        {!! Form::close() !!}
    @endif
--}}

<div class="modal fade" id="modifyweightModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">修改重量</h4>
            </div>
            <div class="modal-body">
                <form id="formModifyweight" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        {!! Form::label('oldtonnage', '原吨位（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::text('oldtonnage', $issuedrawing->tonnage, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('tonnage', '新吨位（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::text('tonnage', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('reason', '修改原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::text('reason', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    {!! Form::hidden('issuedrawing_id', $issuedrawing->id, ['class' => 'form-control']) !!}
                </form>
            </div>
            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnModifyweight']) !!}
            </div>
        </div>
    </div>
</div>

<!-- when the approval status is passed, can modify tongue -->
    @if (Auth::user()->id == $issuedrawing->applicant_id and $issuedrawing->status == 0)
        {!! Form::button('修改重量', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#modifyweightModal']) !!}
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
                           @if (isset($issuedrawing->purchaseorder_hxold->businesscontract)) href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $issuedrawing->purchaseorder_hxold->id . '/' . $issuedrawing->purchaseorder_hxold->businesscontract !!}" @else href="" @endif>

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
    <a class="pdf" style="" href="/approval/issuedrawings/pdfjs/viewer" >bbb.pdf</a>
    --}}
@endsection


@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {            
            $("#btnPreview").click(function() {
                window.print();
            });

            $("#btnModifyweight").bind("click", function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('approval/issuedrawing/' . $issuedrawing->id . '/mupdateweight') }}",
                    data: $("form#formModifyweight").serialize(),
                    contentType:"application/x-www-form-urlencoded",
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert($("form#formModifyweight").serialize());
                        alert('error');
                        alert(xhr.status);
                        alert(xhr.responseText);
                        alert(ajaxOptions);
                        alert(thrownError);
                    },
                    success: function(result) {
//                        alert('操作完成.');
                        $('#modifyweightModal').modal('toggle');
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

    <script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
    {{--<script src="/js/jquery.media.js"></script>--}}
    {{--<script src="/js/pdf.min.js"></script>--}}
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

//            $(function() {
//                $('a.media').media({width:800, height:600});
//            });

//            console.log($("#showPdf").attr("href"));
            console.log(DingTalkPC.ua.isInDingTalk);
            if (DingTalkPC.ua.isInDingTalk)
                ;
            else
            {
                $("#showPdf").click(function() {
                    $('#myModal').modal();
//                    location.href = 'http://www.huaxing-east.cn:2015/pdfjs/build/generic/web/viewer.html?file=' + $("#showPdf").attr("href");
                    return false;
                });
            }

            DingTalkPC.ready(function(res) {
                if (DingTalkPC.ua.isInDingTalk)
                {
                    $("a").attr("target", "_self");

                    $("#showPdf").click(function() {
                        location.href = 'http://www.huaxing-east.cn:2015/pdfjs/build/generic/web/viewer.html?file=' + $("#showPdf").attr("href");
//                        DingTalkPC.biz.util.openLink({
//                            url: $("#showPdf").attr("href"),
//                            onSuccess : function(result) {
//                                /**/
//                            },
//                            onFail : function() {}
//                        });
                        return false;
                    });

                    $("#showPaymentnode").click(function() {
                        DingTalkPC.biz.util.openLink({
                            url: $("#showPaymentnode").attr("href"),
//                            url: "http://www.huaxing-east.cn:2015/HxCgFiles/swht/7592/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf",//要打开链接的地址
                            onSuccess : function(result) {
                                /**/
                            },
                            onFail : function() {}
                        })
                        return false;
                    });
                }
                else
                {

//                    $("#showPdf").display = 'none';
                }


            });

            function showPdf() {
                var container = document.getElementById("container");
                container.style.display = "block";
                var url = 'http://www.huaxing-east.cn:2015/HxCgFiles/swht/7592/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf';
                PDFJS.workerSrc = '/js/pdf.worker.min.js';
                PDFJS.getDocument(url).then(function getPdfHelloWorld(pdf) {
                    pdf.getPage(1).then(function getPageHelloWorld(page) {
                        var scale = 1;
                        var viewport = page.getViewport(scale);
                        var canvas = document.getElementById('the-canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                });
            }

            $("#btnTest").click(function() {
                showPdf();

            });
        });
    </script>
@endif

    @yield('for_issuedrawingapprovals_create_script')
@endsection
