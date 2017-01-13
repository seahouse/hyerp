<div class="row">
    <div class="col-xs-2 col-sm-2">供应商类型:</div>
    <div class="col-xs-4 col-sm-4">{{ $paymentrequest->suppliertype }}</div>

    <div class="col-xs-2 col-sm-2">付款类型:</div>
    <div class="col-xs-4 col-sm-4">{{ $paymentrequest->paymenttype }}</div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">支付对象:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest->supplier_hxold->name))
            {{ $paymentrequest->supplier_hxold->name }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">采购合同:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest->purchaseorder_hxold->number))
            {{ $paymentrequest->purchaseorder_hxold->number }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">对应工程名称:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest->purchaseorder_hxold->sohead->custinfo->name))
            {{ $paymentrequest->purchaseorder_hxold->sohead->custinfo->name . ' | ' . $paymentrequest->purchaseorder_hxold->sohead->descrip }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">合同金额:</div>
    <div class="col-xs-4 col-sm-4">
        @if (isset($paymentrequest->purchaseorder_hxold->amount))
            {{ $paymentrequest->purchaseorder_hxold->amount }}
        @endif
    </div>

    <div class="col-xs-2 col-sm-2">已付金额:</div>
    <div class="col-xs-4 col-sm-4">
        @if (isset($paymentrequest->purchaseorder_hxold->amount_paid))
            {{ $paymentrequest->purchaseorder_hxold->amount_paid }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">已开票金额:</div>
    <div class="col-xs-4 col-sm-4">
        @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed))
            {{ $paymentrequest->purchaseorder_hxold->amount_ticketed }}
        @endif
    </div>

    <div class="col-xs-2 col-sm-2">到货情况:</div>
    <div class="col-xs-4 col-sm-4">
         @if (isset($paymentrequest->purchaseorder_hxold->arrival_percent))
            @if ($paymentrequest->purchaseorder_hxold->arrival_percent <= 0.0)
                未到货
            @elseif ($paymentrequest->purchaseorder_hxold->arrival_percent > 0.0 and $paymentrequest->purchaseorder_hxold->arrival_percent < 0.99)
                部分到货
            @else
                全部到货
            @endif
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">付款方式:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest->purchaseorder_hxold->paymethod))
            {{ $paymentrequest->purchaseorder_hxold->paymethod }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">安装完毕日期:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate))
            {{ substr($paymentrequest->purchaseorder_hxold->sohead->installeddate, 0, 10) }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">采购商品名称:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest->purchaseorder_hxold->productname))
            {{ $paymentrequest->purchaseorder_hxold->productname }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">说明:</div>
    <div class="col-xs-10 col-sm-10">
        {{ $paymentrequest->descrip }}
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">本次请款额:</div>
    <div class="col-xs-4 col-sm-4">
        {{ $paymentrequest->amount }}
    </div>

    <div class="col-xs-2 col-sm-2">付款方式:</div>
    <div class="col-xs-4 col-sm-4">
         {{ $paymentrequest->paymentmethod }}
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">付款日期:</div>
    <div class="col-xs-10 col-sm-10">
        {{ $paymentrequest->datepay }}
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">开户行:</div>
    <div class="col-xs-4 col-sm-4">
        @if (isset($paymentrequest->vendbank_hxold->bankname))
            {{ $paymentrequest->vendbank_hxold->bankname }}
        @endif
    </div>

    <div class="col-xs-2 col-sm-2">银行账号:</div>
    <div class="col-xs-4 col-sm-4">
        @if (isset($paymentrequest->vendbank_hxold->accountnum))
            {{ $paymentrequest->vendbank_hxold->accountnum }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">付款节点审批单:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest))
            @foreach ($paymentrequest->paymentnodes() as $paymentnode)
                 <a href="{!! $paymentnode->path !!}" target="_blank">{{ $paymentnode->filename }}</a> <br>
            @endforeach
        @else
            {!! Form::file('paymentnodeattachments[]', ['multiple']) !!}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">商务合同等必要附件:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest))
            @if (isset($paymentrequest->purchaseorder_hxold->businesscontract))
                <a href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $paymentrequest->purchaseorder_hxold->id . '/' . $paymentrequest->purchaseorder_hxold->businesscontract !!}" target="_blank">{{ $paymentrequest->purchaseorder_hxold->businesscontract }}</a> <br>
            @endif

            @foreach ($paymentrequest->businesscontracts() as $businesscontract)
                <a href="{!! $businesscontract->path !!}" target="_blank">{{ $businesscontract->filename }}</a> <br>
            @endforeach
        @else
            {!! Form::file('businesscontractattachments[]', ['multiple']) !!}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-2 col-sm-2">商务合同等必要附件:</div>
    <div class="col-xs-10 col-sm-10">
        @if (isset($paymentrequest))
            <div class="row" id="previewimage">
                @foreach ($paymentrequest->paymentrequestimages() as $paymentrequestimage)
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail">
                            <img src="{!! $paymentrequestimage->path !!}" />
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @if ($agent->isDesktop())
                {!! Form::file('images[]', ['multiple']) !!}
            @else
                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}
            @endif            
        @endif
    </div>
</div>

<hr>

@if (isset($paymentrequest))
    @if ($paymentrequest->paymentrequestapprovals->count())
        <div class="row"><strong>--审批记录--</strong>
        </div>

        @foreach ($paymentrequest->paymentrequestapprovals as $paymentrequestapproval)
            <div class="row">
                <div class="col-xs-2 col-sm-2">审批人:</div>
                <div class="col-xs-4 col-sm-4">
                    {{ $paymentrequestapproval->approver->name }}
                </div>

                <div class="col-xs-2 col-sm-2">审批结果:</div>
                <div class="col-xs-4 col-sm-4">
                    @if ($paymentrequestapproval->status==0)
                        通过
                    @else
                        未通过
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-2 col-sm-2">审批描述:</div>
                <div class="col-xs-4 col-sm-4">
                    {{ $paymentrequestapproval->description }}
                </div>

                <div class="col-xs-2 col-sm-2">审批时间:</div>
                <div class="col-xs-4 col-sm-4">
                    {{ $paymentrequestapproval->created_at }}
                </div>
            </div>

            <br>
        @endforeach
    @endif
@endif