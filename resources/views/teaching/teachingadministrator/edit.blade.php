@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($teachingadministrator, ['method' => 'PATCH', 'action' => ['Teaching\TeachingadministratorController@update', $teachingadministrator->id], 'class' => 'form-horizontal']) !!}
        @include('teaching.teachingadministrator._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
 {{--     <ul class="nav nav-tabs">
        {{ URL('items/edit_charass') }}
        <li role="presentation" class="active"><a href="/items/edit_charass">Home</a></li>
        <li role="presentation"><a href="#">Profile</a></li>
        <li role="presentation"><a href="#">Messages</a></li>
    </ul> --}}
    


    @include('errors.list')
@endsection


@section('script')


@endsection
