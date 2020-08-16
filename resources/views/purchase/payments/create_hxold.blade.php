@extends('navbarerp')

@section('main')
    <h1>添加付款记录</h1>
    <hr/>

    <?php $can_payment = false; ?>
    @can('purchase_purchaseorder_payment')
        <?php $can_payment = true; ?>
    @else
        {{-- 当 对公付款审批 的类型是“安装合同安装费付款”，且采购商品名称是“钢结构安装”，开放权限给发起人 --}}
        @if (strpos($purchaseorder->productname, '钢结构安装') >= 0)
            @if ($purchaseorder->corporatepayments()->where('status', '>=', 0)->where('applicant_id', Auth::user()->id)->count())
                <?php $can_payment = true; ?>
            @endif
        @endif
    @endcan
    @if ($can_payment)
        {!! Form::open(['url' => 'purchase/purchaseorders/' . $purchaseorder->id . '/payments/store_hxold', 'class' => 'form-horizontal']) !!}
        @include('purchase.payments._form_hxold', ['submitButtonText' => '添加', 'paydate' => date('Y-m-d')])
        {!! Form::close() !!}
    @else
        无权限。
    @endif

    
    @include('errors.list')
@stop
