@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($contact, ['method' => 'PATCH', 'action' => ['Crm\ContactsController@update', $contact->id]]) !!}
        @include('crm.contacts._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop