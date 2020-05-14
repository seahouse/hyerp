@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($constructionbidinformationfield, ['method' => 'PATCH', 'action' => ['Basic\ConstructionbidinformationfieldController@update', $constructionbidinformationfield->id], 'class' => 'form-horizontal']) !!}
        @include('basic.constructionbidinformationfields._form',
            [
                'submitButtonText' => '保存',
                'attr' => null,
            ]
        )
    {!! Form::close() !!}  



    @include('errors.list')
@endsection

