@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($poitem, ['method' => 'PATCH', 'action' => ['Purchaseorderc\PoitemcController@update', $poitem->id], 'class' => 'form-horizontal']) !!}
        @include('purchaseorderc.poitemcs._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

