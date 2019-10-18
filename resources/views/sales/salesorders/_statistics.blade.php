@can('approval_paymentrequest_amountstatics_view')
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
			<p>订单开票总金额：{{ $sohead->sotickets->sum('amount') }}万</p>
			<?php $pohead_amount_total = $sohead->poheads->sum('amount'); ?>
            <?php $pohead_amount_ticketed_total = $sohead->poheads->sum('amount_ticketed'); ?>
            <?php $poheadAmountBy7550 = array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550; ?>
            <?php $sohead_taxamount = isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0; ?>
            <?php $sohead_poheadtaxamount = isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0; ?>
            <?php $sohead_poheadtaxamountby7550 = array_first($sohead->getPoheadTaxAmountBy7550())->poheadTaxAmountBy7550; ?>
			<p>对应的采购订单合同金额总额：{{ number_format($pohead_amount_total / 10000.0, 4) }}万</p>		{{-- 似乎写到数据库视图中速度更快 --}}
			<p>对应的采购订单开票总额：{{ number_format($pohead_amount_total / 10000.0, 4) }}万</p>
			<p>公用订单分摊成本金额：{{ number_format($poheadAmountBy7550 / 10000.0, 4)  }}万</p>
			<p>税差：{{ number_format(($sohead_taxamount - $sohead_poheadtaxamount - $sohead_poheadtaxamountby7550) / 10000.0, 4) }}万（含公摊税差{{ number_format($sohead_poheadtaxamountby7550 / 10000.0, 4) }}万）</p>
			@if ($sohead->amount > 0.0)
				<p>采购成本比例：{{ number_format(($pohead_amount_total + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount - $sohead_poheadtaxamountby7550) / ($sohead->amount * 10000.0) * 100.0, 2) }}%
					(含公摊{{ number_format($poheadAmountBy7550 / ($sohead->amount * 10000.0) * 100.0, 2) }}%、
					税差{{ number_format(($sohead_taxamount - $sohead_poheadtaxamount - $sohead_poheadtaxamountby7550) / ($sohead->amount * 10000.0) * 100.0, 2) }}%)</p>
			@else
				<p>采购成本比例：-</p>
			@endif
		@if (isset($sohead->project->id))
			<a href="{{ URL::to('/sales/projects/' . $sohead->project->id . '/mstatistics') }}" target="_blank" class="btn btn-default btn-sm">备注</a>
		@endif
			<?php $pohead_amount_payment_total = $sohead->payments->sum('amount'); ?>
			<p>采购订单付款总金额：{{ number_format($pohead_amount_payment_total / 10000.0, 4) }}万</p>
			<p>采购订单累计付款比例：{{ number_format($pohead_amount_payment_total / $pohead_amount_total * 100.0, 2) }}%</p>
			@if ($sohead->amount > 0.0)
				<p>采购付款占销售订单比例：{{ number_format($pohead_amount_payment_total / ($sohead->amount * 10000.0) * 100.0, 2) }}%</p>
			@else
				<p>采购付款占销售订单比例：-</p>
			@endif
		@if (null !== $sohead->soheaddocs->where('type', 'swht')->first())
			<p>商务合同：<a class="media" href="{{ config('custom.hxold.purchase_businesscontract_webdir') . 'swht/' . $sohead->id . '/' . $sohead->soheaddocs->where('type', 'swht')->first()->name }}" target="_blank" id="showPdf">{{ $sohead->soheaddocs->where('type', 'swht')->first()->name }}</a></p>
		@else
			<p>商务合同：无。</p>
		@endif
			@if (null !== $sohead->soheaddocs->where('type', 'jsxy')->first())
				<p>技术协议：<a class="media" href="{{ config('custom.hxold.purchase_businesscontract_webdir') . 'jsxy/' . $sohead->id . '/' . $sohead->soheaddocs->where('type', 'jsxy')->first()->name }}" target="_blank" id="showPdf_jsxy">{{ $sohead->soheaddocs->where('type', 'jsxy')->first()->name }}</a></p>
			@else
				<p>技术协议：无。</p>
			@endif
		@endif
	</div>
@endcan















