@extends('navbarerp')

@section('main')
    <h1>编辑对应订单</h1>
    <hr/>
    
    {!! Form::model($dtlog, ['class' => 'form-horizontal']) !!}
        @include('dingtalk.dtlogs._form',
            [
                'submitButtonText' => '提交',
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

    {!! Form::model($dtlog, ['method' => 'PATCH', 'action' => ['Dingtalk\DtlogController@updateattachsohead', $dtlog->id], 'class' => 'form-horizontal']) !!}

    @if ($dtlog->template_name == '项目经理施工日志')
        <div class="form-group">
            {!! Form::label('xmjlsgrz_sohead_name', '工程项目', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if ($dtlog->xmjlsgrz_sohead_id > 0)
                    {!! Form::text('xmjlsgrz_sohead_name', '[' . $dtlog->xmjlsgrz_sohead->projectjc . ']-[' . $dtlog->xmjlsgrz_sohead->salesmanager . ']-[' . $dtlog->xmjlsgrz_sohead->number . ']', ['class' => 'form-control', 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name', 'data-id' => 'sohead_id']) !!}
                @else
                    {!! Form::text('xmjlsgrz_sohead_name', null, ['class' => 'form-control', 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name', 'data-id' => 'sohead_id', 'placeholder' => '点击选择']) !!}
                @endif
                    {!! Form::hidden('xmjlsgrz_sohead_id', null, ['id' => 'xmjlsgrz_sohead_id']) !!}
                    {!! Form::submit('设置', ['class' => 'btn btn-primary btn-sm', 'id' => 'btnSubmit']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('number', '关联订单编号', ['class' => 'col-xs-4 col-sm-2  control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('number', $sohead_number, ['class' => 'form-control','readonly']) !!}
            </div>
        </div>
    @endif

    {!! Form::close() !!}



    {!! Form::model($dtlog, ['class' => 'form-horizontal']) !!}

    @foreach($dtlog->dtlogitems as $dtlogitem)
        <div class="form-group">
            {!! Form::label($dtlogitem->key, $dtlogitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text($dtlogitem->key, $dtlogitem->value, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>
    @endforeach
    {!! Form::close() !!}


    @include('errors.list')

    <div class="modal fade" id="selectProjectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">选择项目</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '项目编号、项目名称', 'id' => 'keyProject']) !!}
                        <span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchProject']) !!}
                   	</span>
                    </div>
                    {!! Form::hidden('name', null, ['id' => 'name']) !!}
                    {!! Form::hidden('id', null, ['id' => 'id']) !!}
                    <p>
                    <div class="list-group" id="listproject">

                    </div>
                    </p>
                    <form id="formAccept">
                        {!! csrf_field() !!}

                        {{--                    {!! Form::hidden('reimbursement_id', $reimbursement->id, ['class' => 'form-control']) !!}
                                            {!! Form::hidden('status', 0, ['class' => 'form-control']) !!} --}}
                    </form>
                </div>
                {{--            <div class="modal-footer">
                                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
                            </div>--}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {








            $('#selectProjectModal').on('show.bs.modal', function (e) {
                $("#listproject").empty();

                var text = $(e.relatedTarget);
                // alert(text.data('id'));

                var modal = $(this);
                modal.find('#name').val(text.data('name'));
                modal.find('#id').val(text.data('id'));
                // alert(modal.find('#id').val());
            });

            $("#btnSearchProject").click(function() {
                if ($("#keyProject").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/sales/salesorders/getitemsbykey/') !!}" + "/" + $("#keyProject").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectProject_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.number + "</h4><p>" + field.descrip + "</p></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listproject").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectProject_' + String(i);
                            addBtnClickEventProject(btnId, field.id, field.number, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventProject(btnId, soheadid, name, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectProjectModal').modal('toggle');
//                    $("#" + $("#selectProjectModal").find('#name').val()).val(field.descrip);
//                    $("#" + $("#selectProjectModal").find('#id').val()).val(soheadid);
					$("#xmjlsgrz_sohead_name").val("[" + field.projectjc + "]-[" + field.salesmanager + "]-[" + field.number + "]");
					$("#xmjlsgrz_sohead_id").val(field.id);
                });
            }












        });
    </script>
@endsection
