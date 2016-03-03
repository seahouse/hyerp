@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($characteristic, ['method' => 'PATCH', 'action' => ['Product\CharacteristicsController@update', $characteristic->id]]) !!}
        @include('product.characteristics._form', ['submitButtonText' => '保存', 'bitems' => true])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

