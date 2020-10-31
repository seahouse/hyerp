@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($prtypeitem, ['method' => 'PATCH', 'action' => ['Purchase\PrtypeitemController@update', $prtypeitem->id], 'class' => 'form-horizontal']) !!}
        @include('purchase.prtypeitems._form',
            [
                'submitButtonText' => '保存',
                'attr' => '',
                'btnclass' => 'btn btn-primary',
            ])
    {!! Form::close() !!}
    
    @include('errors.list')

    <!-- supplier selector -->
    <div class="modal fade" id="selectSupplierModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">选择供应商</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '供应商名称', 'id' => 'keySupplier']) !!}
                        <span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm disabled', 'id' => 'btnSearchSupplier']) !!}
                   	</span>
                    </div>
                    {!! Form::hidden('name', null, ['id' => 'name']) !!}
                    {!! Form::hidden('id', null, ['id' => 'id']) !!}
                    <p>
                    <div class="list-group" id="listsuppliers">

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
            $('#selectSupplierModal').on('show.bs.modal', function (e) {
                $("#listsuppliers").empty();

                var text = $(e.relatedTarget);
                // alert(text.data('id'));

//                var modal = $(this);
//                modal.find('#name').val(text.data('name'));
//                modal.find('#id').val(text.data('id'));
            });

            $("#btnSearchSupplier").click(function() {
                if ($("#keySupplier").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/purchase/vendinfos/getitemsbykey/') !!}" + "/" + $("#keySupplier").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectCustomer_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listsuppliers").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectCustomer_' + String(i);
                            addBtnClickEventSupplier(btnId, field.id, field.name, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventSupplier(btnId, supplierid, name, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectSupplierModal').modal('toggle');
                    $("#" + $("#selectSupplierModal").find('#name').val()).val(name);
                    $("#" + $("#selectSupplierModal").find('#id').val()).val(supplierid);
                    $("#supplier_bank").val(field.bank);
                    $("#supplier_bankaccountnumber").val(field.bankaccountnumber);
                    $("#vendbank_id").val(field.vendbank_id);
                    $("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
                    // $("#supplier_bank2").val(field.bank);
                    // $("#supplier_bankaccountnumber2").val(field.bankaccountnumber);
                    // $("#vendbank2_id").val(field.vendbank_id);
                    $("#selectSupplierBankModal2").find("#vendinfo_id").val(supplierid);
                });
            }
        })
    </script>
@endsection
