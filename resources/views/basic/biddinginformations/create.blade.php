@extends('navbarerp')

@section('main')
    @can('basic_biddinginformation_edit')
        <h2>中标信息 -- 添加</h2>
        <hr/>

        {!! Form::open(array('url' => 'basic/biddinginformations', 'class' => 'form-horizontal')) !!}
            @include('basic.biddinginformations._form', ['submitButtonText' => '添加'])

            @foreach($biddinginformationdefinefields as $biddinginformationdefinefield)
                <div class="form-group">
                    {!! Form::label($biddinginformationdefinefield->name, $biddinginformationdefinefield->name, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text($biddinginformationdefinefield->name, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            @endforeach

            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('添加', ['class' => 'btn btn-primary']) !!}
            </div>
            </div>
        {!! Form::close() !!}

        @include('errors.list')
    @else
        无权限
    @endcan
@endsection
