@section('main')

<!--startprint-->
    {!! Form::model($projectsitepurchase, ['class' => 'form-horizontal']) !!}
        @include('approval.projectsitepurchases._form',
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

    {!! Form::model($projectsitepurchase, ['class' => 'form-horizontal']) !!}
        @include('approval.paymentrequests._approvals', 
            [
                'attr' => 'readonly',
                'attrdisable' => 'disabled',
                'btnclass' => 'hidden',
            ])
    {!! Form::close() !!}

    {!! Form::model($projectsitepurchase, ['class' => 'form-horizontal']) !!}
        @include('approval.paymentrequests._approvers', 
            [
                'attr' => 'readonly',
            ])
    {!! Form::close() !!}
<!--endprint-->

    @yield('for_paymentrequestapprovals_create')

{{-- 撤回审批记录 --}}
<?php $paymentrequestretract = $projectsitepurchase->paymentrequestretract; ?>

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
    @if (Auth::user()->id == $projectsitepurchase->applicant_id and isset($projectsitepurchase->approversetting->level) and $projectsitepurchase->approversetting->level == 1)
         {!! Form::open(array('url' => 'approval/paymentrequests/mdestroy/' . $projectsitepurchase->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}
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
                    {!! Form::hidden('paymentrequest_id', $projectsitepurchase->id, ['class' => 'form-control']) !!}
                    {!! Form::submit('确定', ['class' => 'btn btn-sm']) !!}
                {!! Form::close() !!}
                --}}
                <form id="formRetract">
                    {!! csrf_field() !!}
                    {!! Form::text('description', null, ['class' => 'form-control']) !!}
                    {!! Form::hidden('paymentrequest_id', $projectsitepurchase->id, ['class' => 'form-control']) !!}
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

    <script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
    {{--<script src="/js/jquery.media.js"></script>--}}
    {{--<script src="/js/pdf.min.js"></script>--}}
    <script type="text/javascript">
        jQuery(document).ready(function(e) {


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

    @yield('for_paymentrequestapprovals_create_script')
@endsection
