@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="images/create" class="btn btn-sm btn-success">新建</a>
    </div>
    
    @if ($images->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>名称</th>
                <th>描述</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($images as $image)
                <tr>
                    <td>
                        {{ $image->name }}
                    </td>
                    <td>
                        {{ $image->descrip }}
                    </td>
                    <td>
                        {{ $image->created_at }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/images/'.$image->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('system.images.destroy', $image->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $images->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
