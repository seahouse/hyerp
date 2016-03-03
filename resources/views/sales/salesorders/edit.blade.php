@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($salesorder, ['method' => 'PATCH', 'action' => ['Sales\SalesordersController@update', $salesorder->id]]) !!}
        @include('sales.salesorders._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

