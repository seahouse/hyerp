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

            {!! Form::label('select_xmjlsgrz_sohead_label', '项目经理施工日志对应订单', ['class' => 'control-label']) !!}
            {!! Form::select('select_xmjlsgrz_sohead', $soheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'select_xmjlsgrz_sohead']) !!}
            {!! Form::hidden('xmjlsgrz_sohead_id', null, ['id' => 'sohead_id']) !!}
            {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、申请人']); !!}--}}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            @if (Auth::user()->email == "admin@admin.com")
            {!! Form::button('关联项目经理施工日志到ERP订单', ['class' => 'btn btn-default btn-sm', 'id' => 'btn_xmjlsgrz_sohead_id']) !!}
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
                <th style="width: 120px">操作</th>
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
                        <a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id) }}" class="btn btn-success btn-sm pull-left" target="_blank">查看</a>
                        {{--<a href="{{ URL::to('/dingtalk/dtlogs/'.$dtlog->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>--}}
                        {{--{!! Form::open(array('route' => array('dingtalk.dtlogs.destroy', $dtlog->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}
                        {{--{!! Form::close() !!}--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $dtlogs->setPath('/dingtalk/dtlogs')->appends([
        'createdatestart' => isset($inputs['createdatestart']) ? $inputs['createdatestart'] : null ,
        'createdateend' => isset($inputs['createdateend']) ? $inputs['createdateend'] : null,
        'creator_name' => isset($inputs['creator_name']) ? $inputs['creator_name'] : null,
        'template_name' => isset($inputs['template_name']) ? $inputs['template_name'] : null,
    ])->links() !!}

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


    </script>
@endsection
