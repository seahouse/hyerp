@extends('app')

{!! Form::model($epcsecening, ['url' => 'approval/epcsecening/' . $epcsecening->id . '/updateamount',  'class' => 'form-horizontal']) !!}
<div class="form-group">
    {!! Form::label('amount_whl', '吴总定价:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('amount_whl', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}