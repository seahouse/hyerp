@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="salesreps/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
        </div>
    </div>
    

    @if ($salesreps->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>姓名</th>
                <th>佣金</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesreps as $salesrep)
                <tr>
                    <td>
                        {{ $salesrep->number }}
                    </td>
                    <td>
                        {{ $salesrep->name }}
                    </td>
                    <td>
                        {{ $salesrep->commission }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/sales/salesreps/'.$salesrep->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('sales.salesreps.destroy', $salesrep->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-sm btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $salesreps->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
