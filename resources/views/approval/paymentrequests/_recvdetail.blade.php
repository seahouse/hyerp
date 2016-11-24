@if ($purchaseorder)
    @foreach ($purchaseorder->poitems as $poitem)
        <ul><li><strong>{{ $poitem->item->goods_name }}</strong><br>
{{--
        名称: {{ $poitem->item->goods_name }} , 数量: {{ $poitem->qty }}, 单价: {{ $poitem->unitprice }} <br>
        <ul><li>
        本单采购信息<br>
        @foreach ($purchaseorder->receiptorders as $receiptorder)
            {{ $receiptorder->receipt_id }}<br>
            @foreach ($receiptorder->receiptitems as $receiptitem)
                @if ($receiptitem->item_number == $poitem->item->goods_no)
                    数量: {{ $receiptitem->quantity }}, 单价: {{ $receiptitem->unitprice }}, 金额: {{ $receiptitem->amount }}, 录入时间: {{ $receiptitem->record_at }}<br>
                @endif
            @endforeach
        @endforeach
--}}
        历史最低价: {{ $poitem->item->receiptitems->min('unitprice') }}<br>
        历史均价: 

        {{ $poitem->item->receiptitems->sum(function($item) { return $item['unitprice'] * $item['quantity'];}) / $poitem->item->receiptitems->sum('quantity')}}
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
                @foreach ($poitem->item->receiptitems as $receiptitem)
                <tr @if (in_array($receiptitem->receipt_id, $purchaseorder->receiptorders->pluck('receipt_id')->toArray())) class="success" @endif>
                    <td>{{ $receiptitem->quantity }}</td>
                    <td>{{ $receiptitem->unitprice }}</td>
                    <td>{{ $receiptitem->amount }}</td>
                    <td>{{ substr($receiptitem->record_at, 0, 10) }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
</li></ul></li></ul>
    @endforeach
    注：淡绿色表示本采购订单的采购记录
@endif


















