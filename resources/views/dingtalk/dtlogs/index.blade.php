@extends('navbarerp')

@section('title', '钉钉日志')

@section('main')
    @can('dingtalk_dtlogs_view')
    <div class="panel-heading">
        <div class="panel-title">钉钉 -- 钉钉日志

        </div> 
    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/dingtalk/dtlogs/search', 'class' => 'pull-right form-inline', 'id' => 'frmCondition']) !!}
        <div class="form-group-sm">
            {!! Form::label('createdatelabel', '发起时间:', ['class' => 'control-label']) !!}
            {!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}
            {!! Form::label('createdatelabelto', '-', ['class' => 'control-label']) !!}
            {!! Form::date('createdateend', null, ['class' => 'form-control']) !!}
            {!! Form::select('creator_name', $dtlog_creatornames, null, ['class' => 'form-control', 'placeholder' => '--发起人--']) !!}

            {!! Form::select('template_name', $dtlog_templatenames, null, ['class' => 'form-control', 'placeholder' => '--日志模板--']) !!}

            {{--{!! Form::label('select_xmjlsgrz_sohead_label', '项目经理施工日志对应订单', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::select('select_xmjlsgrz_sohead', $soheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'select_xmjlsgrz_sohead']) !!}--}}
            {{--{!! Form::hidden('xmjlsgrz_sohead_id', null, ['id' => 'xmjlsgrz_sohead_id']) !!}--}}

            {!! Form::label('select_xmjlsgrz_project_label', '项目经理施工日志对应项目', ['class' => 'control-label']) !!}
            {!! Form::select('select_xmjlsgrz_project', $projectList, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'select_xmjlsgrz_project']) !!}
            {!! Form::hidden('xmjlsgrz_project_id', null, ['id' => 'xmjlsgrz_project_id']) !!}

            {!! Form::select('other', ['xmjlsgrz_sohead_id_undefined' => '还未关联订单的项目经理施工日志', 'btn_xmjlsgrz_peoplecount_undefined' => '施工人数填写不符要求或未填'], null, ['class' => 'form-control', 'placeholder' => '--其他--']) !!}
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '备注']) !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            {{--{!! Form::button('还未关联订单的项目经理施工日志', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_xmjlsgrz_sohead_id_undefined']) !!}--}}
            @if (Auth::user()->email == "admin@admin.com")
            {!! Form::button('关联项目经理施工日志到ERP订单', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_xmjlsgrz_sohead_id']) !!}
            {!! Form::button('关联工程调试日志到ERP订单', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_gctsrz_sohead_id']) !!}
            @endif
        </div>
        {!! Form::close() !!}
    </div> 

    
    @if ($dtlogs->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>创建时间</th>
                <th>发起人</th>
                <th>日志模板</th>
                <th>日志日期</th>
                <th>项目名称</th>
                <th>订单编号</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dtlogs as $dtlog)
                <tr>
                    <td>
                        {{ $dtlog->create_time }}
                    </td>
                    <td>
                        {{ $dtlog->creator_name }}
                    </td>
                    <td>
                        {{ $dtlog->template_name }}
                    </td>
                    <td>
                        @if(isset($dtlog->dtlogitems->where('key','1、日志日期')->first()->value)) {{ $dtlog->dtlogitems->where('key','1、日志日期')->first()->value }} @else{{'-'}} @endif
                    </td>
                    <td>
                        @if($dtlog->template_name == '项目经理施工日志')
                            @if(isset($dtlog->xmjlsgrz_sohead)) {{ $dtlog->xmjlsgrz_sohead->projectjc }} @else {{'-'}} @endif
                        @elseif($dtlog->template_name == '工程调试日志')
                            @if(isset($dtlog->gctsrz_sohead)) {{ $dtlog->gctsrz_sohead->projectjc }} @else {{'-'}} @endif
                         @endif
                    </td>
                    <td>

                        @if($dtlog->template_name == '项目经理施工日志')
                            @if(isset($dtlog->xmjlsgrz_sohead)) {{ $dtlog->xmjlsgrz_sohead->number }} @else {{'-'}} @endif
                        @elseif($dtlog->template_name == '工程调试日志')
                            @if(isset($dtlog->gctsrz_sohead)) {{ $dtlog->gctsrz_sohead->number }} @else {{'-'}} @endif
                        @endif
                    </td>
                    <td>
                        {{ str_limit($dtlog->remark, 20) }}
                    </td>
                    <td>
                        <div class="form-inline">
                            <a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id) }}" class="btn btn-success btn-sm" target="_blank">查看</a>
                            @if($dtlog->template_name == '项目经理施工日志')
                                <a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id.'/attachsohead') }}" class="btn btn-success btn-sm" target="_blank">关联订单</a>
                            @endif
                            <a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id.'/peoplecount') }}" class="btn btn-success btn-sm" target="_blank">人数</a>
                            {{--{!! Form::open(array('route' => array('dingtalk.dtlogs.destroy', $dtlog->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}
                            {{--{!! Form::close() !!}--}}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $dtlogs->setPath('/dingtalk/dtlogs')->appends($inputs)->links() !!}

    {{--{!! $dtlogs->setPath('/dingtalk/dtlogs')->appends([--}}
        {{--'createdatestart' => isset($inputs['createdatestart']) ? $inputs['createdatestart'] : null ,--}}
        {{--'createdateend' => isset($inputs['createdateend']) ? $inputs['createdateend'] : null,--}}
        {{--'creator_name' => isset($inputs['creator_name']) ? $inputs['creator_name'] : null,--}}
        {{--'template_name' => isset($inputs['template_name']) ? $inputs['template_name'] : null,--}}
        {{--'xmjlsgrz_sohead_id' => isset($inputs['xmjlsgrz_sohead_id']) ? $inputs['xmjlsgrz_sohead_id'] : null,--}}
    {{--])->links() !!}--}}

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
            $("#btn_xmjlsgrz_sohead_id").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{!! url('dingtalk/dtlogs/relate_xmjlsgrz_sohead_id') !!}",
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
