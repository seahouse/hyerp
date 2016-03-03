@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="terms/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
        </div>
    </div>
    

    @if ($terms->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>名称</th>
                <th>描述</th>
                <th>截止日</th>
                <th>贴现日</th>
                <th>贴现折扣</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($terms as $term)
                <tr>
                    <td>
                        {{ $term->code }}
                    </td>
                    <td>
                        {{ $term->descrip }}
                    </td>
                    <td>
                        {{ $term->duedays }}
                    </td>
                    <td>
                        {{ $term->discdays }}
                    </td>
                    <td>
                        {{ $term->discprcnt }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/sales/terms/'.$term->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('sales.terms.destroy', $term->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $terms->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
