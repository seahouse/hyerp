@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($term, ['method' => 'PATCH', 'action' => ['Sales\TermsController@update', $term->id]]) !!}
        @include('sales.terms._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

