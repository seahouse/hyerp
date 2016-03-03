@extends('navbarerp')

@section('main')
    <h1>{!! $parentItem->item_number !!}</h1>
    <hr/>
    
    @if ($bomhead->count())
        {!! Form::model($bomhead, ['method' => 'PATCH', 'action' => ['BomsController@update', $bomhead->id]]) !!}
            @include('product.boms._form', ['submitButtonText' => '保存'])
        {!! Form::close() !!}
    @else
        {!! Form::open(['url' => '/boms']) !!}
            @include('product.boms._form', ['submitButtonText' => '添加'])
        {!! Form::close() !!}
    @endif
    
    <div class="panel-heading">
        <a href="{{ URL::to('product/bomitems/' . $parentItem->id . '/createitem/') }}" class="btn btn-sm btn-success">新建</a>
    </div>
    
        <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>物料编号</th>
                <th>数量</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bomitems as $bomitem)
                <tr>
                    <td>
                        {{ $bomitem->item->item_number }}
                    </td>
                    <td>
                        {{ $bomitem->qtyper }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/product/bomitems/'.$bomitem->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('product.bomitems.destroy', $bomitem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    
    @include('errors.list')
@stop
