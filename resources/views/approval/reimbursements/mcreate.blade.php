@extends('app')

@section('main')
    {!! Form::open(array('url' => 'approval/reimbursements', 'class' => 'form-horizontal')) !!}
        @include('approval.reimbursements._form', ['submitButtonText' => '添加', 'marketprice' => '0.0'])
    {!! Form::close() !!}
@endsection

