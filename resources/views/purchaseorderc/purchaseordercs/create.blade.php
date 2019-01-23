@extends('navbarerp')

@section('main')
    <h1>添加采购订单</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchaseorderc/purchaseordercs', 'class' => 'form-horizontal']) !!}
        @include('purchaseorderc.purchaseordercs._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    

    
    @include('errors.list')
@stop
