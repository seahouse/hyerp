@extends('navbarerp')

@section('main')
    <h1>{!! $bomitem->item->item_number !!}</h1>
    <hr/>
    
    {!! Form::model($bomitem, ['method' => 'PATCH', 'action' => ['Product\BomitemsController@update', $bomitem->id]]) !!}
        @include('product.bomitems._form', ['parentSelected' => $bomitem->parent_item_id, 'submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
