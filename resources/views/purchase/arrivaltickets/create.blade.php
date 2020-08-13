@extends('navbarerp')

@section('main')
    <h1>添加到票记录</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchase/arrivaltickets', 'class' => 'form-horizontal']) !!}
        @include('purchase.arrivaltickets._form',
            [
                'attr' => '',
                'btnclass' => 'btn btn-primary',
                'attrdisable' => '',
                'submitButtonText' => '添加',
            ])
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
                    $("#" + $("#selectProjectModal").find('#name').val()).val(field.descrip);
                    $("#" + $("#selectProjectModal").find('#id').val()).val(soheadid);
                    $("#sohead_number").val(field.number);
//					$("#supplier_bank").val(field.bank);
//					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
//					$("#vendbank_id").val(field.vendbank_id);
//					$("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
                });
            }
        });
    </script>
@endsection