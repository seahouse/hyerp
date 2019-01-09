@extends('navbarerp')

@section('title', '图纸物料清单')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">销售 -- 销售订单 -- 图纸物料清单
            {{--
            <div class="pull-right">
                <a href="{{ URL::to('sales/salesreps') }}" class="btn btn-sm btn-success">{{'销售代表管理'}}</a>
                <a href="{{ URL::to('sales/terms') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'付款条款管理', [], 'layouts'}}</a>
            </div>
            --}}
        </div>
    </div>
    
    <div class="panel-body">
        {{--
        <a href="{{ URL::to('/sales/salesorders/create') }}" class="btn btn-sm btn-success">新建</a>
           --}}

        {{--
        {!! Form::open(['url' => '/sales/salesorderhx/search', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '订单编号、项目名称']) !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
        --}}

        
    </div>

    @if ($dwgboms->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>物料清单名称</th>
                <th>生成时间</th>
                <th>更新时间</th>
                {{--
                <th>物料</th>
                <th>操作</th>
                --}}
            </tr>
        </thead>
        <tbody>
            @foreach($dwgboms as $dwgbom)
                <tr>
                    <td>
                        {{ $dwgbom->bomname }}
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
    {{--
    <td>
        <a href="{{ URL::to('/sales/soitems/' . $salesorder->id . '/list') }}" target="_blank">明细</a>
    </td>
    --}}
</tr>
@endforeach
</tbody>

</table>
    @if (isset($inputs))
        {!! $dwgboms->setPath('/sales/salesorderhx/' . $id . '/dwgbom')->appends($inputs)->links() !!}
    @else
        {!! $dwgboms->setPath('/sales/salesorderhx/' . $id . '/dwgbom')->links() !!}
    @endif
@else
<div class="alert alert-warning alert-block">
<i class="fa fa-warning"></i>
{{'无记录', [], 'layouts'}}
</div>
@endif

@stop
