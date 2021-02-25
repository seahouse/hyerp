@section('main')


<!--startprint-->
    {!! Form::model($epcsecening, ['class' => 'form-horizontal']) !!}
        @include('approval.epcsecenings._form',
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



{{--
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
--}}

<!-- when the approval status is passed, can modify tongue -->
    @if ($epcsecening->status == 0)
        {!! Form::button('修改重量', ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'modal', 'data-target' => '#modifyweightModal']) !!}
    @endif


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
                    url: "{{ url('approval/issuedrawing/' . $epcsecening->id . '/mupdateweight') }}",
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
    <script type="text/javascript">
        jQuery(document).ready(function(e) {



        });
    </script>
@endif

    @yield('for_issuedrawingapprovals_create_script')
@endsection
