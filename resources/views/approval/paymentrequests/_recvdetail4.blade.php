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
                    <ul><li>
                    <strong>{{ $itemp->goods_name . '(型号: ' . $itemp->goods_spec . ')'}}</strong><br>

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
                                <th style="min-width: 80px">供应商</th>
                                <th style="min-width: 80px">对应项目</th>
                                <th>录入时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemp->receiptitems->sortByDesc('record_at')->take(20) as $receiptitem)
                            <tr @if (in_array($receiptitem->receipt_id, $purchaseorder->receiptorders->pluck('receipt_id')->toArray())) class="success" @endif>

                                <td>{{ $receiptitem->quantity }}</td>
                                <td>{{ number_format($receiptitem->unitprice * 1.17, 2, '.', '') }}</td>
                                <td>{{ $receiptitem->item->goods_unit_name }}</td>
                                <td>{{ number_format($receiptitem->amount * 1.17, 2, '.', '') }}</td>
                                <td>
                                    @if (isset($receiptitem->rwrecord->supplier->shortname))
                                        @if ($receiptitem->rwrecord->supplier->shortname == '') 
                                            {{ str_limit($receiptitem->rwrecord->supplier->name, 8) }}
                                        @else
                                            {{ str_limit($receiptitem->rwrecord->supplier->shortname, 8) }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($receiptitem->rwrecord->receiptorder->pohead->sohead->descrip))
                                        {{ $receiptitem->rwrecord->receiptorder->pohead->sohead->descrip }}
                                    @endif
                                </td>
                                <td>{{ substr($receiptitem->record_at, 0, 10) }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    </li></ul>
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
                <a href="{{ url('/product/indexp_hxold/' . $itemp->goods_id . '/msethxold2') }}" target="_blank" class="btn btn-default btn-sm">重新对应</a>
                <br>

                历史最低价: {{ $itemp->receiptitems->min('unitprice') * 1.17 }}<br>
                历史均价: 

                @if ($itemp->receiptitems->sum('quantity') <= 0.0) - 
                @else
                    {{ number_format($itemp->receiptitems->sum(function($item) { return $item['unitprice'] * (1 + $item['taxrate'] / 100.0) * $item['quantity'];}) / $itemp->receiptitems->sum('quantity'), 6, '.', '')}}
                @endif
                <br>
                现存量:
                {{ $itemp->receiptitems->sum('quantity') - $itemp->shipitems->sum('quantity') }}

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
                            <th style="min-width: 80px">供应商</th>
                            <th style="min-width: 80px">对应项目</th>
                            <th>出库项目</th>
                            <th>录入时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemp->receiptitems->sortByDesc('record_at')->take(20) as $receiptitem)
                        <tr @if (in_array($receiptitem->receipt_id, $purchaseorder->receiptorders->pluck('receipt_id')->toArray())) class="success" @endif>
                            <td>{{ $receiptitem->quantity }}</td>
                            <td>{{ number_format($receiptitem->unitprice * (1 + $receiptitem['taxrate'] / 100.0), 2, '.', '') }}</td>
                            <td>{{ $receiptitem->item->goods_unit_name }}</td>
                            <td>{{ number_format($receiptitem->amount * (1 + $receiptitem['taxrate'] / 100.0), 2, '.', '') }}</td>
                            <td>
                                @if ($receiptitem->rwrecord->supplier->shortname == '') 
                                    {{ $receiptitem->rwrecord->supplier->name }}
                                @else
                                    {{ $receiptitem->rwrecord->supplier->shortname }}
                                @endif
                            </td>
                            <td>
                                @if (isset($receiptitem->rwrecord->receiptorder->pohead->sohead->projectjc))
                                    @if ($receiptitem->rwrecord->receiptorder->pohead->sohead->projectjc === "")
                                        {{ $receiptitem->rwrecord->receiptorder->pohead->sohead->descrip }}
                                    @else
                                        {{ $receiptitem->rwrecord->receiptorder->pohead->sohead->projectjc }}
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ $receiptitem->out_sohead_name }}
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


















