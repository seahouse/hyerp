@extends('navbarerp')

@section('title', '属性')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">产品 -- 属性
{{--            <div class="pull-right">
                <a href="{{ URL::to('product/characteristics') }}" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div> --}}
        </div>        

    </div>
    
    <div class="panel-body">
        <a href="{{ URL::to('product/characteristics/create') }}" class="btn btn-sm btn-success">新建</a>
{{--        <form class="pull-right" action="/items/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form> --}}
    </div>

    @if ($characteristics->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>名称</th>
                <th>描述</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($characteristics as $characteristic)
                <tr>
                    <td>
                        {{ $characteristic->name }}
                    </td>
                    <td>
                        {{ $characteristic->descrip }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/product/characteristics/'.$characteristic->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('product.characteristics.destroy', $characteristic->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-sm btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $characteristics->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
