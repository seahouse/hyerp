@extends('navbarerp')

@section('title', '项目信息')

@section('main')
    <div class="panel-heading">
        <a href="/basic/biddingprojects/create" class="btn btn-sm btn-success">新建</a>
        <a href="/basic/biddingprojects/export" class="btn btn-sm btn-success pull-right">导出</a>
        {{--<a href="shipments/import" class="btn btn-sm btn-success">导入(Import)</a>--}}
    </div>

    <div class="panel-body">


        @if ($biddingprojects->count())
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>订单数量</th>
                    <th>订单明细</th>
                    <th>备注</th>
                    <th>Operation</th>
                </tr>
                </thead>
                <tbody>
                @foreach($biddingprojects as $biddingproject)
                    <tr>
                        <td>
                            {{ $biddingproject->name }}
                        </td>
                        <td>
                            {{ $biddingproject->biddinginformation->count() }}
                        </td>
                        <td>
                            <a href="{{ URL::to('/basic/biddingprojects/'.$biddingproject->id.'/showbiddinginformation') }}" class="btn btn-success btn-sm pull-left">订单明细</a>
                        </td>
                        <td>
                            {{ $biddingproject->remark }}
                        </td>
                        <td>
                            <a href="{{ URL::to('/basic/biddingprojects/'.$biddingproject->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                            {!! Form::open(array('route' => array('basic.biddingprojects.destroy', $biddingproject->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录(Delete this record)?");')) !!}
                            @if($biddingproject->biddinginformation->count()>0)
                                {!! Form::button('删除', ['class' => 'btn btn-info btn-sm disabled','title'=>'有关联订单无法删除']) !!}
                            @else
                                {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                            @endif
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            {!! $biddingprojects->setPath('/basic/biddingprojects')->appends($inputs)->links() !!}
        @else
            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{'无记录(No Record)', [], 'layouts'}}
            </div>
        @endif
    </div>

@endsection

