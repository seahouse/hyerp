@extends('navbarerp')

@section('main')
    @can('purchase_purchaseorder_edit')
    <hr>
    {!! Form::model($purchaseorder, ['method' => 'PATCH', 'action' => ['Purchase\PurchaseordersController@update_hx', $purchaseorder->id], 'class' => 'form-horizontal']) !!}
        @include('purchase.purchaseorders._form_hxedit',
            [
                'attr'               => '',
                'attrdisable'       =>'',
                'btnclass'           => 'btn btn-primary',
                'submitButtonText' => '保存'
            ])
    {!! Form::close() !!}
    
    @include('errors.list')

    <div class="modal fade" id="editTaxrateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">编辑税率</h4>
                </div>
                <div class="modal-body">
                    <p>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>金额</th>
                            <th>税率</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyTaxrate">
                        {{--
                        @foreach($purchaseorder->poheadtaxrateasses as $poheadtaxrateass)
                            <tr>
                                <td>
                                    {{ $poheadtaxrateass->name }}
                                </td>
                                <td>
                                    {{ $poheadtaxrateass->amount }}
                                </td>
                                <td>
                                    {{ $poheadtaxrateass->taxrate }}
                                </td>
                                <td>
                                    {!! Form::open(array('route' => array('purchase.poheadtaxrateass.destroy', $poheadtaxrateass->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                                        {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                        --}}
                        </tbody>
                    </table>
                    </p>
                    <p>
                    {!! Form::open(array('url' => 'purchase/poheadtaxrateass', 'class' => 'form-horizontal', 'id' => 'formPoheadtaxrateass')) !!}
                    <div class="form-group">
                        {!! Form::label('name', '名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('amount', '金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('taxrate', '税率:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::text('taxrate', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    {!! Form::hidden('pohead_id', null) !!}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            {!! Form::button('新增', ['class' => 'btn btn-sm', 'id' => 'btnAddPoheadtaxrate']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                    </p>

                </div>
                {{--
                            <div class="modal-footer">
                                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
                            </div>
                --}}
            </div>
        </div>
    </div>
    @else
    无权限。
    @endcan
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $('#editTaxrateModal').on('show.bs.modal', function (e) {
                var text = $(e.relatedTarget);
                var modal = $(this);

//                alert(text.data('pohead_id'));
//                alert(modal.find("input[name='pohead_id']").val());
                modal.find("input[name='pohead_id']").val(text.data('pohead_id'));
            });

            $('#editTaxrateModal').on('shown.bs.modal', function (e) {
                $("#tbodyTaxrate").empty();
                var text = $(e.relatedTarget);
                var modal = $(this);

                refreshPoheadtaxrateassTable();

                {{--$.ajax({--}}
                    {{--type: "GET",--}}
                    {{--url: "{!! url('/purchase/purchaseorders/' . $purchaseorder->id . '/getpoheadtaxrateass_hx') !!}",--}}
                    {{--success: function(result) {--}}
                        {{--var strhtml = '';--}}
                        {{--$.each(result, function(i, field) {--}}
                            {{--btnId = 'btnDeletePoheadtaxrate_' + String(field.id);--}}
                            {{--strhtml += "\--}}
                                {{--\<tr>\--}}
                                {{--\<td>" + field.name + "</td>\--}}
                                     {{--\<td>" + field.amount + "</td>\--}}
                                     {{--\<td>" + field.taxrate + "</td>\--}}
                                     {{--\<td><button type='button' class='btn btn-danger btn-sm' id='" + btnId + "'>删除</button></td>\--}}
                                {{--\</tr>";--}}
                        {{--});--}}
                        {{--modal.find('#tbodyTaxrate').empty().append(strhtml);--}}

                        {{--$.each(result, function(i, field) {--}}
                            {{--btnId = 'btnDeletePoheadtaxrate_' + String(field.id);--}}
                            {{--addBtnClickEventDeletePoheadtaxrate(btnId, field);--}}
                        {{--});--}}
                    {{--},--}}
                    {{--error: function(xhr, ajaxOptions, thrownError) {--}}
                        {{--alert('error');--}}
                    {{--}--}}
                {{--});--}}
            });

            function addBtnClickEventDeletePoheadtaxrate(btnId, field)
            {
                $("#" + btnId).bind("click", function() {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/purchase/poheadtaxrateass/destorybyid') }}" + "/" + field.id,
//                        data: $("form#formPoheadtaxrateass").serialize(),
//                    dataType: "json",
                        error:function(xhr, ajaxOptions, thrownError){
                            alert('error');
                            alert(xhr.status);
                            alert(xhr.responseText);
                            alert(ajaxOptions);
                            alert(thrownError);
                        },
                        success:function(result){
                            alert("删除成功。");
                            refreshPoheadtaxrateassTable();
                        },
                    });
                });
            }

            function refreshPoheadtaxrateassTable() {
                var modal = $("#editTaxrateModal");
                $.ajax({
                    type: "GET",
                    url: "{!! url('/purchase/purchaseorders/' . $purchaseorder->id . '/getpoheadtaxrateass_hx') !!}",
                    success: function(result) {
                        var strhtml = '';
                        $.each(result, function(i, field) {
                            btnId = 'btnDeletePoheadtaxrate_' + String(field.id);
                            strhtml += "\
                                \<tr>\
                                \<td>" + field.name + "</td>\
                                     \<td>" + field.amount + "</td>\
                                     \<td>" + field.taxrate + "</td>\
                                     \<td><button type='button' class='btn btn-danger btn-sm' id='" + btnId + "'>删除</button></td>\
                                \</tr>";
                        });
                        modal.find('#tbodyTaxrate').empty().append(strhtml);

                        $.each(result, function(i, field) {
                            btnId = 'btnDeletePoheadtaxrate_' + String(field.id);
                            addBtnClickEventDeletePoheadtaxrate(btnId, field);
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            }

            $("#btnAddPoheadtaxrate").click(function() {
                if ($("#editTaxrateModal").find("input[name='pohead_id']").val() < 0)
                {
                    alert("没有指定的采购订单，无法录入税率数据。");
                    return;
                }
//                if ($("#editTaxrateModal").find("input[name='pohead_id']").val().trim() == "" || $("#editTaxrateModal").find("#accountnum").val().trim() == "")
//                {
//                    alert("开户行和银行账号不能为空。");
//                    return;
//                }
//                $("form#formPoheadtaxrateass").submit();
//                return;
                $.ajax({
                    type: "POST",
                    url: "{{ url('/purchase/poheadtaxrateass') }}",
                    data: $("form#formPoheadtaxrateass").serialize(),
//                    dataType: "json",
//                    contentType:"application/x-www-form-urlencoded",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert($("form#formPoheadtaxrateass").serialize());
                        alert('error');
                        alert(xhr.status);
                        alert(xhr.responseText);
                        alert(ajaxOptions);
                        alert(thrownError);
                    },
                    success:function(result){
                        alert("新增成功。");
                        refreshPoheadtaxrateassTable();
//                        $('#editTaxrateModal').modal('toggle');
//                        $("#vendbank_id").val(result.id);
//                        $("#supplier_bank").val(result.bankname);
//                        $("#supplier_bankaccountnumber").val(result.accountnum);
                    },
                });
            });


        });
    </script>
@endsection