@extends('navbarerp')

@section('main')
    <div class="panel-heading">
<!--        <div class="pull-right" style="padding-top: 4px;"> -->
<!--             <a href="{{ URL::to('items/create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'新建', [], 'layouts'}}</a> -->
<!--         </div> -->
        <a href="{{ URL::to('sales/custinfos/create') }}" class="btn btn-sm btn-success">新建</a>
<!--         <h2> -->
<!--             {{ ('物料') }} -->
<!--         </h2> -->
    </div>
    
    @if ($custinfos->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>名称</th>
                <th>联系人</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($custinfos as $custinfo)
                <tr>
                    <td>
                        {{ $custinfo->number }}
                    </td>
                    <td>
                        {{ $custinfo->name }}
                    </td>
                    <td>
                        @if (isset($custinfo->contact->name)) {{ $custinfo->contact->name }} @endif
                    </td>
                    <td>
                        {{ $custinfo->created_at }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/sales/custinfos/'.$custinfo->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('sales.custinfos.destroy', $custinfo->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $custinfos->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif
    
<!--     <nav> -->
<!--         <ul class="pagination"> -->
<!--             <li> -->
<!--                 <a href="#" aria-label="Previous"> -->
<!--                     <span aria-hidden="true">&laquo;</span> -->
<!--                 </a>                 -->
<!--             </li> -->
<!--             <li><a href="#">1</a></li> -->
<!--             <li><a href="#">2</a></li> -->
<!--             <li><a href="#">3</a></li> -->
<!--             <li><a href="#">4</a></li> -->
<!--             <li><a href="#">5</a></li> -->
<!--             <li> -->
<!--                 <a href="#" aria-label="Next"> -->
<!--                     <span aria-hidden="true">&raquo;</span> -->
<!--                 </a> -->
<!--             </li> -->
<!--         </ul> -->
<!--     </nav> -->
@stop
