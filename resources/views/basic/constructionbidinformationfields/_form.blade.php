<div class="form-group">
    {!! Form::label('name', '名称:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('name', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('sort', '排序:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('sort', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('type', '类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('type', array(1 => '字符串', 2 => '单选'), null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('projecttype', '项目类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('projecttype', $projecttypes_constructionbidinformationfield, null, ['class' => 'form-control', $attr, 'placeholder' => '--请选择--']) !!}
    </div>
</div>

{{--<div class="form-group">--}}
{{--{!! Form::label('select_strings', '选择字符串:', ['class' => 'col-sm-2 control-label']) !!}--}}
{{--<div class='col-sm-10'>--}}
{{--{!! Form::text('select_strings', null, ['class' => 'form-control', $attr, 'placeholder' => '逗号分隔']) !!}--}}
{{--</div>--}}
{{--</div>--}}

<div class="form-group">
    {!! Form::label('unitprice', '华星单价:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('unitprice', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('unitprice_bidder', '投标人单价:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('unitprice_bidder', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('unit', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('unit', $unitstrList, null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>