
<div id="tabid1" class="tabid">
	@if ($sohead)
		<p>销售订单编号：{{ $sohead->number }}</p>
		<p>工程名称：{{ $sohead->descrip }}</p>
		<p>客户：{{ $sohead->custinfo->name }}</p>
		<p>订单金额：{{ $sohead->amount }}万</p>
		<p>订单收款总金额：{{ $sohead->receiptpayments->sum('amount') }}万</p>
        <?php $pohead_amount_total = $sohead->poheads->sum('amount'); ?>
		<p>对应的采购订单合同金额总额：{{ $pohead_amount_total }}</p>		{{-- 似乎写到数据库视图中速度更快 --}}
		@if ($sohead->amount > 0.0)
			<p>采购成本比例：{{ $pohead_amount_total / ($sohead->amount * 10000.0) * 100.0 }}%</p>
		@else
			<p>采购成本比例：-</p>
		@endif
        <?php $pohead_amount_payment_total = $sohead->payments->sum('amount'); ?>
		<p>采购订单付款总金额：{{ $pohead_amount_payment_total }}</p>
		<p>采购订单累计付款比例：{{ $pohead_amount_payment_total / $pohead_amount_total * 100.0 }}%</p>



	@endif
</div>



















