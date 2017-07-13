@if (Auth::user()->isSuperAdmin())
	<div id="tabid1" class="tabid">
		@if ($sohead)
			<p>销售订单编号：{{ $sohead->number }}</p>
			<p>工程名称：{{ $sohead->descrip }}</p>
			<p>客户：{{ $sohead->custinfo->name }}</p>
			<p>订单金额：{{ $sohead->amount }}万</p>
			<p>付款方式：{{ $sohead->paymethod }}</p>
            <?php $sohead_receiptpayments_total = $sohead->receiptpayments->sum('amount'); ?>
			<p>订单收款总金额：{{ $sohead_receiptpayments_total }}万</p>
			@if ($sohead->amount > 0.0)
				<p>订单收款比例：{{ number_format($sohead_receiptpayments_total / $sohead->amount * 100.0, 2) }}%</p>
			@else
				<p>订单收款比例：-</p>
			@endif
			<?php $pohead_amount_total = $sohead->poheads->sum('amount'); ?>
			<p>对应的采购订单合同金额总额：{{ number_format($pohead_amount_total / 10000.0, 4) }}万</p>		{{-- 似乎写到数据库视图中速度更快 --}}
			@if ($sohead->amount > 0.0)
				<p>采购成本比例：{{ number_format($pohead_amount_total / ($sohead->amount * 10000.0) * 100.0, 2) }}%</p>
			@else
				<p>采购成本比例：-</p>
			@endif
			<?php $pohead_amount_payment_total = $sohead->payments->sum('amount'); ?>
			<p>采购订单付款总金额：{{ number_format($pohead_amount_payment_total / 10000.0, 4) }}万</p>
			<p>采购订单累计付款比例：{{ number_format($pohead_amount_payment_total / $pohead_amount_total * 100.0, 2) }}%</p>
			@if ($sohead->amount > 0.0)
				<p>采购付款占销售订单比例：{{ number_format($pohead_amount_payment_total / ($sohead->amount * 10000.0) * 100.0, 2) }}%</p>
			@else
				<p>采购付款占销售订单比例：-</p>
			@endif
		@endif
	</div>
@endif


















