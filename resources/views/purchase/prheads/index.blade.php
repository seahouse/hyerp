@extends('navbarerp')

@section('title', '采购申请单')

@section('main')
    <div class="panel-heading">
        {{--<a href="prheads/create" class="btn btn-sm btn-success">新建</a>--}}

{{--        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div> --}}
    </div>
    

    @if ($prheads->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>申请人</th>
                <th>对应项目</th>
                <th>类型</th>
                <th>对应审批编号</th>
                <th>物料</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prheads as $prhead)
                <tr>
                    <td>
                        {{ $prhead->number }}
                    </td>
                    <td>
                        {{ $prhead->applicant->name }}
                    </td>
                    <td>
                        {{ $prhead->sohead->number . '!' . $prhead->sohead->descrip }}
                    </td>
                    <td>
                        {{ $prhead->type }}
                    </td>
                    <td>
                        {{ $prhead->associated_business_id() }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/pritems/') . '?prhead_id=' . $prhead->id }}" target="_blank">明细</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/prtypes/') . '?prhead_id=' . $prhead->id }}" class="btn btn-success btn-sm pull-left">分组</a>
                        {!! Form::open(array('route' => array('purchase.prheads.destroy', $prhead->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $prheads->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
