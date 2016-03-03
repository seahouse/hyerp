@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="warehouses/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
        </div>
    </div>
    

    @if ($warehouses->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>代码</th>
                <th>描述</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouses as $warehouse)
                <tr>
                    <td>
                        {{ $warehouse->code }}
                    </td>
                    <td>
                        {{ $warehouse->descrip }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/inventory/warehouses/'.$warehouse->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('inventory.warehouses.destroy', $warehouse->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $warehouses->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
