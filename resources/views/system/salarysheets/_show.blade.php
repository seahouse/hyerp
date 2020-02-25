@section('main')

    {{--<style type="text/css">--}}
        {{--.lightbox{--}}
            {{--position: fixed;--}}
            {{--top: 0px;--}}
            {{--left: 0px;--}}
            {{--height: 100%;--}}
            {{--width: 100%;--}}
            {{--z-index: 7;--}}
            {{--opacity: 0.3;--}}
            {{--display: block;--}}
            {{--background-color: rgb(0, 0, 0);--}}
        {{--}--}}
        {{--.pop{--}}
            {{--position: absolute;--}}
            {{--left: 50%;--}}
            {{--width: 894px;--}}
            {{--margin-left: -447px;--}}
            {{--z-index: 9;--}}
        {{--}--}}
    {{--</style>--}}


<!--startprint-->
    {!! Form::model($salarysheet, ['class' => 'form-horizontal']) !!}
        @include('system.salarysheets._form',
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


    @if (!isset($salarysheet->salarysheetreply))
        @include('system.salarysheetreplies._form',
            [
                'acceptButtonText' => '确认无误',
                'rejectButtonText' => '存有异议',
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
    @endif

<!--endprint-->

    {{--@yield('for_paymentrequestapprovals_create')--}}




    {{--@yield('for_paymentrequestretractapprovals_create')--}}

    {{--<!-- when next approver level is 1 or next approver is nothing, can destory -->--}}
    {{--@if (Auth::user()->id == $paymentrequest->applicant_id and isset($paymentrequest->approversetting->level) and $paymentrequest->approversetting->level == 1)--}}
         {{--{!! Form::open(array('url' => 'approval/paymentrequests/mdestroy/' . $paymentrequest->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定撤销此记录?");')) !!}--}}
            {{--{!! Form::submit('撤销', ['class' => 'btn btn-danger btn-sm']) !!}--}}
        {{--{!! Form::close() !!}--}}
    {{--@endif--}}


    {{-- 审批通过后，发起人可以申请撤回 --}}
    {{-- todo: 已撤回的审批单，无法再次撤回 --}}
    {{--@if (Auth::user()->id == $paymentrequest->applicant_id and $paymentrequest->approversetting_id == 0)--}}
        {{--{!! Form::button('申请撤回', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#retractModal']) !!}--}}
        {{--{!! Form::open(array('url' => 'approval/paymentrequests/retract/' . $paymentrequest->id)) !!}--}}

        {{--{!! Form::close() !!}--}}
    {{--@endif--}}

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
                           @if (isset($salarysheet->purchaseorder_hxold->businesscontract)) href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $salarysheet->purchaseorder_hxold->id . '/' . $salarysheet->purchaseorder_hxold->businesscontract !!}" @else href="" @endif>

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
    <script src="http://g.alicdn.com/dingding/open-develop/1.0.0/dingtalk.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function(e) {

            $("#btnAccept").bind("click", function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('system/salarysheetreply/mstore') }}",
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
                        {
                            alert('操作成功.');
                            {{--
                                                        // dd.biz.ding.post({
                                                        //     users : ['manager1200'],//用户列表，工号
                                                        //     corpId: '{!! array_get($config, 'corpId') !!}', //企业id
                                                        //     type: 2, //钉类型 1：image  2：link
                                                        //     alertType: 2,
                                                        //     alertDate: {"format":"yyyy-MM-dd HH:mm","value":"2015-05-09 08:00"},
                                                        //     attachment: {
                                                        //         title: '华星审批',
                                                        //         url: '',
                                                        //         image: '',
                                                        //         text: '2222'
                                                        //     },
                                                        //     text: '有一个报销需要您审批', //消息
                                                        //     onSuccess : function() {},
                                                        //     onFail : function() {}
                                                        // });
                            --}}
                        }
                        else
                            alert('操作失败：' + result);
                        $('#acceptModal').modal('toggle');
                        {{--location.href = "{!!  urldecode(url('system/salarysheetreply') . '?' . ($_SERVER['QUERY_STRING'])) !!}";--}}
                        location.reload();
                    },
                });
            });

            $("#btnReject").bind("click", function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('system/salarysheetreply/mstore') }}",
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
{{--                        location.href = "{{ url('approval/mindexmyapproval') }}";--}}
                        location.reload();
                    },
                });
            });


            dd.config({
                agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
                corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
                timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
                nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
                signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
                jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile', 'biz.chat.pickConversation',
                    'biz.chat.chooseConversationByCorpId', 'biz.chat.toConversation'] // 必填，需要使用的jsapi列表
            });

            dd.ready(function() {

                $("#btnPickConversation3").click(function() {
                    dd.biz.chat.chooseConversationByCorpId({
                        corpId: '{!! array_get($config, 'corpId') !!}',
                        onSuccess : function(result) {
                            //onSuccess将在选择结束之后调用
                            alert(result.chatId);
                            alert(result.title);
                            /*{
                             chatId: 'xxxx',
                             title:'xxx'
                             }*/

                            dd.biz.chat.toConversation({
                                corpId: '{!! array_get($config, 'corpId') !!}', //企业id
                                chatId: result.chatId,//会话Id
                                onSuccess : function() {
                                    alert('进入会话.');
                                },
                                onFail : function(error) { alert('进入会话失败'); alert(result.chatId); alert('dd.error: ' + JSON.stringify(error)); }
                            });


                        },
                        onFail : function() { alert('error'); }
                    });
                });

            });

            dd.error(function(error) {
                alert('dd.error: ' + JSON.stringify(error));
            });

        });
    </script>
    <script src="/js/jquery.watermark.js"></script>
    <script type="text/javascript">

    $('form').watermark({
        texts : ['{!! $salarysheet->username !!}'], //水印文字
        textColor : "#d2d2d2", //文字颜色
        textFont : '14px 微软雅黑', //字体
        width : 100, //水印文字的水平间距
        height : 100,  //水印文字的高度间距（低于文字高度会被替代）
        textRotate : -30 //-90到0， 负数值，不包含-90
    });  
    </script>  
@endsection
