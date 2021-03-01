@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($vendinfo, ['method' => 'PATCH', 'action' => ['Purchase\VendinfosController@update', $vendinfo->id]]) !!}
        @include('purchase.vendinfos._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

