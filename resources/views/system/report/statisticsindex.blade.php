@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        {{--
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/depts') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'部门管理', [], 'layouts'}}</a>
        </div>
        --}}
    </div>

    <div class="panel-body">
        {!! Form::open(['url' => '/system/report/' . $report->id . '/export', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            @foreach($input as $key=>$value)
                {!! Form::hidden($key, $value) !!}
            @endforeach
            {!! Form::submit('导出到Excel', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}

        {!! Form::open(['url' => '/system/report/' . $report->id . '/statistics', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {{-- 根据不同报表设置不同搜索条件 --}}
            @if ($report->id == 1)
                {!! Form::label('arrivaldatelabel', '到货时间:', ['class' => 'control-label']) !!}
                {!! Form::date('datearravalfrom', null, ['class' => 'form-control']) !!}
                {!! Form::label('arrivaldatelabelto', '-', ['class' => 'control-label']) !!}
                {!! Form::date('datearravalto', null, ['class' => 'form-control']) !!}

                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '对应项目名称']) !!}
            @elseif ($report->id == 2)
            @endif

            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    
    @if ($items->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                @foreach(array_first($items->items()) as $key=>$value)
                <th>{{$key}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                @foreach($item as $value)
                <td>
                    {{ $value }}
                </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>

    </table>
    {!! $items->setPath('/system/report/' . $report->id . '/statistics')->appends($input)->links() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
