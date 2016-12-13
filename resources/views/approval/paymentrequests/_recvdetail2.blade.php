@if ($purchaseorder)
    @foreach ($itemps as $itemp)
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
                        <th>金额</th>
                        <th>录入时间</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemp->receiptitems as $receiptitem)
                    <tr @if (in_array($receiptitem->receipt_id, $purchaseorder->receiptorders->pluck('receipt_id')->toArray())) class="success" @endif>
                        <td>{{ $receiptitem->quantity }}</td>
                        <td>{{ $receiptitem->unitprice * 1.17 }}</td>
                        <td>{{ $receiptitem->amount * 1.17 }}</td>
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
{{--
    注：淡绿色表示本采购订单的采购记录
--}}
@endif


















