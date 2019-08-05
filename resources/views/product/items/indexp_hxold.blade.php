@extends('navbarerp')

@section('title', '物料')

@can('product_item_purchase_view')
@section('main')
    <div class="panel-heading">
        <div class="panel-title">基础资料 -- 购入商品
{{--
            <div class="pull-right">
                <a href="{{ URL::to('product/itemclasses') }}" target="_blank" class="btn btn-sm btn-success">{{'物料类型管理'}}</a>
                <a href="{{ URL::to('product/characteristics') }}" target="_blank" class="btn btn-sm btn-success">{{'物料属性管理'}}</a>
            </div>
--}}
        </div>
    </div>
    
    <div class="panel-body">
{{--
        <a href="{{ URL::to('product/items/create') }}" class="btn btn-sm btn-success">新建</a>
--}}
        {!! Form::open(['url' => '/product/indexp_hxold/search', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {!! Form::select('numberstatus', ['已设置' => '已设置', '未设置' => '未设置'], null, ['class' => 'form-control', 'placeholder' => '--编号设置状态--']) !!}
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '编号']); !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']); !!}
        </div>
        {!! Form::close() !!}

@if (Auth::user()->isSuperAdmin())
        {!! Form::button('重新对新老系统编号进行一一对应（按照名称、型号完全匹配）', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnSetNo']) !!}
            <a href="{{ URL::to('product/indexp_hxold/itemstopdm') }}" target="_blank" class="btn btn-sm btn-success">同步到PDM</a>
            <a href="{{ URL::to('product/indexp_hxold/bomstopdm') }}" target="_blank" class="btn btn-sm btn-success">BOM同步到PDM</a>
@endif

        {{--
                {!! Form::open(['url' => '', 'class' => 'pull-right form-inline']) !!}
                <div class="form-group-sm">
                    {!! Form::submit('对新老系统编号进行一一对应（按照名称、型号）', ['class' => 'btn btn-default btn-sm']) !!}
                </div>
                {!! Form::close() !!}
         --}}

        {{--        <form class="pull-right" role="search" action="/items/search" method="post">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-default btn-sm">查找</button>
                    </div>
                    <div class="pull-right input-group-sm">
                        <input type="text" class="form-control" name="key" placeholder="Search">
                    </div>
                </form> --}}

        </div>
{{--        <form class="media-right" role="search">
            <div class="input-group-sm">
                <input type="text" class="form-control" placeholder="Search">    
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
        </form> --}}
{{--        <div class="pull-right" style="padding-top: 4px; width: 500px">
            {!! Form::open(['url' => '/items/search']) !!}   
                <div class="form-group  input-group-sm">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
                </div>      
                <button type="submit" class="btn btn-success btn-sm">Submit</button>         
                <a href="{{ URL::to('items/create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'搜索', [], 'layouts'}}</a> 
            {!! Form::close() !!}
        </div> --}}
    @if ($items->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>物料编号</th>
{{--
                <th>物料类别</th>
--}}
                <th>名称</th>
                <th>型号</th>
                <th>老编号</th>
{{--
                <th>物料类型</th>
                <th>索引</th>
                <th>创建日期</th>
                <th>BOM</th>
--}}
                <th style="width: 200px">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
{{--
                        <a href="{{ url('/product/items', $item->id) }}">{{ $item->goods_no }}</a>
--}}
                        {{ $item->goods_no }}
                    </td>
{{--
                    <td>
                        {{ $item->itemclass->name }}
                    </td>
--}}
                    <td>
                        {{ $item->goods_name }}
                    </td>
                    <td>
                        {{ $item->goods_spec }}
                    </td>
                    <td>
                        {{ $item->goods_no2 }}
                    </td>
{{--
                    <td>
                        {{ $item->itemtype->name }}
                    </td>
                    <td>
                        {{ $item->index }}
                    </td>
                    <td>
                        {{ $item->created_at }}
                    </td>
                    <td>
                        @if ($item->itemtype->name == '生产' || $item->itemtype->name == '采购')
                            <a href="{{ URL::to('product/boms/' . $item->id . '/edit') }}" target="_blank">编辑</a>
                        @else
                            --
                        @endif
                    </td>
--}}
                    <td>
                        @can('product_item_purchase_setoldrelation')
                        <a href="{{ URL::to('/product/indexp_hxold/'.$item->goods_id.'/sethxold2') }}" target="_blank" class="btn btn-success btn-sm pull-left">对应老编号</a>
                        @endcan

                            @if (isset(Auth::user()->email) and Auth::user()->email == "admin@admin.com")
                                <a href="{{ URL::to('/product/indexp_hxold/'.$item->goods_id.'/topdm') }}" target="_blank" class="btn btn-success btn-sm pull-left">到PDM</a>
                            @endif
{{--
                        {!! Form::open(array('route' => array('product.items.destroy', $item->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    @if (isset($key))
        {!! $items->setPath('/product/indexp_hxold')->appends([
            'key' => $key,
            'numberstatus' => $inputs['numberstatus']
        ])->links() !!}
    @else
        {!! $items->setPath('/product/indexp_hxold')->links() !!}
    @endif
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif

    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content" >
                <span style="text-align:center;color:red">正在批量修改数据库信息，请勿刷新页面！</span><br />

                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%" id="process1">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnSetNo").click(function() {
                $('#myModal1').modal('toggle');
                setitempnumber(0);
            });
            
            function setitempnumber(from) {
                $.post("{{ url('/product/indexp_hxold/resetitempnumber') }}", {from:from, _token:"{!! csrf_token() !!}"}, function (data) {
//                    alert(data.sum);
                    from = from + parseInt(data.count);
//                    alert(from);
                    $('#process1').css('width', String(from / parseInt(data.sum) * 100) + '%').text(String(from) + "/" + String(data.sum));
                    if (data.count > 0 && from < data.sum)
                        setitempnumber(from);
                    else
                    {
                        $('#myModal1').modal('toggle');
                        window.location.reload(true);
                    }
                }, "json");
            };

        });
    </script>
@endsection
@else
    无权限。
@endcan