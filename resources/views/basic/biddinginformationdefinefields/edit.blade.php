@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($biddinginformationdefinefield, ['method' => 'PATCH', 'action' => ['Basic\BiddinginformationdefinefieldController@update', $biddinginformationdefinefield->id], 'class' => 'form-horizontal']) !!}
        @include('basic.biddinginformationdefinefields._form',
            [
                'submitButtonText' => '保存',
                'attr' => null,
            ]
        )
    {!! Form::close() !!}  



    @include('errors.list')
@endsection

