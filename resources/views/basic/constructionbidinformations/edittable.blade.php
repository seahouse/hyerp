@extends('navbarerp')

@section('title', '施工标编辑')

@section('main')
    @can('basic_constructionbidinformation_edit')
    <div class="panel-heading">
        {{--<a href="{{ url('basic/constructionbidinformations/create') }}" class="btn btn-sm btn-success">新建</a>--}}
        {{--<a href="{{ url('basic/constructionbidinformations/import') }}" class="btn btn-sm btn-success">导入</a>--}}
        {{--<a href="{{ url('basic/constructionbidinformationdefinefields') }}" class="btn btn-sm btn-success">维护字段</a>--}}
        {{--@can('basic_constructionbidinformation_edittable')--}}
            {{--@if (Auth::user()->email == 'admin@admin.com')--}}
                {{--<a href="{{ url('basic/constructionbidinformations/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>--}}
            {{--@endif--}}
        {{--@endcan--}}
    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/basic/constructionbidinformations/search', 'class' => 'pull-right form-inline', 'id' => 'frmCondition']) !!}
        <div class="form-group-sm">
            {{--{!! Form::label('createdatelabel', '发起时间:', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}--}}
            {{--{!! Form::label('createdatelabelto', '-', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::date('createdateend', null, ['class' => 'form-control']) !!}--}}
            {{--{!! Form::select('creator_name', $dtlog_creatornames, null, ['class' => 'form-control', 'placeholder' => '--发起人--']) !!}--}}

            {{--{!! Form::select('template_name', $dtlog_templatenames, null, ['class' => 'form-control', 'placeholder' => '--日志模板--']) !!}--}}


            {{--{!! Form::label('select_xmjlsgrz_project_label', '项目经理施工日志对应项目', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::select('select_xmjlsgrz_project', $projectList, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'select_xmjlsgrz_project']) !!}--}}
            {{--{!! Form::hidden('xmjlsgrz_project_id', null, ['id' => 'xmjlsgrz_project_id']) !!}--}}

            {{--{!! Form::select('other', ['xmjlsgrz_sohead_id_undefined' => '还未关联订单的项目经理施工日志', 'btn_xmjlsgrz_peoplecount_undefined' => '施工人数填写不符要求或未填'], null, ['class' => 'form-control', 'placeholder' => '--其他--']) !!}--}}
            {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '备注']) !!}--}}
            {{--{!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}--}}
            @can('basic_constructionbidinformation_export')
            {{--{!! Form::button('导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport']) !!}--}}
                {{--{!! Form::button('清空数据（慎用！）', ['class' => 'btn btn-default btn-sm', 'id' => 'btnClear']) !!}--}}
                {{--<a href="{{ url('basic/constructionbidinformations/export') }}" class="btn btn-sm btn-success">测试导出</a>--}}
            {{--{!! Form::button('关联工程调试日志到ERP订单', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_gctsrz_sohead_id']) !!}--}}
            @endcan
        </div>
        {!! Form::close() !!}
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <table class="table table-hover table-condensed text-nowrap">
        <thead>
        <tr>
            <th>名称</th>
            <th>采购方</th>
            <th>规格及技术要求</th>
            <th>一条线</th>
            <th>二条线</th>
            <th>三条线</th>
            <th>四条线</th>
            <th>单位</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        @foreach($constructionbidinformation->constructionbidinformationitems as $constructionbidinformationitem)
            <tr>
                <td>
                    {{ $constructionbidinformationitem->key }}
                    {{--<a href="#" id="number" name="number" data-type="text" data-pk="{{ $constructionbidinformation->id }}" data-url="{{ url('basic/constructionbidinformations/updateedittable') }}">{{ $constructionbidinformation->number }}</a>--}}
                </td>
                <td>
                    <a href="#" name="edititem_select" data-type="select" data-pk="{{ $constructionbidinformationitem->id }}" data-name="purchaser" data-value="{{ $constructionbidinformationitem->purchaser }}" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->purchaser }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="specification_technicalrequirements" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->specification_technicalrequirements }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="value_line1" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->value_line1 }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="value_line2" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->value_line2 }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="value_line3" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->value_line3 }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="value_line4" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->value_line4 }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="unit" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->unit }}</a>
                </td>
                <td>
                    <a href="#" name="edititem" data-type="text" data-pk="{{ $constructionbidinformationitem->id }}" data-name="remark" data-url="{{ url('basic/constructionbidinformationitems/updateedittable') }}">{{ $constructionbidinformationitem->remark }}</a>
                </td>

                {{--<td>--}}
                {{--<div class="form-inline">--}}
                {{--<a href="{{ URL::to('/basic/constructionbidinformations/'.$constructionbidinformation->id) }}" class="btn btn-success btn-xs pull-left">查看</a>--}}
                {{--@if ($constructionbidinformation->closed != 1)--}}
                {{--<a href="{{ URL::to('/basic/constructionbidinformations/'.$constructionbidinformation->id.'/edit') }}" class="btn btn-success btn-xs pull-left">编辑</a>--}}
                {{--@endif--}}
                {{--<a href="{{ url('basic/constructionbidinformations/exportword/' . $constructionbidinformation->id) }}" class="btn btn-success btn-xs pull-left" target="_blank">导出Word</a>--}}
                {{--{!! Form::open(array('action' => ['Basic\BiddinginformationController@close', $constructionbidinformation->id], 'method' => 'post', 'onsubmit' => 'return confirm("确定关闭此记录?");')) !!}--}}
                {{--{!! Form::submit('关闭', ['class' => 'btn btn-danger btn-xs pull-left']) !!}--}}
                {{--{!! Form::close() !!}--}}

                {{--{!! Form::open(array('route' => array('basic.constructionbidinformations.destroy', $constructionbidinformation->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-xs']) !!}--}}
                {{--{!! Form::close() !!}--}}
                {{--</div>--}}
                {{--</td>--}}
            </tr>
        @endforeach
        </tbody>

    </table>




    @else
        无权限
    @endcan
@endsection

@section('script')
    {{--<script type="text/javascript" src="/js/jquery-editable-select.js"></script>--}}
    <script type="text/javascript" src="/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    {{--<script type="text/javascript" src="/editTable/jquery.edittable.min.js"></script>--}}
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
//            $('a[name="number"]').editable(
//                {
//                    ajaxOptions: {
//                        headers: {
//                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                        }
//                    },
//                }
//            );

            $('a[name="edititem"]').editable(
                {
                    ajaxOptions: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                }
            );

            $('a[name="edititem_select"]').editable(
                {
                    ajaxOptions: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    source: [{ value: "华星东方", text: "华星东方" }, { value: "投标人", text: "投标人" }],
                }
            );

            {{--var mytable = $('#edittable').editTable({--}}
                {{--data: '{{ json_encode($constructionbidinformations->toArray()['data'])  }}',           // Fill the table with a js array (this is overridden by the textarea content if not empty)--}}
                {{--tableClass: 'inputtable',   // Table class, for styling--}}
                {{--jsonData: false,        // Fill the table with json data (this will override data property)--}}
                {{--headerCols: false,      // Fix columns number and names (array of column names)--}}
                {{--maxRows: 999,           // Max number of rows which can be added--}}
                {{--first_row: true,        // First row should be highlighted?--}}
                {{--row_template: false,    // An array of column types set in field_templates--}}
                {{--field_templates: false, // An array of custom field type objects--}}

                {{--// Validate fields--}}
                {{--validate_field: function (col_id, value, col_type, $element) {--}}
                    {{--return true;--}}
                {{--}--}}
            {{--});--}}

            $("#btnExport").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('basic/constructionbidinformations/export') !!}",
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

            $("#btn_gctsrz_sohead_id").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('dingtalk/dtlogs/relate_gctsrz_sohead_id') !!}",
                    data : $('#frmCondition').serialize(),
                    success: function(result) {
                        // alert(result);
                        // alert(result.errmsg);
                        if (result.errcode == 0)
                        {
                            alert(result.errmsg);
                        }
                        else
                            alert(JSON.stringify(result));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });


        });

    </script>
@endsection
