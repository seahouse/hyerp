@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($vendtype, ['method' => 'PATCH', 'action' => ['Purchase\VendtypesController@update', $vendtype->id]]) !!}
        @include('purchase.vendtypes._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

