@extends('navbarerp')

@section('title', '审批设置')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 类型管理
{{--            <div class="pull-right">
                <a href="{{ URL::to('approval/approvaltypes') }}" target="_blank" class="btn btn-sm btn-success">{{'类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a> 
            </div> --}}
        </div> 
    </div>
    
    <div class="panel-body">
        <a href="{{ URL::to('approval/approvaltypes/create') }}" class="btn btn-sm btn-success">新建</a>
{{--        <form class="pull-right" action="/approval/items/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form> --}}
    </div> 

    
    @if ($approvaltypes->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>类型</th>
                <th>描述</th>
                <th style="width: 120px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvaltypes as $approvaltype)
                <tr>
                    <td>
                        {{ $approvaltype->name }}
                    </td>
                    <td>
                        {{ str_limit($approvaltype->description, 20) }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/approval/approvaltypes/'.$approvaltype->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('approval.approvaltypes.destroy', $approvaltype->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $approvaltypes->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection
