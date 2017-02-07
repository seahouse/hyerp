@extends('navbarerp')

@section('title', '物料')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">设置老编号 -- 名称: {{ $itemp->goods_name }}, 型号: {{ $itemp->goods_spec }}, 编号: {{ $itemp->goods_no }}
            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div>
        </div>
    </div>
    
    <div class="panel-body">
{{-- 
        <a href="{{ URL::to('product/items/create') }}" class="btn btn-sm btn-success">新建</a>
        <form class="pull-right" action="/product/indexp_hxold/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="编号">    
            </div>
        </form>
--}}


    </div>


    
    @if ($items2->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>物料编号</th>
{{--
                <th>物料类别</th>
--}}
                <th>名称</th>
                <th>型号</th>
                {{--
                                <th>老编号</th>
                                <th>物料类型</th>
                                <th>索引</th>
                                <th>创建日期</th>
                                <th>BOM</th>
                --}}
                <th style="width: 150px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items2 as $item)
                <tr @if ($itemp->goods_no2 === $item->goods_no) class="success" @endif>
                    <td>
{{--
                        <a href="{{ url('/product/items', $item->id) }}">{{ $item->goods_no }}</a>
--}}
                        {{ $item->goods_no }}
                    </td>
{{--
                    <td>
                        {{ $item->itemclass->name }}
                    </td>
--}}
                    <td>
                        {{ $item->goods_name }}
                    </td>
                    <td>
                        {{ $item->goods_spec }}
                    </td>
                    {{--
                                        <td>
                                            {{ $item->goods_no2 }}
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
                    --}}
                    <td>
                        {!! Form::open(['url' => url('product/indexp_hxold/' . $itemp->goods_id . '/sethxold2/' . $item->goods_id), 'onsubmit' => 'return confirm("确定设定此记录?");']) !!}
                            {!! Form::submit('设定', ['class' => 'btn btn-success btn-sm']) !!}
                        {!! Form::close() !!}
{{--
                        <a href="{{ URL::to('/product/indexp_hxold/'.$item->goods_id.'/sethxold2') }}" target="_blank" class="btn btn-success btn-sm pull-left">设定</a>
                        {!! Form::open(array('route' => array('product.items.destroy', $item->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $items2->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection
