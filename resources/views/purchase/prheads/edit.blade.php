@extends('navbarerp')

@section('main')
<h1>编辑</h1>
<hr />

{!! Form::model($prhead, ['method' => 'PATCH', 'action' => ['Purchase\PrheadController@update', $prhead->id], 'class' => 'form-horizontal']) !!}
@include('purchase.prheads._form',
[
'submitButtonText' => '保存',
'attr' => '',
'btnclass' => 'btn btn-primary',
])
{!! Form::close() !!}

@include('errors.list')

<div class="modal fade" id="selectModalSupplier" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">选择供应商</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '供应商名称', 'id' => 'keySupplier']) !!}
                    <span class="input-group-btn">
                        {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearch']) !!}
                    </span>
                </div>
                {!! Form::hidden('name1', null, ['id' => 'name1']) !!}
                {!! Form::hidden('id1', null, ['id' => 'id1']) !!}
                <p>
                    <div class="list-group" id="item_list">
                    </div>
                </p>
                <form id="formAccept1">
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    jQuery(document).ready(function(e) {
        $('#selectModalSupplier').on('show.bs.modal', function(e) {
            $("#item_list").empty();

            var text = $(e.relatedTarget);
            var modal = $(this);

            modal.find('#name1').val(text.data('name'));
            modal.find('#id1').val(text.data('id'));
        });

        $("#btnSearch").click(function() {
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
                        btnId = 'btnSelectSupplier_' + String(i);
                        strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.name + "</h4></button>"
                    });
                    if (strhtml == '')
                        strhtml = '无记录。';
                    $("#item_list").empty().append(strhtml);

                    $.each(result.data, function(i, field) {
                        btnId = 'btnSelectSupplier_' + String(i);
                        addBtnClickEvent1(btnId, field);
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert('error');
                }
            });
        });

        function addBtnClickEvent1(btnId, field) {
            $("#" + btnId).bind("click", function() {
                $('#selectModalSupplier').modal('toggle');

                var html = [];
                html.push("<tr>");
                html.push("<td>");
                html.push("<a href='javascript:void(0);' onclick='window.removeitemClick(this);'>删除</a>");
                html.push("</td>");
                html.push("<td>");
                html.push("<label><input type='checkbox' onclick='window.markSelected(this);'> " + field.name + "</label>");
                html.push("<input type='hidden' name='suppliers[]' value='" + field.id + "'>")
                html.push("<input type='hidden' name='chk_suppliers[]' class='chk' value='0'>")
                html.push("</td>");
                html.push("</tr>");
                $("#tblBody").append(html.join(''));
            });
        }

        // 移除整行
        window.removeitemClick = function(it) {
            $(it).parent().parent().remove();
            return false;
        }

        // 设置是否选中
        window.markSelected = function(it) {
            $(it).parent().parent().children('.chk').val($(it).is(":checked") ? 1 : 0);
        }
    })
</script>
@endsection