@extends('navbarerp')

@section('main')
    {!! Form::model($biddinginformation, ['class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('', '', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-4 col-sm-6'>
            {!! Form::label('aaa', '协议', ['class' => 'control-label']) !!}
        </div>
        <div class='col-xs-4 col-sm-4'>
            {!! Form::label('aaa', '澄清', ['class' => 'control-label']) !!}
        </div>
    </div>

    @foreach($biddinginformation->biddinginformationeditems()->orderBy('sort')->get() as $biddinginformationeditem)
        <div class="form-group">
            {!! Form::label($biddinginformationeditem->key, $biddinginformationeditem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-4 col-sm-6'>
                {!! Form::text($biddinginformationeditem->key, $biddinginformationeditem->value, ['class' => 'form-control', 'readonly', 'oncopy' => 'return false', 'oncontextmenu' => 'return false']) !!}
            </div>
            <div class='col-xs-4 col-sm-4'>
                @if ($biddinginformationeditem->biddinginformationitem() != null)
                    @if ($biddinginformationeditem->value != $biddinginformationeditem->biddinginformationitem()->value)
                        {!! Form::text($biddinginformationeditem->key, $biddinginformationeditem->biddinginformationitem()->value, ['class' => 'form-control', 'readonly', 'oncopy' => 'return false', 'oncontextmenu' => 'return false', 'style' => 'color: red']) !!}
                    @else
                        {!! Form::text($biddinginformationeditem->key, $biddinginformationeditem->biddinginformationitem()->value, ['class' => 'form-control', 'readonly', 'oncopy' => 'return false', 'oncontextmenu' => 'return false']) !!}
                    @endif
                @endif


            </div>
        </div>
    @endforeach

    {{--<div class="form-group">--}}
        {{--<div class="col-sm-offset-2 col-sm-10">--}}
            {{--<a href="{{ url('basic/biddinginformations/exportword/' . $biddinginformation->id) }}" class="btn btn-primary" target="_blank">导出Word</a>--}}
        {{--</div>--}}
    {{--</div>--}}
    {!! Form::close() !!}

@endsection
