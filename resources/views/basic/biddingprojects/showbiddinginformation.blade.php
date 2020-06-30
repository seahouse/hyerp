@extends('navbarerp')

@section('title', '项目订单信息')

@section('main')
    <div class="panel-heading">

        {{--<a href="shipments/import" class="btn btn-sm btn-success">导入(Import)</a>--}}
    </div>

    <div class="panel-body">


        @if ($biddinginformations->count())
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>投标编号</th>
                    <th>名称</th>
                    {{--<th>Operation</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($biddinginformations as $biddinginformation)
                    <tr>
                        <td>
                            {{ $biddinginformation->number }}
                        </td>
                        <td>
                            @if (null != $biddinginformation->biddinginformationitems->where('key', '名称')->first())
                                {{ $biddinginformation->biddinginformationitems->where('key', '名称')->first()->value }}
                            @else
                                '-'
                            @endif
                        </td>
                        <td>
                            {{--<a href="{{ URL::to('/basic/biddingprojects/'.$id.'/deletebiddinginformation') }}" class="btn btn-success btn-sm pull-left">删除</a>--}}
                            {{--{!! Form::open(array('route' => array('basic.biddingprojects.deletebiddinginformation', $biddinginformation->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录(Delete this record)?");')) !!}--}}

                             {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}

                            {{--{!! Form::close() !!}--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            {!! $biddinginformations->setPath('/basic/biddingprojects/'.$id.'/showbiddinginformation')->appends($inputs)->links() !!}
        @else
            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{'无记录(No Record)', [], 'layouts'}}
            </div>
        @endif
    </div>

@endsection

