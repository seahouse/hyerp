@extends('navbarerp')

@section('main')
    <h1>添加员工</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/employees']) !!}
        @include('system.employees._form', ['submitButtonText' => '添加', 'startdate' => DB::raw('NOW()')])
    {!! Form::close() !!}
    


    
    {{-- 
    <form>
        <div class="form-group">
            <label for="province">Select Province:</label>
                {!! Form::select('province_id', array('0' => '--请选择--') + json_decode($provinceList, true), null, ['class' => 'form-control', 'id' => 'province']) !!} 
            </select>
        </div>
        
        <div class="form-group">
            <label for="city">Select City:</label>
            {!! Form::select('city_id', array(), null, ['class' => 'form-control', 'id' => 'city']) !!}
        </div>
    </form>
    --}}

    
    @include('errors.list')
@stop
