@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($soitem, ['method' => 'PATCH', 'action' => ['Sales\SoitemsController@update', $soitem->id]]) !!}
        @include('sales.soitems._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

