@can('approval_paymentrequest_amountstatics_view')
	<div id="tabid1" class="tabid">
		@if (isset($project))
			<p>项目名称：{{ $project->name }}</p>
            <?php $totalamount = 0.0; ?>
            <?php $sohead_receiptpayments_total = 0.0; ?>
            <?php $sohead_tickets_total = 0.0; ?>
            <?php $pohead_amount_total = 0.0; ?>
            <?php $pohead_amount_ticketed_total = 0.0; ?>
            <?php $poheadAmountBy7550 = 0.0; ?>
            <?php $sohead_taxamount = 0.0; ?>
            <?php $sohead_poheadtaxamount = 0.0; ?>
            <?php $pohead_amount_payment_total = 0.0; ?>
			<?php $warehousecost = 0.0; ?>
			<?php $warehousetaxcost = 0.0; ?>
			<?php $nowarehousecost = 0.0; ?>
			<?php $nowarehousetaxcost = 0.0; ?>
			<?php $nowarehouseamountby7550 = 0.0; ?>
			<?php $warehouseqty = 0.0; ?>
			<?php $drawingqty = 0.0; ?>
            <?php $sohead_othercostpercent = 0.0; ?>
		@foreach($project->soheads as $sohead)
            	<?php $totalamount += $sohead->amount; ?>
            	<?php $sohead_receiptpayments_total += $sohead->receiptpayments->sum('amount'); ?>
                <?php $sohead_tickets_total += $sohead->sotickets->sum('amount'); ?>
                <?php $pohead_amount_total += $sohead->poheads->sum('amount'); ?>
                <?php $pohead_amount_ticketed_total += $sohead->poheads->sum('amount_ticketed'); ?>
                <?php $poheadAmountBy7550 += array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550; ?>
                <?php $sohead_taxamount += isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0; ?>
                <?php $sohead_poheadtaxamount += isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0; ?>
                <?php $pohead_amount_payment_total += $sohead->payments->sum('amount'); ?>
				<?php $warehousecost +=array_first($sohead->getwarehouseCost())->warehousecost; ?>
				<?php $warehousetaxcost +=array_first($sohead->getwarehousetaxCost())->warehousetaxcost;?>
				<?php $nowarehousecost +=array_first($sohead->getnowarehouseCost())->nowarehousecost;?>
				<?php $nowarehousetaxcost +=array_first($sohead->getnowarehousetaxCost())->nowarehousetaxcost;?>
				<?php $warehouseqty +=array_first($sohead->getwarehouseqty())->warehouseqty;?>
				<?php $drawingqty +=$sohead->Issuedrawings->sum('tonnage');?>
                <?php $sohead_othercostpercent += $sohead->othercostpercent;?>
		@endforeach
			<p>订单总金额：{{ $totalamount }}万</p>
			<p>订单收款总金额：{{ $sohead_receiptpayments_total }}万
				<a href="{{ URL::to('/sales/projects/'.$project->id.'/paymentdetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">订单收款明细</a></p>
			@if ($totalamount > 0.0)
				<p>订单收款比例：{{ number_format($sohead_receiptpayments_total / $totalamount * 100.0, 2) }}%</p>
			@else
				<p>订单收款比例：-</p>
			@endif
			<p>订单开票总金额：{{ $sohead_tickets_total }}万
				<a href="{{ URL::to('/sales/projects/'.$project->id.'/ticketsdetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">订单开票明细</a></p>
			<p>对应的采购订单合同金额总额：{{ number_format($pohead_amount_total / 10000.0, 4) }}万
				<a href="{{ URL::to('/sales/projects/'.$project->id.'/purchaseticketamountdetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">采购合同明细</a></p> 	{{-- 似乎写到数据库视图中速度更快 --}}
			<p>对应的采购订单开票金额总额：{{ number_format($pohead_amount_ticketed_total / 10000.0, 4) }}万
				<a href="{{ URL::to('/sales/projects/'.$project->id.'/purchaseticketamountdetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">采购开票明细</a></p>
			<p>公用订单分摊成本金额：{{ number_format($poheadAmountBy7550 / 10000.0, 4)  }}万</p>
			<p>税差：{{ number_format(($sohead_taxamount - $sohead_poheadtaxamount) / 10000.0, 4) }}万</p>
			@if ($totalamount > 0.0)
				<p>采购成本比例：{{ number_format(($pohead_amount_total + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount) / ($totalamount * 10000.0) * 100.0, 2) + $sohead_othercostpercent * 100 }}%
					(含公摊{{ number_format($poheadAmountBy7550 / ($totalamount * 10000.0) * 100.0, 2) }}%、
					税差{{ number_format(($sohead_taxamount - $sohead_poheadtaxamount) / ($totalamount * 10000.0) * 100.0, 2) }}%、
					工程采购及差旅合计比例{{ number_format($sohead_othercostpercent * 100.0, 4) }}%)</p>
			@else
				<p>采购成本比例：-</p>
			@endif

			<hr style="border-top-color:rgba(0,0,0,1);" >
			<p>出库物品金额总额：{{number_format($warehousecost/ 10000.0, 4)}}万</p>
			<a href="{{ URL::to('/sales/projects/'.$project->id.'/warehousedetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">出库项目明细</a>
			<a href="{{ URL::to('/sales/projects/'.$project->id.'/otherwarehousedetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">出到其他项目明细</a>
			<a href="{{ URL::to('/sales/projects/'.$project->id.'/fromotherwarehousedetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">从其他项目来明细</a>
			<a href="{{ URL::to('/sales/projects/'.$project->id.'/leftwarehousedetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">剩余项目库存明细</a>
			<p>出库物品税额：{{number_format(( $warehousetaxcost ) / 10000.0, 4)}}万</p>
			<p>无入库记录合同金额总额：{{number_format($nowarehousecost/ 10000.0, 4)}}万
				<a href="{{ URL::to('/sales/projects/'.$project->id.'/nowarehousedetailbyproject/') }}" class="btn btn-default btn-sm" target="_blank">无入库明细</a></p>
			<p>无入库记录物品税额：{{number_format(( $nowarehousetaxcost) / 10000.0, 4)}}万</p>
			@if ($totalamount > 0.0)
				<p>出库类成本比例：{{number_format(($warehousecost  + $nowarehousecost + $sohead_taxamount  - $nowarehousetaxcost - $warehousetaxcost) / ($totalamount * 10000.0) * 100.0, 2)}}%</p>
			@else
				<p>出库类成本比例：-</p>
			@endif
			<p>理论废料数量：{{number_format(($warehouseqty/1000 - $drawingqty) , 4)}}吨</p>
			<hr style="border-top-color:rgba(0,0,0,1);" >
			@if (isset($project->group->id))
				<a href="{{ URL::to('/sales/groups/' . $project->group->id . '/mstatistics') }}" target="_blank" class="btn btn-default btn-sm">备注</a>
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















