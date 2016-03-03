@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($purchaseorder, ['method' => 'PATCH', 'action' => ['Purchase\PurchaseordersController@update', $purchaseorder->id]]) !!}
        @include('purchase.purchaseorders._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

