@extends('navbarerp')

@section('title', '物料')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">基础资料 -- 物料
            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div>
        </div>
    </div>
    
    <div class="panel-body">
        <a href="{{ URL::to('product/items/create') }}" class="btn btn-sm btn-success">新建</a>
        <form class="pull-right" action="/product/items/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form>
        
{{--        <form class="pull-right" role="search" action="/items/search" method="post">
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form> --}}

        </div>
{{--        <form class="media-right" role="search">
            <div class="input-group-sm">
                <input type="text" class="form-control" placeholder="Search">    
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
        </form> --}}
{{--        <div class="pull-right" style="padding-top: 4px; width: 500px">
            {!! Form::open(['url' => '/items/search']) !!}   
                <div class="form-group  input-group-sm">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
                </div>      
                <button type="submit" class="btn btn-success btn-sm">Submit</button>         
                <a href="{{ URL::to('items/create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'搜索', [], 'layouts'}}</a> 
            {!! Form::close() !!}
        </div> --}}
    
    @if ($items->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>物料编号</th>
                <th>物料类别</th>
                <th>名称</th>
                <th>物料类型</th>
                <th>索引</th>
                <th>创建日期</th>
                <th>BOM</th>
                <th style="width: 120px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <a href="{{ url('/product/items', $item->id) }}">{{ $item->item_number }}</a>
                    </td>
                    <td>
                        {{ $item->itemclass->name }}
                    </td>
                    <td>
                        {{ $item->item_name }}
                    </td>
                    <td>
                        {{ $item->itemtype->name }}
                    </td>
                    <td>
                        {{ $item->index }}
                    </td>
                    <td>
                        {{ $item->created_at }}
                    </td>
                    <td>
                        @if ($item->itemtype->name == '生产' || $item->itemtype->name == '采购')
                            <a href="{{ URL::to('product/boms/' . $item->id . '/edit') }}" target="_blank">编辑</a>
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        <a href="{{ URL::to('/product/items/'.$item->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('product.items.destroy', $item->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $items->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection
