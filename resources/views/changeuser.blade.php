@extends('navbarerp')

@section('title')
切换账号
@endsection

@section('main')
    @if (Auth::user()->isSuperAdmin())
        {!! Form::open(array('url' => 'changeuser_store', 'class' => 'form-horizontal')) !!}
        <div class="form-group">
            {!! Form::label('user_id', '用户:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('user_id', $userList, null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('确定', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    @else
        无权限。
    @endif
@endsection

