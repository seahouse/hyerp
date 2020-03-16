@extends('navbarerp')

@section('title', '投标项目')

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }} ">
@endsection

@section('main')
    @can('basic_biddinginformation_view')
    <div class="panel-heading">
        <a href="{{ url('basic/biddinginformations/create') }}" class="btn btn-sm btn-success">测试历史下拉用</a>
        {!! Form::button('新建', ['class' => 'btn btn-sm btn-success', 'data-toggle' => 'modal', 'data-target' => '#createModal']) !!}
        <a href="{{ url('basic/biddinginformations/import') }}" class="btn btn-sm btn-success">导入</a>
        <a href="{{ url('basic/biddinginformationdefinefields') }}" class="btn btn-sm btn-success">维护字段</a>
        @can('basic_biddinginformation_edittable')
            @if (Auth::user()->email == 'admin@admin.com')
                <a href="{{ url('basic/biddinginformations/edittable') }}" class="btn btn-sm btn-success">高级编辑</a>
            @endif
        @endcan
    </div>
    
    <div class="panel-body">
        <div class="pull-right">
            <p>
            {!! Form::open(['url' => '/basic/biddinginformations/search', 'class' => 'form-inline', 'id' => 'frmCondition']) !!}
            <div class="form-group-sm">
                {{--{!! Form::label('createdatelabel', '发起时间:', ['class' => 'control-label']) !!}--}}
                {{--{!! Form::date('createdatestart', null, ['class' => 'form-control']) !!}--}}
                {{--{!! Form::label('createdatelabelto', '-', ['class' => 'control-label']) !!}--}}
                {{--{!! Form::date('createdateend', null, ['class' => 'form-control']) !!}--}}
                {{--{!! Form::select('creator_name', $dtlog_creatornames, null, ['class' => 'form-control', 'placeholder' => '--发起人--']) !!}--}}

                {{--{!! Form::select('template_name', $dtlog_templatenames, null, ['class' => 'form-control', 'placeholder' => '--日志模板--']) !!}--}}


                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '字段内容']) !!}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            </div>
            {!! Form::close() !!}
            </p>

            <p>
            {!! Form::open(['url' => '/basic/biddinginformations/export', 'class' => 'form-inline', 'id' => 'frmExport']) !!}
            <div class="form-group-sm">
                {!! Form::select('selectprojecttypes_export', array('SDA半干法系统' => 'SDA半干法系统', '湿法系统' => '湿法系统', 'SNCR系统' => 'SNCR系统', 'SCR系统' => 'SCR系统', '飞灰输送系统' => '飞灰输送系统',
                    '灰库系统' => '灰库系统', '稳定化系统' => '稳定化系统', 'CFB系统' => 'CFB系统', '固定喷雾系统' => '固定喷雾系统', '公用系统' => '公用系统'), null,
                    ['class' => 'form-control selectpicker', 'multiple']) !!}
                {!! Form::hidden('projecttypes_export', null, []) !!}

                @can('basic_biddinginformation_export')
                    {!! Form::button('导出', ['class' => 'btn btn-default btn-sm', 'id' => 'btnExport']) !!}
                    {{--                {!! Form::button('清空数据（慎用！）', ['class' => 'btn btn-default btn-sm', 'id' => 'btnClear']) !!}--}}
                    {{--<a href="{{ url('basic/biddinginformations/export') }}" class="btn btn-sm btn-success">测试导出</a>--}}
                @endcan
            </div>
            {!! Form::close() !!}
            </p>
        </div>
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


    <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">创建投标项目</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'basic/biddinginformations/storebyprojecttypes', 'id' => 'frmCreate']) !!}
                        <div class="form-group">
                            {!! Form::select('selectprojecttypes', array('SDA半干法系统' => 'SDA半干法系统', '湿法系统' => '湿法系统', 'SNCR系统' => 'SNCR系统', 'SCR系统' => 'SCR系统', '飞灰输送系统' => '飞灰输送系统',
                                '灰库系统' => '灰库系统', '稳定化系统' => '稳定化系统', 'CFB系统' => 'CFB系统', '固定喷雾系统' => '固定喷雾系统', '公用系统' => '公用系统'), null,
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
                    url: "{!! url('basic/biddinginformations/export') !!}",
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

            $("#btnCreate").click(function() {
                $("input[name='projecttypes']").val($("select[name='selectprojecttypes']").val());
//                alert($("input[name='projecttypes']").val());
                $("form#frmCreate").submit();
            });

        });

    </script>
@endsection
