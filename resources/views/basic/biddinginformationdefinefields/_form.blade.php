
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
    {!! Form::label('exceltype', 'Excel类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('exceltype', array('汇总表' => '汇总表', '项目明细' => '项目明细', '汇总明细' => '汇总明细'), null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('projecttype', '项目类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('projecttype', array('SDA半干法系统' => 'SDA半干法系统', '湿法系统' => '湿法系统', 'SNCR系统' => 'SNCR系统', 'SCR系统' => 'SCR系统', '飞灰输送系统' => '飞灰输送系统',
            '灰库系统' => '灰库系统', '稳定化系统' => '稳定化系统', 'CFB系统' => 'CFB系统', '固定喷雾系统' => '固定喷雾系统', '现场实测数据' => '现场实测数据', '公用系统' => '公用系统'), null, ['class' => 'form-control', $attr, 'placeholder' => '--请选择--']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('select_strings', '选择字符串:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('select_strings', null, ['class' => 'form-control', $attr, 'placeholder' => '逗号分隔']) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>
