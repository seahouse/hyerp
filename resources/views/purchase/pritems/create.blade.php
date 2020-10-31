@extends('navbarerp')

@section('main')
    <h1>添加采购申请单分组明细</h1>
    <hr/>
    
    {!! Form::open(array('url' => 'purchase/prtypeitems', 'class' => 'form-horizontal', 'id' => 'formMain', 'files' => true)) !!}
    @include('purchase.prtypeitems._form',
        [
            'submitButtonText' => '提交',
            'project_name' => null,
            'drawingchecker' => null,
            'pohead_name' => null,
            'item_name' => null,
            'paymentdate' => date('Y-m-d'),
            'customer_name' => null,
            'customer_id' => '0',
            'amount' => '0.0',
            'order_number' => null,
            'order_id' => '0',
            'paydate'   => null,
            'attr' => '',
            'attrdisable' => '',
            'btnclass' => 'btn btn-primary',
        ])
    {!! Form::close() !!}
    
    @include('errors.list')

    <!-- item selector -->
    <div class="modal fade" id="selectItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">选择物品</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '物品名称', 'id' => 'keyItem']) !!}
                        <span class="input-group-btn">
                   		{!! Form::button('查找', ['class' => 'btn btn-default btn-sm disabled', 'id' => 'btnSearchItem']) !!}
                   	</span>
                    </div>
                    {!! Form::hidden('name', null, ['id' => 'name']) !!}
                    {!! Form::hidden('id', null, ['id' => 'id']) !!}
                    {!! Form::hidden('num', null, ['id' => 'num']) !!}
                    <p>
                    <div class="list-group" id="listitem">

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
            $('#selectItemModal').on('show.bs.modal', function (e) {
                $("#listitem").empty();

                var target = $(e.relatedTarget);

//                var modal = $(this);
//                modal.find('#num').val(target.data('num'));

                $.ajax({
                    type: "GET",
                    url: "{!! url('/product/items/getitemsbyprhead/') . '/' . $prtype->prhead_id !!}",
                    success: function(result) {
                        var strhtml = '';
                        $.each(result, function(i, field) {
                            btnId = 'btnSelectItem_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.goods_name + "(" + field.goods_spec + ")</h4><h5>" + field.goods_old_name + "</h5></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listitem").empty().append(strhtml);

                        $.each(result, function(i, field) {
                            btnId = 'btnSelectItem_' + String(i);
                            addBtnClickEventItem(btnId, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            $("#btnSearchItem").click(function() {
                if ($("#keyItem").val() == "") {
                    alert('请输入关键字');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{!! url('/product/items/getitemsbykey/') !!}" + "/" + $("#keyItem").val(),
                    success: function(result) {
                        var strhtml = '';
                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectItem_' + String(i);
                            strhtml += "<button type='button' class='list-group-item' id='" + btnId + "'>" + "<h4>" + field.goods_name + "(" + field.goods_spec + ")</h4><h5>" + field.goods_old_name + "</h5></button>"
                        });
                        if (strhtml == '')
                            strhtml = '无记录。';
                        $("#listitem").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectItem_' + String(i);
                            addBtnClickEventItem(btnId, field);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventItem(btnId, field)
            {
                $("#" + btnId).bind("click", function() {
                    $('#selectItemModal').modal('toggle');
                    $("#item_name").val(field.goods_name);
                    $("#item_id").val(field.goods_id);
//                    $("#item_spec").val(field.goods_spec);
                    $("#item_unit_name").val(field.goods_unit_name);
                });
            }
        })
    </script>
@endsection
