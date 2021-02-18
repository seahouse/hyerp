@extends('navbarerp')

@section('title', 'EPC-安装队现场增补')

@section('main')
    @can('approval_epcsecening_edit')
    <div class="panel-heading">

    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/approval/issuedrawing/search', 'class' => 'pull-right form-inline', 'id' => 'frmCondition']) !!}
        <div class="form-group-sm">
            {{--{!! Form::label('createdatelabel', '发起时间:', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}--}}
            {{--{!! Form::label('createdatelabelto', '-', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::date('createdateend', null, ['class' => 'form-control']) !!}--}}
            {{--{!! Form::select('creator_name', $dtlog_creatornames, null, ['class' => 'form-control', 'placeholder' => '--发起人--']) !!}--}}

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

    @if ($epcsecenings->count())
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <table class="table table-hover table-condensed text-nowrap">
            <thead>
            <tr>
                <th>申请日期</th>
                <th>审批编号</th>
                <th>增补项所属设计部门</th>
                <th>增补内容</th>

                <th>申请人</th>
                <th>审批状态</th>
                <th>吴总评论</th>
                <th>吴总定价</th>
            </tr>
            </thead>
            <tbody>
            @foreach($epcsecenings as $epcsecening)
                <tr>
                    <td>
                        {{ $epcsecening->created_at }}
                    </td>
                    <td>
                        {{ $epcsecening->business_id }}
                    </td>
                    <td>
                        {{ $epcsecening->additional_design_department }}
                    </td>
                    <td>
                        {{ $epcsecening->additional_content }}
                    </td>
                    <td>
                        {{ isset($epcsecening->applicant->name) ? $epcsecening->applicant->name : '' }}
                    </td>
                    <td>
                        @if ($epcsecening->status == 1)
                            <div class="text-primary">审批中</div>
                        @elseif ($epcsecening->status == 0)
                            <div class="text-success">已通过</div>
                        @elseif ($epcsecening->status == -1)
                            <div class="text-warning">已拒绝</div>
                        @elseif ($epcsecening->status == -2)
                            <div class="text-danger">已撤回</div>
                        @else
                            <div class="text-danger">--</div>
                        @endif
                    </td>
                    <td>
                        {{ $epcsecening->remark_whl }}
                    </td>
                    <td>
                        <a href="#" name="edititem" data-type="text" data-pk="{{ $epcsecening->id }}" data-url="{{ url('approval/epcsecening/updateedittable') }}">{{ $epcsecening->amount_whl }}</a>
                    </td>
                </tr>
            @endforeach

            {{--@foreach($biddinginformations as $biddinginformation)--}}
                {{--<tr>--}}
                    {{--<td>--}}
                        {{--{{ $biddinginformation->number }}--}}
                        {{--<a href="#" id="number" name="number" data-type="text" data-pk="{{ $biddinginformation->id }}" data-url="{{ url('basic/biddinginformations/updateedittable') }}">{{ $biddinginformation->number }}</a>--}}
                    {{--</td>--}}
                    {{--@foreach($types as $type)--}}
                        {{--<td>--}}
                            {{--@if (isset($biddinginformation) && null != $biddinginformation->biddinginformationitems->where('key', $type)->first())--}}
                                {{--<a href="#" name="edititem" data-type="text" data-pk="{{ $biddinginformation->biddinginformationitems->where('key', $type)->first()->id }}" data-url="{{ url('basic/biddinginformationitems/updateedittable') }}">{{ $biddinginformation->biddinginformationitems->where('key', $type)->first()->value }}</a>--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--@endforeach--}}
                {{--</tr>--}}
            {{--@endforeach--}}
            </tbody>

        </table>


        {!! $epcsecenings->setPath('/approval/epcsecening/edittable')->appends($inputs)->links() !!}


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
