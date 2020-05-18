@extends('navbarerp')

@section('title', '施工标定额')

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }} ">
@endsection

@section('main')
    @can('basic_constructionbidinformation_view')
    <div class="panel-heading">
        {!! Form::button('新建', ['class' => 'btn btn-sm btn-success', 'data-toggle' => 'modal', 'data-target' => '#createModal']) !!}
        {{--<a href="{{ url('basic/constructionbidinformations/import') }}" class="btn btn-sm btn-success">导入</a>--}}
        <a href="{{ url('basic/constructionbidinformationfields') }}" class="btn btn-sm btn-success">维护字段</a>
        @can('basic_constructionbidinformation_edittable')
            @if (Auth::user()->email == 'admin@admin.com')
                <a href="{{ url('basic/constructionbidinformations/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>
            @endif
        @endcan
    </div>
    
    <div class="panel-body">
        <div class="pull-right">
            <p>
            {!! Form::open(['url' => '/basic/constructionbidinformations/search', 'class' => 'form-inline', 'id' => 'frmCondition']) !!}
            <div class="form-group-sm">
                {{--{!! Form::label('createdatelabel', '发起时间:', ['class' => 'control-label']) !!}--}}
                {{--{!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}--}}
                {{--{!! Form::label('createdatelabelto', '-', ['class' => 'control-label']) !!}--}}
                {{--{!! Form::date('createdateend', null, ['class' => 'form-control']) !!}--}}
                {{--{!! Form::select('creator_name', $dtlog_creatornames, null, ['class' => 'form-control', 'placeholder' => '--发起人--']) !!}--}}

                {{--{!! Form::select('template_name', $dtlog_templatenames, null, ['class' => 'form-control', 'placeholder' => '--日志模板--']) !!}--}}


                {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '字段内容']) !!}--}}
                {{--{!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}--}}
            </div>
            {!! Form::close() !!}
            </p>

            @can('basic_constructionbidinformation_export')
                <p>
                {!! Form::open(['url' => '/basic/constructionbidinformations/export', 'class' => 'form-inline', 'id' => 'frmExport']) !!}
                <div class="form-group-sm">
                    {!! Form::select('selectprojecttypes_export', array('SDA半干法系统' => 'SDA半干法系统', '湿法系统' => '湿法系统', 'SNCR系统' => 'SNCR系统', 'SCR系统' => 'SCR系统', '飞灰输送系统' => '飞灰输送系统',
                        '灰库系统' => '灰库系统', '稳定化系统' => '稳定化系统', 'CFB系统' => 'CFB系统', '固定喷雾系统' => '固定喷雾系统', '公用系统' => '公用系统'), null,
                        ['class' => 'form-control selectpicker', 'multiple']) !!}
                    {!! Form::hidden('projecttypes_export', null, []) !!}

                    {!! Form::button('导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport']) !!}
                    {{--                {!! Form::button('清空数据（慎用！）', ['class' => 'btn btn-default btn-sm', 'id' => 'btnClear']) !!}--}}
                    {{--<a href="{{ url('basic/constructionbidinformations/export') }}" class="btn btn-sm btn-success">测试导出</a>--}}
                </div>
                {!! Form::close() !!}
                </p>
            @endcan
        </div>
    </div> 

    
    @if ($constructionbidinformations->count())
        <?php $types = []; ?>
        <?php $simpletypes = []; ?>
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>投标编号</th>
                @foreach($types as $type)
                <th>{{ $type }}</th>
                @endforeach
                @foreach($simpletypes as $simpletype)
                    <th>{{ $simpletype }}</th>
                @endforeach
                {{--<th>备注</th>--}}
                <th width="300px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($constructionbidinformations as $constructionbidinformation)
                <tr>
                    <td>
                        {{ $constructionbidinformation->number }}
                    </td>
                    @foreach($types as $type)
                    <td>
                    @if (isset($constructionbidinformation) && null != $constructionbidinformation->biddinginformationitems->where('key', $type)->first())
                        {{ $constructionbidinformation->biddinginformationitems->where('key', $type)->first()->value }}
                    @endif
                    </td>
                    @endforeach
                    @foreach($simpletypes as $simpletype)
                        <td>
                            @if (isset($constructionbidinformation) && null != $constructionbidinformation->biddinginformationitems->where('key', $simpletype)->first())
                                <?php $value = $constructionbidinformation->biddinginformationitems->where('key', $simpletype)->first()->value; ?>
                                @if ($value == '无' || empty($value))
                                    无
                                @else
                                    有
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    {{--<td>--}}
                        {{--{{ str_limit($constructionbidinformation->remark, 20) }}--}}
                    {{--</td>--}}
                    <td>
                        <div class="form-inline">
{{--                            {!! Form::button('关联销售订单', ['class' => 'btn btn-success btn-xs pull-left', 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal','data-informationid' =>$constructionbidinformation->id]) !!}--}}
                            {{--@can('basic_biddinginformation_resetfieldtype')--}}
                                {{--{!! Form::button('重设字段类别', ['class' => 'btn btn-xs btn-success pull-left', 'data-toggle' => 'modal', 'data-target' => '#resetfieldtypeModal', 'data-biddinginformation_id' => $constructionbidinformation->id]) !!}--}}
                            {{--@endcan--}}
                            <a href="{{ URL::to('/basic/constructionbidinformations/'.$constructionbidinformation->id) }}" class="btn btn-success btn-xs pull-left">查看</a>
                            @if ($constructionbidinformation->closed != 1)
                                <a href="{{ URL::to('/basic/constructionbidinformations/'.$constructionbidinformation->id.'/edit') }}" class="btn btn-success btn-xs pull-left">编辑</a>
                            @endif
{{--                            <a href="{{ url('basic/constructionbidinformations/exportword/' . $constructionbidinformation->id) }}" class="btn btn-success btn-xs pull-left" target="_blank">导出Word</a>--}}
                            {{--{!! Form::open(array('action' => ['Basic\BiddinginformationController@close', $constructionbidinformation->id], 'method' => 'post', 'onsubmit' => 'return confirm("确定关闭此记录?");')) !!}--}}
                            {{--{!! Form::submit('关闭', ['class' => 'btn btn-danger btn-xs pull-left']) !!}--}}
                            {{--{!! Form::close() !!}--}}

                            {!! Form::open(array('route' => array('basic.constructionbidinformations.destroy', $constructionbidinformation->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-xs']) !!}
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $constructionbidinformations->setPath('/basic/constructionbidinformations')->appends($inputs)->links() !!}


    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif

    @else
        无权限
    @endcan


    <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">创建施工标</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'basic/constructionbidinformations/storebyprojecttypes', 'id' => 'frmCreate']) !!}
                    <div class="form-group">
                        {!! Form::select('selectprojecttypes', $projecttypes_constructionbidinformationfield, null,
                            ['class' => 'form-control selectpicker', 'multiple']) !!}
                        {!! Form::hidden('projecttypes', null, []) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                    {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnCreate']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resetfieldtypeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">重设字段类别</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'basic/constructionbidinformations/resetfieldtype', 'id' => 'frmResetfieldtype']) !!}
                    <div class="form-group">
                        {!! Form::select('selectprojecttypes', array('SDA半干法系统' => 'SDA半干法系统', '湿法系统' => '湿法系统', 'SNCR系统' => 'SNCR系统', 'SCR系统' => 'SCR系统', '飞灰输送系统' => '飞灰输送系统',
                            '灰库系统' => '灰库系统', '稳定化系统' => '稳定化系统', 'CFB系统' => 'CFB系统', '固定喷雾系统' => '固定喷雾系统', '公用系统' => '公用系统'), null,
                            ['class' => 'form-control selectpicker', 'multiple']) !!}
                        {!! Form::hidden('projecttypes', null, []) !!}
                        {!! Form::hidden('biddinginformation_id', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                    {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnResetfieldtype']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="selectOrderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">关联销售订单</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '项目编号、项目名称', 'id' => 'keyProject']) !!}

                        <span class="input-group-btn">
                   		    {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchProject']) !!}
                   	    </span>
                    </div>
                    {!! Form::hidden('name', null, ['id' => 'name']) !!}
                    <p>
                    <div class="list-group" id="listsalesorders">

                    </div>
                    </p>
                    <form id="formAccept">
                        {!! csrf_field() !!}
                        {!! Form::hidden('soheadid', 0, ['class' => 'form-control', 'id' => 'soheadid']) !!}
                        {!! Form::hidden('informationid', 0, ['class' => 'form-control', 'id' => 'informationid']) !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/bootstrap-select/js/i18n/defaults-zh_CN.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnExport").click(function() {
                $("input[name='projecttypes_export']").val($("select[name='selectprojecttypes_export']").val());
//                $("form#frmExport").submit();

                $.ajax({
                    type: "POST",
                    url: "{!! url('basic/constructionbidinformations/export') !!}",
                    data : $('#frmExport').serialize(),
                    success: function(result) {
                        location.href = result;
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });

            $("#btnClear").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('basic/constructionbidinformations/clear') !!}",
                    data : $('#frmCondition').serialize(),
                    success: function(result) {
//                        alert(result);
                        location.href = result;
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });

            $("#btnCreate").click(function() {
                $("#frmCreate input[name='projecttypes']").val($("#frmCreate select[name='selectprojecttypes']").val());
//                alert($("input[name='projecttypes']").val());
                $("form#frmCreate").submit();
            });

            $('#resetfieldtypeModal').on('show.bs.modal', function (e) {
                var text = $(e.relatedTarget);
                var modal = $(this);
                modal.find("input[name='biddinginformation_id']").val(text.data('biddinginformation_id'));
                // alert(modal.find('#informationid').val());

                $.get("{{ url('basic/constructionbidinformations/') }}" + "/" + text.data('biddinginformation_id') + "/getbiddinginformationfieldtypes", function(data){
//                    alert(data);
//                    $("#frmResetfieldtype select[name='selectprojecttypes']").val(data);
                    $("#frmResetfieldtype select[name='selectprojecttypes']").selectpicker('val', data);
                });
            });

            $("#btnResetfieldtype").click(function(e) {
//                alert($("#frmResetfieldtype select[name='selectprojecttypes']").val());
//                alert($("#frmResetfieldtype").find("#projecttypes").val($("#frmResetfieldtype").find("#selectprojecttypes").val()));
                $("#frmResetfieldtype input[name='projecttypes']").val($("#frmResetfieldtype select[name='selectprojecttypes']").val());
//                alert($("#frmResetfieldtype input[name='projecttypes']").val());
                $("form#frmResetfieldtype").submit();
            });

            $('#selectOrderModal').on('show.bs.modal', function (e) {
                $("#listsalesorders").empty();

                var text = $(e.relatedTarget);
                var modal = $(this);
                modal.find('#name').val(text.data('name'));
                modal.find('#informationid').val(text.data('informationid'));
                // alert(modal.find('#informationid').val());
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
                        $("#listsalesorders").empty().append(strhtml);

                        $.each(result.data, function(i, field) {
                            btnId = 'btnSelectProject_' + String(i);
                            $informationid=  $("#selectOrderModal").find('#informationid').val();
                            // alert($informationid);
                            addBtnClickEventProject(btnId, field.id, $informationid);
                        });
                        // addBtnClickEvent('btnSelectOrder_0');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('error');
                    }
                });
            });

            function addBtnClickEventProject(btnId, soheadid, informationid)
            {
                $("#" + btnId).bind("click", function() {
                    // $('#selectOrderModal').modal('toggle');
                    // $("#" + $("#selectOrderModal").find('#name').val()).val(field.descrip);
                    // $("#" + $("#selectOrderModal").find('#id').val()).val(soheadid);
                    $("#soheadid").val(soheadid);
                    $("#informationid").val(informationid);
                    // data=[];

// //					$("#supplier_bank").val(field.bank);
// //					$("#supplier_bankaccountnumber").val(field.bankaccountnumber);
// //					$("#vendbank_id").val(field.vendbank_id);
// //					$("#selectSupplierBankModal").find("#vendinfo_id").val(supplierid);
//                     alert(soheadid +"," + informationid);

                    $.ajax({
                        type: "POST",
                        url: "{!! url('/basic/constructionbidinformations/updatesaleorderid/') !!}" ,
                        data: $("form#formAccept").serialize(),
                        // data: {id:soheadid,informationid:informationid},
                        dataType:"json",
                        success: function(result) {
                            if (result.errorcode >= 0)
                            {
                                $('#selectOrderModal').modal('toggle');
                                alert("关联成功。");
                                window.location.reload('true');
                                // redirect('development/fabricdischarges');
                            }
                            else
                                alert(result.errormsg );
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert('error');
                        }
                    });
                });


            }

        });

    </script>
@endsection
