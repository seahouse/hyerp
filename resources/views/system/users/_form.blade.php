<div class="form-group">
    {!! Form::label('name', '姓名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('email', '邮箱:') !!}
    {!! Form::input('email', 'email', null, ['class' => 'form-control', 'disabled']) !!}
</div>

<div class="form-group">
    {!! Form::label('dept_id', '部门:') !!}
    {!! Form::select('dept_id', $deptList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('position', '职位:') !!}
    {!! Form::text('position', null, ['class' => 'form-control', 'id' => 'position']) !!}
</div>

<div class="form-group">
    {!! Form::label('avatar', '头像:') !!}
    {!! Form::file('avatar') !!}
</div>



{{--
<div class="form-group">
    {!! Form::label('password', '密码:') !!}
    {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('password', '确认密码:') !!}
    {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control']) !!}
</div>
--}}

<div class="form-group">
    {!! Form::label('dtuserid', '钉钉员工号:') !!}
    {!! Form::text('dtuserid', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('supplier_name', '供应商:') !!}

    {!! Form::text('supplier_name', null, ['class' => 'form-control', 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-id' => 'pohead_id', 'data-soheadid' => 'sohead_id', 'data-poheadamount' => 'pohead_amount']) !!}
    <a href="#" id="clearid">清除供应商</a>
    {!! Form::hidden('supplier_id', null, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>

<div class="modal fade" id="selectSupplierModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择关联的供应商</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '供应商名称', 'id' => 'keySupplier']) !!}
                    <span class="input-group-btn">
                        {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchSupplier']) !!}
                    </span>
                </div>
                {!! Form::hidden('sname', null, ['id' => 'sname']) !!}
                {!! Form::hidden('sid', null, ['id' => 'sid']) !!}
                <p>
                    <div class="list-group" id="listsuppliers">
                    </div>
                </p>
                <form id="formAccept">
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>
</div>

@section('script')
<script type="text/javascript">
    jQuery(document).ready(function(e) {
        $('#clearid').click(function() {
            $('#supplier_id').val("");
            $('#supplier_name').val("");
        });

        $('#selectSupplierModal').on('show.bs.modal', function(e) {
            $("#listsuppliers").empty();

            var text = $(e.relatedTarget);
            // alert(text.data('id'));

            var modal = $(this);
            modal.find('#sname').val(text.data('sname'));
            modal.find('#sid').val(text.data('sid'));
            // alert(modal.find('#id').val());
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

        function addBtnClickEventSupplier(btnId, supplierid, name, field) {
            $("#" + btnId).bind("click", function() {
                $('#selectSupplierModal').modal('toggle');
                $("#supplier_name").val(field.name);
                $("#supplier_id").val(field.id);
                // $("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
            });
        }
    });
</script>

@endsection