@extends('navbarerp')

@section('main')
    <h1>添加项目</h1>
    <hr/>
    
    {!! Form::open(['url' => '/basic/biddingprojects', 'class' => 'form-horizontal']) !!}
        @include('basic.biddingprojects._form', ['submitButtonText' => '添加(Add)', 'attr' => '','btnclass' => 'btn btn-primary',])
    {!! Form::close() !!}

    
    @include('errors.list')
@stop
