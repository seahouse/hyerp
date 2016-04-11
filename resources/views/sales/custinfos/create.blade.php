@extends('navbarerp')

@section('main')
    <h1>添加客户</h1>
    <hr/>
    
    {!! Form::open(['url' => '/sales/custinfos']) !!}
        @include('sales.custinfos._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
