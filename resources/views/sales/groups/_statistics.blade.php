@can('approval_paymentrequest_amountstatics_view')
	<div id="tabid1" class="tabid">
		@if (isset($group))
			<p>集团名称：{{ $group->name }}</p>
            <?php $totalamount = 0.0; ?>
            <?php $sohead_receiptpayments_total = 0.0; ?>
            <?php $pohead_amount_total = 0.0; ?>
            <?php $poheadAmountBy7550 = 0.0; ?>
            <?php $sohead_taxamount = 0.0; ?>
            <?php $sohead_poheadtaxamount = 0.0; ?>
            <?php $pohead_amount_payment_total = 0.0; ?>
			@foreach($group->projects as $project)
				@if (isset($project))
					@foreach($project->soheads as $sohead)
						<?php $totalamount += $sohead->amount; ?>
						<?php $sohead_receiptpayments_total += $sohead->receiptpayments->sum('amount'); ?>
						<?php $pohead_amount_total += $sohead->poheads->sum('amount'); ?>
						<?php $poheadAmountBy7550 += array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550; ?>
						<?php $sohead_taxamount += isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0; ?>
						<?php $sohead_poheadtaxamount += isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0; ?>
						<?php $pohead_amount_payment_total += $sohead->payments->sum('amount'); ?>
					@endforeach
				@endif
			@endforeach
			<p>集团总金额：{{ $totalamount }}万</p>
			<p>集团收款总金额：{{ $sohead_receiptpayments_total }}万</p>
			@if ($totalamount > 0.0)
				<p>订单收款比例：{{ number_format($sohead_receiptpayments_total / $totalamount * 100.0, 2) }}%</p>
			@else
				<p>订单收款比例：-</p>
			@endif
			<p>对应的采购订单合同金额总额：{{ number_format($pohead_amount_total / 10000.0, 4) }}万</p> 	{{-- 似乎写到数据库视图中速度更快 --}}
			<p>公用订单分摊成本金额：{{ number_format($poheadAmountBy7550 / 10000.0, 4)  }}万</p>
			<p>税差：{{ number_format(($sohead_taxamount - $sohead_poheadtaxamount) / 10000.0, 4) }}万</p>
			@if ($totalamount > 0.0)
				<p>采购成本比例：{{ number_format(($pohead_amount_total + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount) / ($totalamount * 10000.0) * 100.0, 2) }}%
					(含公摊{{ number_format($poheadAmountBy7550 / ($totalamount * 10000.0) * 100.0, 2) }}%、
					税差{{ number_format(($sohead_taxamount - $sohead_poheadtaxamount) / ($totalamount * 10000.0) * 100.0, 2) }}%)</p>
			@else
				<p>采购成本比例：-</p>
			@endif
			<p>采购订单付款总金额：{{ number_format($pohead_amount_payment_total / 10000.0, 4) }}万</p>
			<p>采购订单累计付款比例：{{ number_format($pohead_amount_payment_total / $pohead_amount_total * 100.0, 2) }}%</p>
			@if ($totalamount > 0.0)
				<p>采购付款占销售订单比例：{{ number_format($pohead_amount_payment_total / ($totalamount * 10000.0) * 100.0, 2) }}%</p>
			@else
				<p>采购付款占销售订单比例：-</p>
			@endif
		@endif
	</div>
@endcan















