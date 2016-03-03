@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($poitem, ['method' => 'PATCH', 'action' => ['Purchase\PoitemsController@update', $poitem->id]]) !!}
        @include('purchase.poitems._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

