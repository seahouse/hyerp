@extends('navbarerp')

@section('main')
    <h1>添加仓库</h1>
    <hr/>
    
    {!! Form::open(['url' => 'inventory/warehouses']) !!}
        @include('inventory.warehouses._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    

    
    @include('errors.list')
@stop
