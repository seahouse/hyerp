<div class="form-group">
    {!! Form::label('name', '名称:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('module', '模块:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::select('module', array('系统' => '系统', '销售' => '销售', '采购' => '采购', '库存' => '库存'), null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('titleshow', '表格标题（用","分隔）:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('titleshow', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('active', '有效性:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::radio('active', 1, true) !!}有效
        {!! Form::radio('active', 0) !!}无效
        {{--
        {!! Form::checkbox('active') !!}

        {!! Form::select('active', array(true => '有效', '' => '无效'), null, ['class' => 'form-control']) !!}

        {{ $report->active }}
        --}}
    </div>
</div>


<div class="form-group">
    {!! Form::label('descrip', '描述:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
    </div>
</div>

@if (Auth::user()->email === "admin@admin.com")
<div class="form-group">
    {!! Form::label('statement', '设计:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::textarea('statement', null, ['class' => 'form-control']) !!}
    </div>
</div>
@endif

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>

