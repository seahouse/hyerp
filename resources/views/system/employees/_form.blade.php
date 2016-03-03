<div class="form-group">
    {!! Form::label('number', '编号:') !!}
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('name', '姓名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('dept_id', '部门:') !!}
    {!! Form::select('dept_id', $deptList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('notes', '备注:') !!}
    {!! Form::text('notes', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('image_id', '图片:') !!}
    {!! Form::select('image_id', $imageList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('startdate', '入职日期:') !!}
    {!! Form::input('date', 'startdate', null, ['class' => 'form-control']) !!}
{{--    {!! Form::input('date', 'startdate', date('Y-m-d'), ['class' => 'form-control']) !!} --}}
</div>

{{-- 
<div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
    {{ Form::label('date_of_birth', 'Date of Birth', ['class' => 'control-label']) }}
    <div class="form-inline">
        {{ Form::selectRange('date_of_birth[day]', 1, 31, null, ['class' => 'form-control']) }}
        {{ Form::selectMonth('date_of_birth[month]', null, ['class' => 'form-control']) }}
        {{ Form::selectYear('date_of_birth[year]', date('Y') - 3, date('Y') - 16, null, ['class' => 'form-control']) }}
    </div>
    {{ $errors->first('date_of_birth', '<span class="help-block">:message</span>') }}
</div>
--}}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
