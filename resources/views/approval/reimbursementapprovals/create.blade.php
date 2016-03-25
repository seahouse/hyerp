@extends('app')

@section('main')    
	@include('approval.reimbursements.mshow', ['reimbursement' => $reimbursements])
{{--     {!! Form::open(array('url' => 'product/items', 'class' => 'form-horizontal')) !!}
        @include('product.items._form', ['submitButtonText' => '添加', 'marketprice' => '0.0'])
    {!! Form::close() !!}
    --}}
    @include('errors.list')
@stop
    

