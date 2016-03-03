@extends('navbarerp')

@section('main')
    <h1>编辑物料类别</h1>
    <hr/>
    
    {!! Form::model($itemclass, ['method' => 'PATCH', 'action' => ['Product\ItemclassesController@update', $itemclass->id], 'class' => 'form-horizontal']) !!}
        @include('product.itemclasses._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
