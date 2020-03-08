@extends('navbarerp')

@section('title', '投标项目')

@section('main')
    @can('basic_biddinginformation_edit')
    <div class="panel-heading">
        {{--<a href="{{ url('basic/biddinginformations/create') }}" class="btn btn-sm btn-success">新建</a>--}}
        {{--<a href="{{ url('basic/biddinginformations/import') }}" class="btn btn-sm btn-success">导入</a>--}}
        {{--<a href="{{ url('basic/biddinginformationdefinefields') }}" class="btn btn-sm btn-success">维护字段</a>--}}
        {{--@can('basic_biddinginformation_edittable')--}}
            {{--@if (Auth::user()->email == 'admin@admin.com')--}}
                {{--<a href="{{ url('basic/biddinginformations/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>--}}
            {{--@endif--}}
        {{--@endcan--}}
    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/basic/biddinginformations/search', 'class' => 'pull-right form-inline', 'id' => 'frmCondition']) !!}
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
            @can('basic_biddinginformation_export')
            {{--{!! Form::button('导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport']) !!}--}}
                {{--{!! Form::button('清空数据（慎用！）', ['class' => 'btn btn-default btn-sm', 'id' => 'btnClear']) !!}--}}
                {{--<a href="{{ url('basic/biddinginformations/export') }}" class="btn btn-sm btn-success">测试导出</a>--}}
            {{--{!! Form::button('关联工程调试日志到ERP订单', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_gctsrz_sohead_id']) !!}--}}
            @endcan
        </div>
        {!! Form::close() !!}
    </div>

    @if ($biddinginformations->count())
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <?php $types = $biddinginformationdefinefields; ?>
        <table class="table table-hover table-condensed text-nowrap">
            <thead>
            <tr>
                <th>编号</th>
                @foreach($types as $type)
                    <th>{{ $type }}</th>
                @endforeach
                {{--<th>操作</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($biddinginformations as $biddinginformation)
                <tr>
                    <td>
                        {{ $biddinginformation->number }}
                        {{--<a href="#" id="number" name="number" data-type="text" data-pk="{{ $biddinginformation->id }}" data-url="{{ url('basic/biddinginformations/updateedittable') }}">{{ $biddinginformation->number }}</a>--}}
                    </td>
                    @foreach($types as $type)
                        <td>
                            @if (isset($biddinginformation) && null != $biddinginformation->biddinginformationitems->where('key', $type)->first())
                                <a href="#" name="edititem" data-type="text" data-pk="{{ $biddinginformation->biddinginformationitems->where('key', $type)->first()->id }}" data-url="{{ url('basic/biddinginformationitems/updateedittable') }}">{{ $biddinginformation->biddinginformationitems->where('key', $type)->first()->value }}</a>
                            @endif
                        </td>
                    @endforeach
                    {{--<td>--}}
                        {{--<div class="form-inline">--}}
                            {{--<a href="{{ URL::to('/basic/biddinginformations/'.$biddinginformation->id) }}" class="btn btn-success btn-xs pull-left">查看</a>--}}
                            {{--@if ($biddinginformation->closed != 1)--}}
                                {{--<a href="{{ URL::to('/basic/biddinginformations/'.$biddinginformation->id.'/edit') }}" class="btn btn-success btn-xs pull-left">编辑</a>--}}
                            {{--@endif--}}
                            {{--<a href="{{ url('basic/biddinginformations/exportword/' . $biddinginformation->id) }}" class="btn btn-success btn-xs pull-left" target="_blank">导出Word</a>--}}
                            {{--{!! Form::open(array('action' => ['Basic\BiddinginformationController@close', $biddinginformation->id], 'method' => 'post', 'onsubmit' => 'return confirm("确定关闭此记录?");')) !!}--}}
                            {{--{!! Form::submit('关闭', ['class' => 'btn btn-danger btn-xs pull-left']) !!}--}}
                            {{--{!! Form::close() !!}--}}

                            {{--{!! Form::open(array('route' => array('basic.biddinginformations.destroy', $biddinginformation->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-xs']) !!}--}}
                            {{--{!! Form::close() !!}--}}
                        {{--</div>--}}
                    {{--</td>--}}
                </tr>
            @endforeach
            </tbody>

        </table>


        {!! $biddinginformations->setPath('/basic/biddinginformations/edittable')->appends($inputs)->links() !!}


    @else
        <div class="alert alert-warning alert-block">
            <i class="fa fa-warning"></i>
            {{'无记录', [], 'layouts'}}
        </div>
    @endif

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

            {{--var mytable = $('#edittable').editTable({--}}
                {{--data: '{{ json_encode($biddinginformations->toArray()['data'])  }}',           // Fill the table with a js array (this is overridden by the textarea content if not empty)--}}
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
                    url: "{!! url('basic/biddinginformations/export') !!}",
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
                    url: "{!! url('basic/biddinginformations/clear') !!}",
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
