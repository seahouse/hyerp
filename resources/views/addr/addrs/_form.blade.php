<div class="form-group">
    {!! Form::label('province_id', '省份:') !!}
    {!! Form::select('province_id', array('0' => '--请选择--') + json_decode($provinceList, true), null, ['class' => 'form-control', 'id' => 'province']) !!}
{{--    {!! Form::select('province_id', $provinceList, null, ['class' => 'form-control']) !!} --}}
</div>

<div class="form-group">
    {!! Form::label('city_id', '城市:') !!}
    {!! Form::select('city_id', $cityList, null, ['class' => 'form-control', 'id' => 'city']) !!}
{{--    {!! Form::select('city_id', $cityList, null, ['class' => 'form-control']) !!} --}}
</div>

<div class="form-group">
    {!! Form::label('line1', '详细地址:') !!}
    {!! Form::text('line1', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
