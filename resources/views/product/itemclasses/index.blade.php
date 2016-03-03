@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="/product/itemclasses/create" class="btn btn-sm btn-success">新建</a>
    </div>
    
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>名称</th>
<!--                 <th>Market Price</th> -->
                <th>Operate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemclasses as $itemclass)
                <tr>
                    <td>
                        <a href="{{ url('/product/itemclasses', $itemclass->id) }}">{{ $itemclass->name }}</a>
                    </td>
                    <td>
                        <a href="{{ url('/product/itemclasses/'.$itemclass->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('product.itemclasses.destroy', $itemclass->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-sm btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $itemclasses->render() !!}
@stop
