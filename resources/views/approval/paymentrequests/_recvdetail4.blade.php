<style type="text/css">
    body { padding-top: 70px; }
</style>

<div>
    <div class="navbar-fixed-top" role="navigation" style="background: white">
        <dir class="container-fluid">
            <div class="apprNav">
                <div class="btn-group btn-group-justified wrapper" role="group" aria-label="...">
                    <div class="btn-group btnw" role="group">                
                        <div id="tab1" onclick='changeTab(1)' class="text selected">入库价格明细2</div>
                    </div>
                    <div class="btn-group btnw" role="group">
                        <div id="tab2" onclick='changeTab(2)' class="text">入库价格明细3</div>
                    </div>
                </div>
            </div>
        </dir>
    </div>

    <div id="tabid1" class="tabid">
        @if ($purchaseorder)
            @foreach ($itemps2 as $itemp)
                @if (isset($itemp->goods_name))
                    <ul><li><strong>{{ $itemp->goods_name . '(型号: ' . $itemp->goods_spec . ')'}}</strong><br>

                    历史最低价: {{ $itemp->receiptitems->min('unitprice') * 1.17 }}<br>
                    历史均价: 

                    @if ($itemp->receiptitems->sum('quantity') <= 0.0) - 
                    @else
                        {{ number_format($itemp->receiptitems->sum(function($item) { return $item['unitprice'] * 1.17 * $item['quantity'];}) / $itemp->receiptitems->sum('quantity'), 6, '.', '')}}
                    @endif
            {{--
                    {{ $poitem->item->receiptitems->sum('amount') / $poitem->item->receiptitems->sum('quantity')}}
            --}}
                    <br>
            {{--
                    最近一次采购单价{{ $poitem->item->receiptitems->max('record_at') }}<br>
            --}}
            {{--
                    {{ $purchaseorder->receiptorders->pluck('receipt_id')->toArray() }}<br>
            --}}
                    历史采购明细<br>
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>数量</th>
                                <th>单价</th>
                                <th>单位</th>
                                <th>金额</th>
                                <th>供应商</th>
                                <th>录入时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemp->receiptitems as $receiptitem)
                            <tr @if (in_array($receiptitem->receipt_id, $purchaseorder->receiptorders->pluck('receipt_id')->toArray())) class="success" @endif>
                                <td>{{ $receiptitem->quantity }}</td>
                                <td>{{ $receiptitem->unitprice * 1.17 }}</td>
                                <td>{{ $receiptitem->item->goods_unit_name }}</td>
                                <td>{{ $receiptitem->amount * 1.17 }}</td>
                                <td>
                                    @if (isset($receiptitem->rwrecord->supplier->shortname))
                                        @if ($receiptitem->rwrecord->supplier->shortname == '') 
                                            {{ $receiptitem->rwrecord->supplier->name }}
                                        @else
                                            {{ $receiptitem->rwrecord->supplier->shortname }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ substr($receiptitem->record_at, 0, 10) }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    </li></ul></li></ul>
                @else
                    <div class="alert alert-warning alert-block">
                        <i class="fa fa-warning"></i>
                        {{'无此商品记录', [], 'layouts'}}
                    </div>
                @endif
            @endforeach
        @endif
    </div>
    <div id="tabid2" class="tabid" style='display:none;'>
        @if ($purchaseorder)
            @foreach ($itemps as $itemp)
                <ul><li><strong>{{ $itemp->goods_name . '(型号: ' . $itemp->goods_spec . ')'}}</strong>
{{--
                <a href="{{ url('/product/indexp_hxold/' . $itemp->goods_id . '/sethxold2') }}" target="_blank" class="btn btn-default btn-sm">重新对应</a>
--}}
                <br>

                历史最低价: {{ $itemp->receiptitems->min('unitprice') * 1.17 }}<br>
                历史均价: 

                @if ($itemp->receiptitems->sum('quantity') <= 0.0) - 
                @else
                    {{ number_format($itemp->receiptitems->sum(function($item) { return $item['unitprice'] * 1.17 * $item['quantity'];}) / $itemp->receiptitems->sum('quantity'), 6, '.', '')}}
                @endif
        {{--
                {{ $poitem->item->receiptitems->sum('amount') / $poitem->item->receiptitems->sum('quantity')}}
        --}}
                <br>
        {{--
                最近一次采购单价{{ $poitem->item->receiptitems->max('record_at') }}<br>
        --}}
        {{--
                {{ $purchaseorder->receiptorders->pluck('receipt_id')->toArray() }}<br>
        --}}
                历史采购明细<br>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>数量</th>
                            <th>单价</th>
                            <th>单位</th>
                            <th>金额</th>
                            <th>供应商</th>
                            <th>录入时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemp->receiptitems as $receiptitem)
                        <tr @if (in_array($receiptitem->receipt_id, $purchaseorder->receiptorders->pluck('receipt_id')->toArray())) class="success" @endif>
                            <td>{{ $receiptitem->quantity }}</td>
                            <td>{{ $receiptitem->unitprice * 1.17 }}</td>
                            <td>{{ $receiptitem->item->goods_unit_name }}</td>
                            <td>{{ $receiptitem->amount * 1.17 }}</td>
                            <td>
                                @if ($receiptitem->rwrecord->supplier->shortname == '') 
                                    {{ $receiptitem->rwrecord->supplier->name }}
                                @else
                                    {{ $receiptitem->rwrecord->supplier->shortname }}
                                @endif
                            </td>
                            <td>{{ substr($receiptitem->record_at, 0, 10) }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
        </li></ul></li></ul>
            @endforeach
            注：淡绿色表示本采购订单的采购记录
        @endif
    </div>
</div>


















