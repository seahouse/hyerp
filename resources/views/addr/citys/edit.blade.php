@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($city, ['method' => 'PATCH', 'action' => ['Addr\CitysController@update', $city->id]]) !!}
        @include('addr.citys._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop