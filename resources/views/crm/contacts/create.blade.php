@extends('navbarerp')

@section('main')
    <h1>添加联系人</h1>
    <hr/>
    
    {!! Form::open(['url' => 'crm/contacts']) !!}
        @include('crm.contacts._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
