@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($salesrep, ['method' => 'PATCH', 'action' => ['Sales\SalesrepsController@update', $salesrep->id]]) !!}
        @include('sales.salesreps._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

