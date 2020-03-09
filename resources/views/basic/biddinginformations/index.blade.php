@extends('navbarerp')

@section('title', '投标项目')

@section('main')
    @can('basic_biddinginformation_view')
    <div class="panel-heading">
        <a href="{{ url('basic/biddinginformations/create') }}" class="btn btn-sm btn-success">新建</a>
        <a href="{{ url('basic/biddinginformations/import') }}" class="btn btn-sm btn-success">导入</a>
        <a href="{{ url('basic/biddinginformationdefinefields') }}" class="btn btn-sm btn-success">维护字段</a>
        @can('basic_biddinginformation_edittable')
            @if (Auth::user()->email == 'admin@admin.com')
                <a href="{{ url('basic/biddinginformations/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>
            @endif
        @endcan
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
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '字段内容']) !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            @can('basic_biddinginformation_export')
            {!! Form::button('导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport']) !!}
{{--                {!! Form::button('清空数据（慎用！）', ['class' => 'btn btn-default btn-sm', 'id' => 'btnClear']) !!}--}}
                {{--<a href="{{ url('basic/biddinginformations/export') }}" class="btn btn-sm btn-success">测试导出</a>--}}
            {{--{!! Form::button('关联工程调试日志到ERP订单', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_gctsrz_sohead_id']) !!}--}}
            @endcan
        </div>
        {!! Form::close() !!}
    </div> 

    
    @if ($biddinginformations->count())
        <?php $types = ['序号', '名称', '规模', '工艺', '吸收塔（塔型Niro-Seghers-KS；各20t）', '面积', '安装']; ?>
        <?php $simpletypes = ['刮板机斗提', '灰库', '稳定化', 'SNCR']; ?>
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                @foreach($types as $type)
                <th>{{ $type }}</th>
                @endforeach
                @foreach($simpletypes as $simpletype)
                    <th>{{ $simpletype }}</th>
                @endforeach
                {{--<th>备注</th>--}}
                <th width="250px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($biddinginformations as $biddinginformation)
                <tr>
                    <td>
                        {{ $biddinginformation->number }}
                    </td>
                    @foreach($types as $type)
                    <td>
                    @if (isset($biddinginformation) && null != $biddinginformation->biddinginformationitems->where('key', $type)->first())
                        {{ $biddinginformation->biddinginformationitems->where('key', $type)->first()->value }}
                    @endif
                    </td>
                    @endforeach
                    @foreach($simpletypes as $simpletype)
                        <td>
                            @if (isset($biddinginformation) && null != $biddinginformation->biddinginformationitems->where('key', $simpletype)->first())
                                <?php $value = $biddinginformation->biddinginformationitems->where('key', $simpletype)->first()->value; ?>
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
                        {{--{{ str_limit($biddinginformation->remark, 20) }}--}}
                    {{--</td>--}}
                    <td>
                        <div class="form-inline">
                            <a href="{{ URL::to('/basic/biddinginformations/'.$biddinginformation->id) }}" class="btn btn-success btn-xs pull-left">查看</a>
                            @if ($biddinginformation->closed != 1)
                                <a href="{{ URL::to('/basic/biddinginformations/'.$biddinginformation->id.'/edit') }}" class="btn btn-success btn-xs pull-left">编辑</a>
                            @endif
                            <a href="{{ url('basic/biddinginformations/exportword/' . $biddinginformation->id) }}" class="btn btn-success btn-xs pull-left" target="_blank">导出Word</a>
                            {!! Form::open(array('action' => ['Basic\BiddinginformationController@close', $biddinginformation->id], 'method' => 'post', 'onsubmit' => 'return confirm("确定关闭此记录?");')) !!}
                            {!! Form::submit('关闭', ['class' => 'btn btn-danger btn-xs pull-left']) !!}
                            {!! Form::close() !!}

                            {!! Form::open(array('route' => array('basic.biddinginformations.destroy', $biddinginformation->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-xs']) !!}
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $biddinginformations->setPath('/basic/biddinginformations')->appends($inputs)->links() !!}


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
    <script type="text/javascript" src="/js/jquery-editable-select.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
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


            $('#select_xmjlsgrz_sohead')
                .editableSelect({
                    effects: 'slide',
                })
                //                .on('shown.editable-select', function (e) {
                //                    console.log("shown");
                //                    console.log($('#selectProject').val());
                //                    if ($('#selectProject').val() == "--项目--")
                //                        $('#selectProject').val("");
                //                })
                .on('select.editable-select', function (e, li) {
//                    console.log(li.val() + li.text());
                    if (li.val() > 0)
                        $('input[name=xmjlsgrz_sohead_id]').val(li.val());
                    else
                        $('input[name=xmjlsgrz_sohead_id]').val('');
//                    console.log($('input[name=sohead_id]').val());
//                    console.log($('#project_id').val());
                })
            ;

            $('#select_xmjlsgrz_project')
                .editableSelect({
                    effects: 'slide',
                })
                .on('select.editable-select', function (e, li) {
//                    console.log(li.val() + li.text());
                    if (li.val() > 0)
                        $('input[name=xmjlsgrz_project_id]').val(li.val());
                    else
                        $('input[name=xmjlsgrz_project_id]').val('');
//                    console.log($('input[name=sohead_id]').val());
//                    console.log($('#project_id').val());
                })
            ;
        });

    </script>
@endsection
