@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($custinfo, ['method' => 'PATCH', 'action' => ['CustinfosController@update', $custinfo->id]]) !!}
        @include('custinfos._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop