@extends('navbarerp')

@section('title', 'EPC-安装队现场增补')

@section('main')
@can('approval_issuedrawing_view')
    <div class="panel-heading">
        <div class="panel-title">审批 -- EPC-安装队现场增补
{{--            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div> --}}
        </div>
    </div>
    
    <div class="panel-body">
{{--
        <a href="{{ URL::to('approval/items/create') }}" class="btn btn-sm btn-success">新建</a>
--}}

        @if (Auth::user()->email === "admin@admin.com")
            <form class="pull-right" action="/approval/epcsecening/export_wlhremark" method="post">
                {!! csrf_field() !!}
                <div class="pull-right">
                    <button type="submit" class="btn btn-default btn-sm">导出吴总评论</button>
                </div>
            </form>

        {{--<form class="pull-right" action="/approval/paymentrequests/export" method="post">--}}
            {{--{!! csrf_field() !!}--}}
            {{--<div class="pull-right">--}}
                {{--<button type="submit" class="btn btn-default btn-sm">导出</button>--}}
            {{--</div>--}}
        {{--</form>--}}
        @endif


        {!! Form::open(['url' => '/approval/issuedrawing/search', 'class' => 'pull-right form-inline']) !!}
            <div class="form-group-sm">
                {{--{!! Form::label('sohead_name', '订单', ['class' => 'control-label']) !!}--}}
                {{--{!! Form::select('sohead_name', $soheadList_hxold, null, ['class' => 'form-control', 'id' => 'select_sohead']) !!}--}}
                {{--{!! Form::hidden('sohead_id', null, ['id' => 'sohead_id']) !!}--}}

                {{--{!! Form::select('status', ['1' => '审批中', '0' => '已通过', '-1' => '已拒绝', '-2' => '已撤回'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']) !!}--}}
                {{--{!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '审批编号, 订单编号']) !!}--}}
                {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
            </div>
        {!! Form::close() !!}


    </div>

    @if ($epcsecenings->count())

    <table id="userDataTable" class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请日期</th>
                <th>审批编号</th>
                <th>增补项所属设计部门</th>
                <th>增补内容</th>
                {{--<th>制作概述</th>--}}
                {{--@if (Agent::isDesktop())--}}
                {{--<th>对应项目</th>--}}
                {{--@endif--}}

                <th>申请人</th>
                <th>审批状态</th>
                @if (Agent::isDesktop())
                <th style="width: 150px">操作</th>
                @endif

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
                    {{--<td title="{{ $epcsecening->overview }}">--}}
                        {{--{{ str_limit($epcsecening->overview, 40) }}--}}
                    {{--</td>--}}
                    {{--@if (Agent::isDesktop())--}}
                        {{--<td title="@if (isset($epcsecening->sohead_hxold->descrip)) {{ $epcsecening->sohead_hxold->descrip }} @else @endif">--}}
                            {{--@if (isset($epcsecening->sohead_hxold->projectjc)) {{ str_limit($epcsecening->sohead_hxold->projectjc, 40) }} @else @endif--}}
                        {{--</td>--}}
                    {{--@endif--}}
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
                    @if (Agent::isDesktop())
                        <td>
                            {{--@can('approval_issuedrawing_modifyweight')--}}
                                {{--<a href="{{ url('/approval/issuedrawing/' . $epcsecening->id . '/modifyweight') }}" target="_blank" class="btn btn-success btn-sm pull-left--}}
                        {{--@if ($epcsecening->status == 0)--}}
                                {{--@else--}}
                                        {{--disabled--}}
                                {{--@endif--}}
                                        {{--">修改重量</a>--}}
                            {{--@endcan--}}
                        </td>
                    @endif
                </tr>
            @endforeach

        </tbody>

    </table>


    @if (isset($key))
        {!! $epcsecenings->setPath('/approval/epcsecening')->appends($inputs)->links() !!}
    @else
        {!! $epcsecenings->setPath('/approval/epcsecening')->links() !!}
    @endif



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
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    <script type="text/javascript" src="/js/jquery-editable-select.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnExport").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('approval/issuedrawings/export') }}",
                    // data: $("form#formAddVendbank").serialize(),
                    // dataType: "json",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert('error');
                    },
                    success:function(result){
                        alert("导出成功:" + result);
                    },
                }); 
            });

            $('#select_sohead')
                .editableSelect({
                    effects: 'slide',
                })

                .on('select.editable-select', function (e, li) {
                    if (li.val() > 0)
                        $('input[name=sohead_id]').val(li.val());
                    else
                        $('input[name=sohead_id]').val('');
                })
            ;
        });
    </script>
@endsection